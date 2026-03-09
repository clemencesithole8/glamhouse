<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Booking extends Model
{
    protected $fillable = [
        'full_name','phone','email','location_area',
        'appointment_date','time_slot_id','preferred_time_text',
        'service_id','event_type','is_outcall','outcall_address',
        'skin_type','allergies_notes','has_done_pro_makeup',
        'reference_image_path','deposit_ack','lateness_ack','info_confirmed',
        'status','deposit_amount','total_amount','admin_notes'
    ];

    protected $casts = [
        'appointment_date' => 'date',
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
        return (int) $this->payments()->sum('amount');
    }

    public function getBalanceDueAttribute(): ?int
    {
        if ($this->total_amount === null) return null;
        return max(0, (int)$this->total_amount - $this->total_paid);
    }

}
