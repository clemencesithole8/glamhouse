<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class BookingRescheduledClientNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $previousDate,
        public string $previousTime,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Glamhouse Booking Rescheduled (#{$this->booking->id})")
            ->greeting("Hi {$this->booking->full_name},")
            ->line("Your Glamhouse booking has been rescheduled.")
            ->line("Previous appointment: {$this->previousDate} at {$this->previousTime}.")
            ->line('New appointment: '.$this->newAppointmentLabel().'.')
            ->action('Download Updated Booking Summary', URL::temporarySignedRoute('booking.pdf', now()->addDays(7), ['bookingId' => $this->booking->id]))
            ->line('Please contact Glamhouse if this new time needs another adjustment.');
    }

    private function newAppointmentLabel(): string
    {
        $date = $this->booking->appointment_date?->format('D, d M Y') ?? 'Date pending';
        $time = $this->booking->timeSlot
            ? $this->booking->timeSlot->start_time.' - '.$this->booking->timeSlot->end_time
            : ($this->booking->preferred_time_text ?: 'Time pending');

        return "{$date} at {$time}";
    }
}
