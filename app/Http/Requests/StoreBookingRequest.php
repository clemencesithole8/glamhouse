<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Client
            'full_name' => ['required','string','max:255'],
            'phone' => ['required','string','max:50'],
            'email' => ['nullable','email','max:255'],
            'location_area' => ['required','string','max:255'],

            // Booking
            'appointment_date' => ['required','date','after_or_equal:today'],
            'time_slot_id' => ['nullable','integer','exists:time_slots,id'],
            'preferred_time_text' => ['nullable','string','max:100'],
            'service_id' => ['required','integer','exists:services,id'],

            // Event
            'event_type' => ['nullable','string','max:255'],
            'is_outcall' => ['required','boolean'],
            'outcall_address' => ['nullable','string','max:2000'],

            // Makeup
            'skin_type' => ['nullable','in:Oily,Dry,Combination,Not sure'],
            'allergies_notes' => ['nullable','string','max:2000'],
            'has_done_pro_makeup' => ['required','boolean'],

            // Upload
            'reference_look' => ['nullable','file','mimes:jpg,jpeg,png,webp','max:5120'],

            // Terms
            'deposit_ack' => ['required','accepted'],
            'lateness_ack' => ['required','accepted'],
            'info_confirmed' => ['required','accepted'],

            'how_heard' => ['nullable','string','max:50'],
        ];
    }
}
