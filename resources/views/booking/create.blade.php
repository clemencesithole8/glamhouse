@extends('layouts.public')
@section('title', 'Book - Glamhouse')

@section('content')
@php
    $business = config('seo.business', []);
    $publicPhone = trim((string) ($business['phone'] ?? '')) ?: '+263784721479';
    $whatsappNumber = preg_replace('/\D+/', '', $publicPhone) ?: '263784721479';
    $whatsappUrl = 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode('Hi Glamhouse, I found you on Google and would like to book a makeup appointment in Harare.');
@endphp

<div class="mx-auto max-w-7xl px-4 pb-20 pt-10 sm:px-6 lg:px-8">
    <section class="grid gap-8 lg:grid-cols-[1fr_320px] xl:grid-cols-[1fr_360px]">
        <div class="space-y-6">
            <article class="relative overflow-hidden rounded-[2rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fff9f8_0%,#fff2ef_46%,#fffefd_100%)] p-7 sm:p-9">
                <div class="absolute -right-14 top-2 h-48 w-48 rounded-full bg-rosegold-100/70 blur-2xl"></div>
                <p class="relative inline-flex rounded-full border border-rosegold-200 bg-white/80 px-3 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.2em] text-rosegold-800">
                    Secure Booking Form
                </p>
                <h1 class="font-display relative mt-5 text-5xl leading-[0.95] text-[#2a1c19] sm:text-6xl">Book Your Appointment</h1>
                <p class="relative mt-4 max-w-3xl text-base leading-relaxed text-black/70">
                    Your form is reviewed by our team before confirmation. We will follow up with next steps and payment details after checking availability.
                </p>

                <div class="relative mt-6 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-black/10 bg-white/85 px-4 py-3 text-sm">
                        <div class="text-xs uppercase tracking-[0.14em] text-black/55">Location</div>
                        <div class="mt-1 font-semibold">Harare studio + outcall</div>
                    </div>
                    <div class="rounded-2xl border border-black/10 bg-white/85 px-4 py-3 text-sm">
                        <div class="text-xs uppercase tracking-[0.14em] text-black/55">Step 1</div>
                        <div class="mt-1 font-semibold">Submit Details</div>
                    </div>
                    <div class="rounded-2xl border border-black/10 bg-white/85 px-4 py-3 text-sm">
                        <div class="text-xs uppercase tracking-[0.14em] text-black/55">Step 2</div>
                        <div class="mt-1 font-semibold">Confirmation Follow-up</div>
                    </div>
                </div>
            </article>

            <form class="space-y-6" method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
                        <div class="font-semibold">Please fix the highlighted fields and submit again.</div>
                    </div>
                @endif

                <section class="glass-card rounded-3xl p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="font-display text-3xl text-[#2a1c19]">Client Details</h2>
                        <span class="text-xs uppercase tracking-[0.14em] text-black/50">Required fields marked *</span>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Full Name *</label>
                            <input name="full_name" value="{{ old('full_name') }}" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" required>
                            @error('full_name')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Phone (WhatsApp preferred) *</label>
                            <input name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" required>
                            @error('phone')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                            @error('email')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Location / Area *</label>
                            <input name="location_area" value="{{ old('location_area') }}" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="e.g., Avondale, Borrowdale, CBD" required>
                            @error('location_area')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

                <section class="glass-card rounded-3xl p-6 sm:p-7">
                    <h2 class="font-display text-3xl text-[#2a1c19]">Booking Details</h2>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Date of Appointment *</label>
                            <input id="appointment_date" type="date" name="appointment_date" value="{{ old('appointment_date') }}" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" required>
                            @error('appointment_date')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Service Needed *</label>
                            <select name="service_id" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" required>
                                <option value="">Select service...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                        {{ $service->name }}
                                        @if($service->price) - ${{ number_format($service->price, 0) }} @endif
                                        @if($service->is_consultation_based) (Consultation) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Time Slot</label>
                            <select id="time_slot_id" name="time_slot_id" data-selected="{{ old('time_slot_id') }}" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                                <option value="">Select date first...</option>
                            </select>
                            <div id="slot_status" class="mt-1 text-xs text-black/55">Pick a date to load available slots.</div>
                            @error('time_slot_id')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Preferred Time (Optional)</label>
                            <input name="preferred_time_text" value="{{ old('preferred_time_text') }}" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="e.g., 10:00 AM">
                        </div>
                    </div>
                </section>

                <section class="glass-card rounded-3xl p-6 sm:p-7">
                    <h2 class="font-display text-3xl text-[#2a1c19]">Event Details</h2>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Type of Event</label>
                            <input name="event_type" value="{{ old('event_type') }}" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Wedding, photoshoot, corporate event...">
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Outcall Service? *</label>
                            <select id="is_outcall" name="is_outcall" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" required>
                                <option value="0" @selected(old('is_outcall', '0') === '0')>No (I will come to the studio)</option>
                                <option value="1" @selected(old('is_outcall') === '1')>Yes</option>
                            </select>
                        </div>

                        <div id="outcall_address_wrap" class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">If yes, provide address/location</label>
                            <textarea name="outcall_address" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" rows="3">{{ old('outcall_address') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="glass-card rounded-3xl p-6 sm:p-7">
                    <h2 class="font-display text-3xl text-[#2a1c19]">Makeup Profile</h2>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Skin Type</label>
                            <select name="skin_type" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                                <option value="">Select...</option>
                                @foreach(['Oily', 'Dry', 'Combination', 'Not sure'] as $t)
                                    <option value="{{ $t }}" @selected(old('skin_type') == $t)>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Have you done professional makeup before? *</label>
                            <select name="has_done_pro_makeup" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" required>
                                <option value="1" @selected(old('has_done_pro_makeup', '1') === '1')>Yes</option>
                                <option value="0" @selected(old('has_done_pro_makeup') === '0')>No</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Allergies / Skin Conditions</label>
                            <textarea name="allergies_notes" class="mt-1 w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" rows="3">{{ old('allergies_notes') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-[0.12em] text-black/60">Reference Look (Optional)</label>
                            <input type="file" name="reference_look" class="mt-1 w-full rounded-xl border border-black/15 px-3 py-2 text-sm">
                            <p class="mt-1 text-xs text-black/55">Accepted formats: JPG, PNG, WEBP (up to 5MB).</p>
                            @error('reference_look')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

                <section class="glass-card rounded-3xl p-6 sm:p-7">
                    <h2 class="font-display text-3xl text-[#2a1c19]">Payment & Terms</h2>
                    <div class="mt-5 space-y-3 text-sm">
                        <label class="flex items-start gap-3 rounded-xl border border-black/10 bg-white/80 px-4 py-3">
                            <input type="checkbox" name="deposit_ack" value="1" class="mt-1 rounded border-black/30 text-rosegold-600 focus:ring-rosegold-500" @checked(old('deposit_ack'))>
                            <span>I understand that a deposit is required to secure my booking.</span>
                        </label>
                        @error('deposit_ack')<div class="text-sm text-red-600">{{ $message }}</div>@enderror

                        <label class="flex items-start gap-3 rounded-xl border border-black/10 bg-white/80 px-4 py-3">
                            <input type="checkbox" name="lateness_ack" value="1" class="mt-1 rounded border-black/30 text-rosegold-600 focus:ring-rosegold-500" @checked(old('lateness_ack'))>
                            <span>I understand that late arrival may affect my booking time.</span>
                        </label>
                        @error('lateness_ack')<div class="text-sm text-red-600">{{ $message }}</div>@enderror

                        <label class="flex items-start gap-3 rounded-xl border border-black/10 bg-white/80 px-4 py-3">
                            <input type="checkbox" name="info_confirmed" value="1" class="mt-1 rounded border-black/30 text-rosegold-600 focus:ring-rosegold-500" @checked(old('info_confirmed'))>
                            <span>I confirm that the information I provided is correct.</span>
                        </label>
                        @error('info_confirmed')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                </section>

                <button class="btn-primary w-full justify-center rounded-full py-3 text-sm sm:text-base">
                    Submit Booking
                </button>
            </form>
        </div>

        <aside class="h-max space-y-4 lg:sticky lg:top-24">
            <article class="glass-card rounded-3xl p-5">
                <h3 class="font-display text-2xl text-[#2a1c19]">Before You Submit</h3>
                <ul class="mt-3 space-y-2 text-sm text-black/70">
                    <li>- Choose your date first, then pick an available slot.</li>
                    <li>- Outcall requests should include a clear address.</li>
                    <li>- Add reference photos for best style matching.</li>
                    <li>- Ensure all policy checkboxes are selected.</li>
                </ul>
            </article>

            <article class="rounded-3xl border border-black/10 bg-white p-5">
                <div class="text-xs uppercase tracking-[0.14em] text-black/55">Need Help Fast?</div>
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex w-full items-center justify-center rounded-full bg-green-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
                    Chat on WhatsApp
                </a>
                <a href="mailto:esther2026@gmail.com" class="mt-2 inline-flex w-full items-center justify-center rounded-full border border-black/15 px-4 py-2.5 text-sm font-semibold transition hover:bg-black hover:text-white">
                    Send Email
                </a>
            </article>
        </aside>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('appointment_date');
    const slotSelect = document.getElementById('time_slot_id');
    const slotStatus = document.getElementById('slot_status');
    const selectedSlot = slotSelect.dataset.selected;

    const outcallSelect = document.getElementById('is_outcall');
    const outcallWrap = document.getElementById('outcall_address_wrap');

    const toggleOutcallAddress = () => {
        const show = outcallSelect && outcallSelect.value === '1';
        if (!outcallWrap) return;
        outcallWrap.classList.toggle('opacity-60', !show);
    };

    const loadSlots = async (date) => {
        if (!date) {
            slotSelect.innerHTML = '<option value="">Select date first...</option>';
            slotStatus.textContent = 'Pick a date to load available slots.';
            return;
        }

        slotSelect.innerHTML = '<option value="">Loading slots...</option>';
        slotStatus.textContent = 'Checking availability...';

        try {
            const res = await fetch(`{{ route('availability.index') }}?date=${encodeURIComponent(date)}`);
            if (!res.ok) {
                throw new Error('Failed to load slots');
            }

            const data = await res.json();
            const slots = Array.isArray(data.slots) ? data.slots : [];
            slotSelect.innerHTML = '<option value="">Select slot...</option>';

            let availableCount = 0;

            slots.forEach((slot) => {
                const opt = document.createElement('option');
                opt.value = slot.id;
                opt.textContent = `${String(slot.start_time).slice(0, 5)} - ${String(slot.end_time).slice(0, 5)}${slot.is_available ? '' : ' (Booked)'}`;
                opt.disabled = !slot.is_available;

                if (slot.is_available) {
                    availableCount += 1;
                }

                if (selectedSlot && String(selectedSlot) === String(slot.id) && slot.is_available) {
                    opt.selected = true;
                }

                slotSelect.appendChild(opt);
            });

            slotStatus.textContent = availableCount > 0
                ? `${availableCount} slot(s) available for this date.`
                : 'No open slots found for this date. Try another date.';
        } catch (error) {
            slotSelect.innerHTML = '<option value="">Unable to load slots</option>';
            slotStatus.textContent = 'We could not load availability right now. Please retry.';
        }
    };

    if (dateInput && dateInput.value) {
        loadSlots(dateInput.value);
    }

    if (dateInput) {
        dateInput.addEventListener('change', (e) => {
            loadSlots(e.target.value);
        });
    }

    if (outcallSelect) {
        outcallSelect.addEventListener('change', toggleOutcallAddress);
        toggleOutcallAddress();
    }
});
</script>
@endsection
