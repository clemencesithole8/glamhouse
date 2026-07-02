<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $phone = trim((string) $this->input('phone', ''));
        $phone = preg_replace('/[\s().-]+/', '', $phone) ?? $phone;

        if (str_starts_with($phone, '00')) {
            $phone = '+'.substr($phone, 2);
        }

        $email = $this->input('email');

        $this->merge([
            'full_name' => trim((string) $this->input('full_name', '')),
            'phone' => $phone,
            'email' => is_string($email) ? strtolower(trim($email)) : $email,
            'location_area' => trim((string) $this->input('location_area', '')),
        ]);
    }

    public function rules(): array
    {
        $serviceRules = ['required','integer'];
        $timeSlotRules = ['nullable','integer'];

        if (Schema::hasTable('services')) {
            $serviceRules[] = 'exists:services,id';
        }

        if (Schema::hasTable('time_slots')) {
            $timeSlotRules[] = 'exists:time_slots,id';
        }

        return [
            // Client
            'full_name' => ['required','string','max:255'],
            'phone' => ['required','string','max:20','regex:/\A(?:\+[1-9]\d{7,14}|263\d{8,9}|0\d{8,9})\z/'],
            'email' => ['required','string','email:rfc,strict','max:255'],
            'location_area' => ['required','string','max:255'],

            // Booking
            'appointment_date' => ['required','date','after_or_equal:today'],
            'time_slot_id' => $timeSlotRules,
            'preferred_time_text' => ['nullable','string','max:100'],
            'service_id' => $serviceRules,

            // Event
            'event_type' => ['nullable','string','max:255'],
            'is_outcall' => ['required','boolean'],
            'outcall_address' => ['nullable','string','max:2000'],

            // Makeup
            'skin_type' => ['nullable','in:Oily,Dry,Combination,Not sure'],
            'allergies_notes' => ['nullable','string','max:2000'],
            'has_done_pro_makeup' => ['required','boolean'],

            // Upload
            'reference_look' => ['nullable','image','mimetypes:image/jpeg,image/png,image/webp','max:5120','dimensions:min_width=100,min_height=100'],

            // Terms
            'deposit_ack' => ['required','accepted'],
            'lateness_ack' => ['required','accepted'],
            'info_confirmed' => ['required','accepted'],

            'how_heard' => ['nullable','string','max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid phone number, e.g. +263784721479 or 0770000000.',
            'email.required' => 'Enter a valid email address so we can send your booking confirmation.',
            'email.email' => 'Enter a valid email address so we can send your booking confirmation.',
        ];
    }
}
