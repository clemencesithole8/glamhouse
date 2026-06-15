<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailabilityBlock extends Model
{
    public const TYPE_UNAVAILABLE_DATE = 'unavailable_date';
    public const TYPE_BLOCKED_DAY = 'blocked_day';
    public const TYPE_HOLIDAY = 'holiday';

    public const TYPES = [
        self::TYPE_UNAVAILABLE_DATE => 'Unavailable date',
        self::TYPE_BLOCKED_DAY => 'Blocked weekday',
        self::TYPE_HOLIDAY => 'Holiday',
    ];

    public const WEEKDAYS = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    protected $fillable = [
        'type',
        'name',
        'start_date',
        'end_date',
        'day_of_week',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'day_of_week' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst(str_replace('_', ' ', (string) $this->type));
    }

    public function getScheduleLabelAttribute(): string
    {
        if ($this->type === self::TYPE_BLOCKED_DAY) {
            return self::WEEKDAYS[$this->day_of_week] ?? 'Weekday not set';
        }

        $start = $this->start_date?->format('d M Y') ?? 'Date not set';
        $end = $this->end_date?->format('d M Y');

        return $end && $end !== $start ? "{$start} - {$end}" : $start;
    }
}
