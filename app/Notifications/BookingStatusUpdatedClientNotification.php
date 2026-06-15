<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class BookingStatusUpdatedClientNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $previousStatus,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = ucfirst($this->booking->status);

        $message = (new MailMessage)
            ->subject("Glamhouse Booking {$status} (#{$this->booking->id})")
            ->greeting("Hi {$this->booking->full_name},")
            ->line("Your Glamhouse booking status changed from {$this->previousStatus} to {$this->booking->status}.")
            ->line($this->statusLine())
            ->action('Download Booking Summary', URL::temporarySignedRoute('booking.pdf', now()->addDays(7), ['bookingId' => $this->booking->id]));

        if ($this->booking->admin_notes) {
            $message->line('Please contact Glamhouse if you have any questions about the update.');
        }

        return $message;
    }

    private function statusLine(): string
    {
        return match ($this->booking->status) {
            'confirmed' => 'Your appointment is confirmed. Please keep your date and time available.',
            'completed' => 'Thank you for choosing Glamhouse. Your appointment has been marked completed.',
            'cancelled' => 'Your appointment has been cancelled. Please contact Glamhouse if you need to book a new time.',
            default => 'We will keep you updated as your booking progresses.',
        };
    }
}
