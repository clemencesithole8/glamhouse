<?php

namespace App\Notifications;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class PaymentRecordedClientNotification extends Notification
{
    public function __construct(public Payment $payment) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $relations = ['booking.payments'];

        if (Schema::hasTable('services')) {
            $relations[] = 'booking.service';
        }

        if (Schema::hasTable('time_slots')) {
            $relations[] = 'booking.timeSlot';
        }

        $payment = $this->payment->loadMissing($relations);
        $booking = $payment->booking;

        $pdf = Pdf::loadView('pdf.payment-receipt', [
            'payment' => $payment,
            'booking' => $booking,
        ])->setPaper('a4')->output();

        return (new MailMessage)
            ->subject("Glamhouse Payment Receipt (#{$payment->id})")
            ->greeting("Hi {$booking->full_name},")
            ->line('A payment has been recorded for your Glamhouse booking.')
            ->line('Amount received: $'.number_format($payment->amount, 0))
            ->line('Payment status: '.$booking->payment_status_label)
            ->action('Download Receipt PDF', URL::temporarySignedRoute('payment.receipt', now()->addDays(14), ['paymentId' => $payment->id]))
            ->line('Thank you. Please keep this receipt for your records.')
            ->attachData($pdf, 'Glamhouse_Receipt_'.$payment->id.'.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
