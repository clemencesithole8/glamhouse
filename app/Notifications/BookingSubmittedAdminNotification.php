<?php
namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingSubmittedAdminNotification extends Notification implements ShouldQueue
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
            ->subject("New Glamhouse Booking (#{$this->booking->id})")
            ->line("Service: {$this->booking->service->name}")
            ->line("Date: {$this->booking->appointment_date->format('D, d M Y')}")
            ->line("Client: {$this->booking->full_name} | {$this->booking->phone}")
            ->action('View in Admin', route('admin.bookings.show', $this->booking));
    }
}
