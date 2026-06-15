<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WhatsAppNotifier
{
    public function sendNewBookingAlert(Booking $booking): bool
    {
        if (!config('glamhouse.whatsapp_notifications.enabled')) {
            Log::info('WhatsApp notifier skipped: notifications disabled.', [
                'booking_id' => $booking->id,
            ]);

            return false;
        }

        $provider = config('glamhouse.whatsapp_notifications.provider', 'meta');

        return match ($provider) {
            'meta' => $this->sendViaMeta($booking),
            default => $this->skipUnsupportedProvider((string) $provider, $booking),
        };
    }

    protected function skipUnsupportedProvider(string $provider, Booking $booking): bool
    {
        Log::warning('WhatsApp notifier skipped: unsupported provider configured.', [
            'booking_id' => $booking->id,
            'provider' => $provider,
        ]);

        return false;
    }

    protected function sendViaMeta(Booking $booking): bool
    {
        $token = (string) config('glamhouse.whatsapp_notifications.meta.access_token');
        $phoneNumberId = (string) config('glamhouse.whatsapp_notifications.meta.phone_number_id');
        $apiVersion = (string) config('glamhouse.whatsapp_notifications.meta.api_version', 'v21.0');
        $recipient = $this->normalizeNumber((string) config('glamhouse.whatsapp_notifications.admin_number'));

        if ($token === '' || $phoneNumberId === '' || $recipient === '') {
            Log::warning('WhatsApp notifier skipped: missing Meta config values.');
            return false;
        }

        $message = $this->buildMessage($booking);
        $endpoint = "https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages";

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->acceptJson()
                ->post($endpoint, [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $recipient,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => false,
                        'body' => $message,
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('WhatsApp notifier request failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (Throwable $e) {
            Log::error('WhatsApp notifier exception.', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function buildMessage(Booking $booking): string
    {
        $serviceName = $booking->service?->name ?? 'Service not set';
        $date = optional($booking->appointment_date)->format('D, d M Y') ?: 'Date not set';
        $time = $booking->timeSlot
            ? "{$booking->timeSlot->start_time} - {$booking->timeSlot->end_time}"
            : ($booking->preferred_time_text ?: 'Time pending');

        return implode("\n", [
            'New Glamhouse Booking',
            "ID: #{$booking->id}",
            "Client: {$booking->full_name}",
            "Phone: {$booking->phone}",
            "Service: {$serviceName}",
            "Date: {$date}",
            "Time: {$time}",
            'Status: Pending',
            "Admin: " . route('admin.bookings.show', $booking),
        ]);
    }

    protected function normalizeNumber(string $number): string
    {
        return preg_replace('/\D+/', '', $number) ?? '';
    }
}
