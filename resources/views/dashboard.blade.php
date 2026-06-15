<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#8f3a4e]">Customer account</p>
                <h2 class="mt-1 font-display text-3xl font-semibold text-[#241915]">
                    My bookings
                </h2>
            </div>
            <a href="{{ route('booking.create') }}" class="btn-primary inline-flex w-fit items-center text-sm">
                Book a session
            </a>
        </div>
    </x-slot>

    <div class="bg-[#fffaf6] py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
                    <div class="font-semibold">Please review the form errors.</div>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="grid gap-4 md:grid-cols-3">
                <article class="rounded-lg border border-black/10 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-black/50">Upcoming</p>
                    <p class="mt-3 text-4xl font-semibold text-[#241915]">{{ $upcomingBookings->count() }}</p>
                    <p class="mt-2 text-sm text-black/60">Sessions scheduled from today forward.</p>
                </article>

                <article class="rounded-lg border border-black/10 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-black/50">Pending</p>
                    <p class="mt-3 text-4xl font-semibold text-[#8f3a4e]">{{ $pendingCount }}</p>
                    <p class="mt-2 text-sm text-black/60">Requests awaiting studio confirmation.</p>
                </article>

                <article class="rounded-lg border border-black/10 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-black/50">Completed</p>
                    <p class="mt-3 text-4xl font-semibold text-[#241915]">{{ $completedCount }}</p>
                    <p class="mt-2 text-sm text-black/60">Finished Glamhouse appointments.</p>
                </article>
            </section>

            <section class="grid gap-6 lg:grid-cols-[0.9fr_1.6fr]">
                <aside class="space-y-4 rounded-lg border border-black/10 bg-white p-6 shadow-sm">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-black/50">Welcome</p>
                        <h3 class="mt-2 font-display text-3xl font-semibold text-[#241915]">{{ $user->name }}</h3>
                        <p class="mt-2 text-sm text-black/60">{{ $user->email }}</p>
                    </div>

                    @if ($nextBooking)
                        <div class="rounded-lg bg-[#fbf7f3] p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#8f3a4e]">Next appointment</p>
                            <p class="mt-2 text-lg font-semibold text-[#241915]">
                                {{ $nextBooking->appointment_date->format('D, M j, Y') }}
                            </p>
                            <p class="mt-1 text-sm text-black/65">
                                {{ $nextBooking->service?->name ?? 'Glamhouse service' }}
                                @if ($nextBooking->timeSlot)
                                    at {{ substr($nextBooking->timeSlot->start_time, 0, 5) }}
                                @elseif ($nextBooking->preferred_time_text)
                                    at {{ $nextBooking->preferred_time_text }}
                                @endif
                            </p>
                            <a href="{{ route('booking.pdf', $nextBooking) }}" class="mt-4 inline-flex text-sm font-semibold text-[#8f3a4e] hover:text-[#241915]">
                                Download summary
                            </a>
                        </div>
                    @else
                        <div class="rounded-lg bg-[#fbf7f3] p-4">
                            <p class="text-sm font-semibold text-[#241915]">No upcoming booking yet.</p>
                            <p class="mt-2 text-sm text-black/65">Choose a service and submit your preferred date to get started.</p>
                        </div>
                    @endif

                    <div class="grid gap-3 text-sm">
                        <a href="{{ route('booking.create') }}" class="rounded-lg border border-black/10 px-4 py-3 font-semibold text-[#241915] transition hover:border-[#b9536a]/50 hover:bg-[#fbe6e4]/40">
                            Make another booking
                        </a>
                        <a href="{{ route('profile.edit') }}" class="rounded-lg border border-black/10 px-4 py-3 font-semibold text-[#241915] transition hover:border-[#b9536a]/50 hover:bg-[#fbe6e4]/40">
                            Update account details
                        </a>
                    </div>
                </aside>

                <section class="rounded-lg border border-black/10 bg-white shadow-sm">
                    <div class="border-b border-black/10 p-6">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-black/50">Booking hub</p>
                                <h3 class="mt-2 font-display text-3xl font-semibold text-[#241915]">Your Glamhouse visits</h3>
                            </div>
                            <p class="text-sm text-black/60">{{ $confirmedCount }} confirmed</p>
                        </div>
                    </div>

                    @if ($bookings->isEmpty())
                        <div class="p-6">
                            <div class="rounded-lg bg-[#fbf7f3] p-6 text-center">
                                <h4 class="font-display text-2xl font-semibold text-[#241915]">Your booking list is ready.</h4>
                                <p class="mx-auto mt-2 max-w-xl text-sm text-black/65">
                                    Once you submit a booking with this email address, it will appear here with its status, appointment details, and PDF summary.
                                </p>
                                <a href="{{ route('booking.create') }}" class="btn-primary mt-5 inline-flex text-sm">Start a booking</a>
                            </div>
                        </div>
                    @else
                        <div class="divide-y divide-black/10">
                            @foreach ($bookings as $booking)
                                @php
                                    $paid = $booking->payments->sum('amount');
                                    $balance = is_null($booking->total_amount) ? null : max(0, (int) $booking->total_amount - $paid);
                                    $statusClasses = [
                                        'pending' => 'bg-[#f4e6c9] text-[#6f4b16]',
                                        'reviewed' => 'bg-violet-100 text-violet-800',
                                        'confirmed' => 'bg-[#dff3e7] text-[#23643c]',
                                        'completed' => 'bg-[#e8eef8] text-[#284766]',
                                        'cancelled' => 'bg-[#f6dddd] text-[#7a2c2c]',
                                    ][$booking->status] ?? 'bg-gray-100 text-gray-700';
                                    $timeline = [
                                        [
                                            'label' => 'Submitted',
                                            'state' => 'complete',
                                        ],
                                        [
                                            'label' => 'Reviewed',
                                            'state' => in_array($booking->status, ['reviewed', 'confirmed', 'completed'], true) ? 'complete' : ($booking->status === 'pending' ? 'current' : 'upcoming'),
                                        ],
                                        [
                                            'label' => 'Confirmed',
                                            'state' => in_array($booking->status, ['confirmed', 'completed'], true) ? 'complete' : ($booking->status === 'reviewed' ? 'current' : 'upcoming'),
                                        ],
                                        [
                                            'label' => 'Paid',
                                            'state' => $booking->payment_status === 'paid' ? 'complete' : (in_array($booking->status, ['confirmed', 'completed'], true) ? 'current' : 'upcoming'),
                                        ],
                                        [
                                            'label' => 'Completed',
                                            'state' => $booking->status === 'completed' ? 'complete' : ($booking->payment_status === 'paid' ? 'current' : 'upcoming'),
                                        ],
                                    ];
                                @endphp
                                <article class="grid gap-4 p-6 lg:grid-cols-[1fr_auto] lg:items-center">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h4 class="text-lg font-semibold text-[#241915]">
                                                {{ $booking->service?->name ?? 'Glamhouse service' }}
                                            </h4>
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $booking->payment_status_classes }}">
                                                {{ $booking->payment_status_label }}
                                            </span>
                                        </div>

                                        <dl class="mt-4 grid gap-3 text-sm text-black/65 sm:grid-cols-2">
                                            <div>
                                                <dt class="font-semibold text-[#241915]">Date</dt>
                                                <dd>{{ $booking->appointment_date->format('D, M j, Y') }}</dd>
                                            </div>
                                            <div>
                                                <dt class="font-semibold text-[#241915]">Time</dt>
                                                <dd>
                                                    @if ($booking->timeSlot)
                                                        {{ substr($booking->timeSlot->start_time, 0, 5) }} - {{ substr($booking->timeSlot->end_time, 0, 5) }}
                                                    @else
                                                        {{ $booking->preferred_time_text ?? 'To be confirmed' }}
                                                    @endif
                                                </dd>
                                            </div>
                                            <div>
                                                <dt class="font-semibold text-[#241915]">Location</dt>
                                                <dd>{{ $booking->is_outcall ? ($booking->outcall_address ?: $booking->location_area) : 'Glamhouse studio' }}</dd>
                                            </div>
                                            <div>
                                                <dt class="font-semibold text-[#241915]">Balance</dt>
                                                <dd>
                                                    @if (is_null($balance))
                                                        To be confirmed
                                                    @else
                                                        ${{ number_format($balance, 0) }}
                                                    @endif
                                                </dd>
                                            </div>
                                        </dl>

                                        <div class="mt-5 grid gap-2 sm:grid-cols-5">
                                            @foreach ($timeline as $step)
                                                @php
                                                    $stepClasses = match ($step['state']) {
                                                        'complete' => 'border-[#23643c]/25 bg-[#ecf8f0] text-[#23643c]',
                                                        'current' => 'border-[#b9536a]/30 bg-[#fff2ef] text-[#8f3a4e]',
                                                        default => 'border-black/10 bg-white text-black/45',
                                                    };
                                                @endphp
                                                <div class="rounded-lg border px-3 py-2 text-xs font-semibold {{ $stepClasses }}">
                                                    {{ $step['label'] }}
                                                </div>
                                            @endforeach
                                        </div>

                                        @if ($booking->reschedule_requested_at)
                                            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950">
                                                Reschedule requested on {{ $booking->reschedule_requested_at->format('M j, Y') }}. The studio will follow up.
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap gap-2 lg:justify-end">
                                        <a href="{{ route('booking.pdf', $booking) }}" class="btn-outline inline-flex text-sm">
                                            PDF summary
                                        </a>
                                        <a href="{{ route('booking.create', ['copy_from' => $booking->id]) }}" class="btn-outline inline-flex text-sm">
                                            Book Again
                                        </a>
                                        @if (! in_array($booking->status, ['completed', 'cancelled'], true))
                                            <details class="w-full lg:w-64">
                                                <summary class="btn-primary inline-flex w-full cursor-pointer justify-center text-sm">
                                                    Request Reschedule
                                                </summary>
                                                <form method="POST" action="{{ route('dashboard.bookings.reschedule', $booking) }}" class="mt-3 rounded-lg border border-black/10 bg-[#fbf7f3] p-3">
                                                    @csrf
                                                    <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/55">Preferred change</label>
                                                    <textarea name="reschedule_note" rows="3" class="mt-1 w-full rounded-lg border-black/15 text-sm focus:border-[#b9536a] focus:ring-[#b9536a]" placeholder="Share your preferred new date/time."></textarea>
                                                    <button class="mt-2 w-full rounded-lg bg-[#8f3a4e] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#241915]">
                                                        Send Request
                                                    </button>
                                                </form>
                                            </details>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>
            </section>
        </div>
    </div>
</x-app-layout>
