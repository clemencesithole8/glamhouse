<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PaymentReceiptPdfController extends Controller
{
    public function show(Request $request, string $paymentId)
    {
        abort_unless(Schema::hasTable('payments') && Schema::hasTable('bookings'), 404);

        $relations = ['booking.payments'];

        if (Schema::hasTable('services')) {
            $relations[] = 'booking.service';
        }

        if (Schema::hasTable('time_slots')) {
            $relations[] = 'booking.timeSlot';
        }

        $payment = Payment::query()
            ->with($relations)
            ->findOrFail($paymentId);

        $booking = $payment->booking;

        abort_unless($booking && $this->canViewPayment($request, $payment), 403);

        if (! Schema::hasTable('services')) {
            $booking->setRelation('service', null);
        }

        if (! Schema::hasTable('time_slots')) {
            $booking->setRelation('timeSlot', null);
        }

        $pdf = Pdf::loadView('pdf.payment-receipt', [
            'payment' => $payment,
            'booking' => $booking,
        ])->setPaper('a4');

        return $pdf->download('Glamhouse_Receipt_'.$payment->id.'.pdf');
    }

    private function canViewPayment(Request $request, Payment $payment): bool
    {
        if ($request->hasValidSignature()) {
            return true;
        }

        $user = $request->user();

        if (! $user || ! $payment->booking) {
            return false;
        }

        return $user->is_admin || strcasecmp((string) $user->email, (string) $payment->booking->email) === 0;
    }
}
