<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Summary</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .h1 { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
        .muted { color: #555; }
        .box { border: 1px solid #ddd; padding: 12px; border-radius: 6px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 0; vertical-align: top; }
        .label { width: 35%; color: #444; }
    </style>
</head>
<body>
    <div class="h1">Esther's Secrets - Glamhouse | Booking Summary</div>
    <div class="muted">Booking ID: #{{ $booking->id }} | Status: {{ strtoupper($booking->status) }}</div>

    <div class="box">
        <table>
            <tr><td class="label">Client</td><td>{{ $booking->full_name }}</td></tr>
            <tr><td class="label">Phone</td><td>{{ $booking->phone }}</td></tr>
            <tr><td class="label">Email</td><td>{{ $booking->email ?? '-' }}</td></tr>
            <tr><td class="label">Location / Area</td><td>{{ $booking->location_area }}</td></tr>
        </table>
    </div>

    <div class="box">
        <table>
            <tr><td class="label">Service</td><td>{{ $booking->service?->name ?? 'Unavailable' }}</td></tr>
            <tr><td class="label">Date</td><td>{{ $booking->appointment_date->format('D, d M Y') }}</td></tr>
            <tr><td class="label">Time</td>
                <td>
                    @if($booking->timeSlot)
                        {{ substr($booking->timeSlot->start_time, 0, 5) }} - {{ substr($booking->timeSlot->end_time, 0, 5) }}
                    @else
                        {{ $booking->preferred_time_text ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr><td class="label">Outcall</td><td>{{ $booking->is_outcall ? 'Yes' : 'No (Studio)' }}</td></tr>
            <tr><td class="label">Outcall Address</td><td>{{ $booking->outcall_address ?? '-' }}</td></tr>
            <tr><td class="label">Event Type</td><td>{{ $booking->event_type ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="box">
        <div><strong>Notes</strong></div>
        <div class="muted">
            Deposit required to secure booking. Late arrival may shorten service time or lead to cancellation without refund.
            Retainers are non-refundable. Rescheduling at least 24 hours in advance is subject to availability.
        </div>
    </div>
</body>
</html>
