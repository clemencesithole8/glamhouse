<?php
namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingPdfController extends Controller
{
    public function show(Booking $booking)
    {
        $pdf = Pdf::loadView('pdf.booking-summary', [
            'booking' => $booking->load(['service','timeSlot','payments']),
        ])->setPaper('a4');

        return $pdf->download('Glamhouse_Booking_'.$booking->id.'.pdf');
    }
}
