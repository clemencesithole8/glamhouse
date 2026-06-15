<?php

namespace App\Services;

use App\Models\AvailabilityBlock;
use App\Models\Booking;
use App\Models\Setting;
use App\Models\TimeSlot;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Throwable;

class BookingAvailabilityService
{
    public const BLOCKING_STATUSES = ['pending', 'reviewed', 'confirmed'];

    public function slotsForDate(string|CarbonInterface $date, bool $isOutcall = false, ?Booking $ignoreBooking = null): Collection
    {
        $dateString = $this->normalizeDate($date);

        if (! Schema::hasTable('time_slots')) {
            return collect();
        }

        $slots = TimeSlot::query()
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        $dateBlockReason = $this->dateBlockReason($dateString);
        $bookings = $dateBlockReason ? collect() : $this->blockingBookingsForDate($dateString, $ignoreBooking);
        $bufferMinutes = $this->outcallBufferMinutes();

        return $slots->map(function (TimeSlot $slot) use ($dateString, $dateBlockReason, $bookings, $bufferMinutes, $isOutcall): array {
            $reason = $dateBlockReason
                ?? $this->slotConflictReason($dateString, $slot, $bookings, $isOutcall, $bufferMinutes);

            return [
                'id' => $slot->id,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'is_available' => $reason === null,
                'reason' => $reason,
            ];
        });
    }

    public function isDateBookable(string|CarbonInterface $date): bool
    {
        return $this->dateBlockReason($this->normalizeDate($date)) === null;
    }

    public function dateBlockReason(string|CarbonInterface $date): ?string
    {
        $date = Carbon::parse($date)->startOfDay();
        $weekday = $date->dayOfWeek;

        try {
            $blocks = AvailabilityBlock::query()
                ->where('is_active', true)
                ->get();
        } catch (Throwable) {
            return null;
        }

        foreach ($blocks as $block) {
            if ($block->type === AvailabilityBlock::TYPE_BLOCKED_DAY && (int) $block->day_of_week === $weekday) {
                return $block->name;
            }

            if (! in_array($block->type, [AvailabilityBlock::TYPE_UNAVAILABLE_DATE, AvailabilityBlock::TYPE_HOLIDAY], true)) {
                continue;
            }

            if (! $block->start_date) {
                continue;
            }

            $start = $block->start_date->copy()->startOfDay();
            $end = ($block->end_date ?: $block->start_date)->copy()->endOfDay();

            if ($date->betweenIncluded($start, $end)) {
                return $block->name;
            }
        }

        return null;
    }

    public function isSlotAvailable(
        string|CarbonInterface $date,
        int $timeSlotId,
        bool $isOutcall = false,
        ?Booking $ignoreBooking = null,
        bool $lock = false,
    ): bool {
        $dateString = $this->normalizeDate($date);

        if ($this->dateBlockReason($dateString) !== null) {
            return false;
        }

        if (! Schema::hasTable('time_slots')) {
            return false;
        }

        $slotQuery = TimeSlot::query()
            ->whereKey($timeSlotId)
            ->where('is_active', true);

        if ($lock) {
            $slotQuery->lockForUpdate();
        }

        $slot = $slotQuery->first();

        if (! $slot) {
            return false;
        }

        $bookings = $this->blockingBookingsForDate($dateString, $ignoreBooking, $lock);

        return $this->slotConflictReason(
            $dateString,
            $slot,
            $bookings,
            $isOutcall,
            $this->outcallBufferMinutes(),
        ) === null;
    }

    public function outcallBufferMinutes(): int
    {
        $minutes = (int) Setting::valueFor('outcall_travel_buffer_minutes', '0');

        return max(0, min($minutes, 720));
    }

    private function blockingBookingsForDate(string $date, ?Booking $ignoreBooking = null, bool $lock = false): Collection
    {
        if (! Schema::hasTable('bookings')) {
            return collect();
        }

        $query = Booking::query()
            ->whereDate('appointment_date', $date)
            ->whereIn('status', self::BLOCKING_STATUSES)
            ->whereNotNull('time_slot_id')
            ->with('timeSlot');

        if ($ignoreBooking) {
            $query->whereKeyNot($ignoreBooking->getKey());
        }

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->get();
    }

    private function slotConflictReason(
        string $date,
        TimeSlot $slot,
        Collection|EloquentCollection $bookings,
        bool $candidateIsOutcall,
        int $bufferMinutes,
    ): ?string {
        $candidateStart = $this->slotStart($date, $slot);
        $candidateEnd = $this->slotEnd($date, $slot);
        $candidateWindowStart = $candidateIsOutcall ? $candidateStart->copy()->subMinutes($bufferMinutes) : $candidateStart;
        $candidateWindowEnd = $candidateIsOutcall ? $candidateEnd->copy()->addMinutes($bufferMinutes) : $candidateEnd;

        foreach ($bookings as $booking) {
            if ((int) $booking->time_slot_id === (int) $slot->id) {
                return 'Booked';
            }

            if (! $booking->timeSlot) {
                continue;
            }

            $existingStart = $this->slotStart($date, $booking->timeSlot);
            $existingEnd = $this->slotEnd($date, $booking->timeSlot);
            $existingWindowStart = $booking->is_outcall ? $existingStart->copy()->subMinutes($bufferMinutes) : $existingStart;
            $existingWindowEnd = $booking->is_outcall ? $existingEnd->copy()->addMinutes($bufferMinutes) : $existingEnd;

            if ($candidateWindowStart->lt($existingWindowEnd) && $candidateWindowEnd->gt($existingWindowStart)) {
                return $booking->is_outcall || $candidateIsOutcall ? 'Travel buffer' : 'Booked';
            }
        }

        return null;
    }

    private function slotStart(string $date, TimeSlot $slot): Carbon
    {
        return Carbon::parse($date.' '.$slot->start_time);
    }

    private function slotEnd(string $date, TimeSlot $slot): Carbon
    {
        return Carbon::parse($date.' '.$slot->end_time);
    }

    private function normalizeDate(string|CarbonInterface $date): string
    {
        return Carbon::parse($date)->toDateString();
    }
}
