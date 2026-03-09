<?php
namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingSubmittedClientNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Glamhouse Booking Received (#{$this->booking->id})")
            ->greeting("Hi {$this->booking->full_name},")
            ->line("Thank you for booking with Esther's Secrets – Glamhouse.")
            ->line("Your booking request has been received and is pending confirmation once the deposit/retainer is paid.")
            ->action('Download Booking Summary (PDF)', route('booking.pdf', $this->booking))
            ->line("If you need to reschedule, please do so at least 24 hours in advance (subject to availability).");
    }
}
