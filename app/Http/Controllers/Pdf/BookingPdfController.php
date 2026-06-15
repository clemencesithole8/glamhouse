<?php
namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BookingPdfController extends Controller
{
    public function show(Request $request, string $bookingId)
    {
        abort_unless(Schema::hasTable('bookings'), 404);

        $booking = Booking::query()->findOrFail($bookingId);

        abort_unless($this->canViewBooking($request, $booking), 403);

        $relations = [];

        if (Schema::hasTable('services')) {
            $relations[] = 'service';
        } else {
            $booking->setRelation('service', null);
        }

        if (Schema::hasTable('time_slots')) {
            $relations[] = 'timeSlot';
        } else {
            $booking->setRelation('timeSlot', null);
        }

        if (Schema::hasTable('payments')) {
            $relations[] = 'payments';
        } else {
            $booking->setRelation('payments', collect());
        }

        if ($relations !== []) {
            $booking->load($relations);
        }

        $pdf = Pdf::loadView('pdf.booking-summary', [
            'booking' => $booking,
        ])->setPaper('a4');

        return $pdf->download('Glamhouse_Booking_'.$booking->id.'.pdf');
    }

    private function canViewBooking(Request $request, Booking $booking): bool
    {
        if ($request->hasValidSignature()) {
            return true;
        }

        $user = $request->user();

        if (! $user) {
            return false;
        }

        return $user->is_admin || strcasecmp((string) $user->email, (string) $booking->email) === 0;
    }
}
