<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRescheduleRequestedAdminNotification extends Notification implements ShouldQueue
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
            ->subject("Reschedule Requested (#{$this->booking->id})")
            ->line("Client: {$this->booking->full_name} | {$this->booking->phone}")
            ->line('Appointment: '.$this->booking->appointment_date->format('D, d M Y'))
            ->line('Service: '.($this->booking->service?->name ?? 'Glamhouse service'))
            ->line('Client note: '.($this->booking->reschedule_note ?: 'No note provided.'))
            ->action('Open Booking', route('admin.bookings.show', $this->booking));
    }
}
