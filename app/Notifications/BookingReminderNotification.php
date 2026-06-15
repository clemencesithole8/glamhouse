<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class BookingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $time = $this->booking->timeSlot
            ? substr((string) $this->booking->timeSlot->start_time, 0, 5)
            : ($this->booking->preferred_time_text ?: 'your confirmed time');

        return (new MailMessage)
            ->subject("Reminder: Glamhouse Appointment Tomorrow (#{$this->booking->id})")
            ->greeting("Hi {$this->booking->full_name},")
            ->line('This is a friendly reminder for your Glamhouse appointment.')
            ->line('Date: '.$this->booking->appointment_date->format('D, d M Y'))
            ->line('Time: '.$time)
            ->line('Service: '.($this->booking->service?->name ?? 'Glamhouse service'))
            ->action('View Booking Summary', URL::temporarySignedRoute('booking.pdf', now()->addDays(2), ['bookingId' => $this->booking->id]))
            ->line('If you need to request a reschedule, please do so as soon as possible from your dashboard.');
    }
}
