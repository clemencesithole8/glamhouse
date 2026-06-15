<?php
namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Booking extends Model
{
    public const STATUSES = ['pending', 'reviewed', 'confirmed', 'completed', 'cancelled'];

    protected $fillable = [
        'full_name','phone','email','location_area',
        'appointment_date','time_slot_id','preferred_time_text',
        'service_id','event_type','is_outcall','outcall_address',
        'skin_type','allergies_notes','has_done_pro_makeup',
        'reference_image_path','deposit_ack','lateness_ack','info_confirmed',
        'status','deposit_amount','total_amount','admin_notes',
        'reminder_sent_at','reschedule_requested_at','reschedule_note',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'reminder_sent_at' => 'datetime',
        'reschedule_requested_at' => 'datetime',
        'is_outcall' => 'boolean',
        'has_done_pro_makeup' => 'boolean',
        'deposit_ack' => 'boolean',
        'lateness_ack' => 'boolean',
        'info_confirmed' => 'boolean',
    ];
    use Notifiable;
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute(): int
    {
        if ($this->relationLoaded('payments')) {
            return (int) $this->payments->sum('amount');
        }

        return (int) $this->payments()->sum('amount');
    }

    public function getBalanceDueAttribute(): ?int
    {
        if ($this->total_amount === null) return null;
        return max(0, (int)$this->total_amount - $this->total_paid);
    }

    public function getPaymentStatusAttribute(): string
    {
        $paid = $this->total_paid;
        $total = $this->total_amount !== null ? (int) $this->total_amount : null;
        $deposit = $this->deposit_amount !== null ? (int) $this->deposit_amount : null;

        if ($paid <= 0) {
            return 'unpaid';
        }

        if ($total !== null && $total > 0) {
            if ($paid >= $total) {
                return 'paid';
            }

            if (($deposit !== null && $deposit > 0 && $paid >= $deposit) || $paid > 0) {
                return $deposit !== null && $deposit > 0 && $paid >= $deposit ? 'balance_due' : 'partial';
            }
        }

        if ($deposit !== null && $deposit > 0) {
            return $paid >= $deposit ? 'paid' : 'partial';
        }

        return 'partial';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'Unpaid',
            'partial' => 'Partial',
            'paid' => 'Paid',
            'balance_due' => 'Balance due',
            default => ucfirst((string) $this->payment_status),
        };
    }

    public function getPaymentStatusClassesAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'bg-rose-100 text-rose-900',
            'partial' => 'bg-amber-100 text-amber-900',
            'paid' => 'bg-emerald-100 text-emerald-900',
            'balance_due' => 'bg-sky-100 text-sky-900',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function appointmentDateTime(): ?Carbon
    {
        if (! $this->appointment_date) {
            return null;
        }

        $appointmentAt = $this->appointment_date->copy()->startOfDay();
        $startTime = $this->timeSlot?->start_time;

        if (! $startTime) {
            return $appointmentAt;
        }

        $parts = explode(':', (string) $startTime);

        return $appointmentAt->setTime((int) ($parts[0] ?? 0), (int) ($parts[1] ?? 0));
    }

}
