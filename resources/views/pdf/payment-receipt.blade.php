<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .h1 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .muted { color: #555; }
        .box { border: 1px solid #ddd; padding: 12px; border-radius: 6px; margin: 10px 0; }
        .total { font-size: 18px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 0; vertical-align: top; }
        .label { width: 35%; color: #444; }
    </style>
</head>
<body>
    <div class="h1">Esther's Secrets - Glamhouse | Payment Receipt</div>
    <div class="muted">Receipt #{{ $payment->id }} | Booking #{{ $booking->id }}</div>

    <div class="box">
        <table>
            <tr><td class="label">Client</td><td>{{ $booking->full_name }}</td></tr>
            <tr><td class="label">Phone</td><td>{{ $booking->phone }}</td></tr>
            <tr><td class="label">Email</td><td>{{ $booking->email ?? '-' }}</td></tr>
            <tr><td class="label">Service</td><td>{{ $booking->service?->name ?? 'Glamhouse service' }}</td></tr>
            <tr><td class="label">Appointment</td><td>{{ $booking->appointment_date->format('D, d M Y') }}</td></tr>
        </table>
    </div>

    <div class="box">
        <table>
            <tr><td class="label">Payment Date</td><td>{{ optional($payment->paid_at)->format('D, d M Y H:i') ?? '-' }}</td></tr>
            <tr><td class="label">Payment Type</td><td>{{ ucfirst($payment->type) }}</td></tr>
            <tr><td class="label">Method</td><td>{{ $payment->method ?: 'Not set' }}</td></tr>
            <tr><td class="label">Reference</td><td>{{ $payment->reference ?: '-' }}</td></tr>
            <tr><td class="label">Amount Received</td><td class="total">${{ number_format($payment->amount, 0) }}</td></tr>
        </table>
    </div>

    <div class="box">
        <table>
            <tr><td class="label">Quoted Total</td><td>{{ $booking->total_amount !== null ? '$'.number_format($booking->total_amount, 0) : 'To be confirmed' }}</td></tr>
            <tr><td class="label">Total Paid</td><td>${{ number_format($booking->total_paid, 0) }}</td></tr>
            <tr><td class="label">Balance Due</td><td>{{ $booking->balance_due !== null ? '$'.number_format($booking->balance_due, 0) : 'To be confirmed' }}</td></tr>
            <tr><td class="label">Payment Status</td><td>{{ $booking->payment_status_label }}</td></tr>
        </table>
    </div>

    <div class="muted">
        Thank you for your payment. This receipt confirms the payment recorded by Glamhouse.
    </div>
</body>
</html>
