@extends('layouts.public')
@section('title', 'Book - Glamhouse')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10">
    <h1 class="font-serif text-3xl">Book Your Appointment</h1>
    <p class="mt-2 text-black/70">Bookings are confirmed after review and payment of the required deposit.</p>

    <form class="mt-8 space-y-6" method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="rounded-2xl border border-black/10 p-6">
            <h2 class="font-semibold">Client Details</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm">Full Name*</label>
                    <input name="full_name" value="{{ old('full_name') }}" class="mt-1 w-full rounded-xl border-black/20" required>
                    @error('full_name')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="text-sm">Phone (WhatsApp preferred)*</label>
                    <input name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-xl border-black/20" required>
                    @error('phone')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="text-sm">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-xl border-black/20">
                    @error('email')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="text-sm">Location / Area*</label>
                    <input name="location_area" value="{{ old('location_area') }}" class="mt-1 w-full rounded-xl border-black/20" required>
                    @error('location_area')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-black/10 p-6">
            <h2 class="font-semibold">Booking Details</h2>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm">Date of Appointment*</label>
                    <input id="appointment_date" type="date" name="appointment_date" value="{{ old('appointment_date') }}"
                           class="mt-1 w-full rounded-xl border-black/20" required>
                    @error('appointment_date')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="text-sm">Service Needed*</label>
                    <select name="service_id" class="mt-1 w-full rounded-xl border-black/20" required>
                        <option value="">Select…</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                {{ $service->name }}
                                @if($service->price) - ${{ number_format($service->price, 0) }} @endif
                                @if($service->is_consultation_based) (Consultation) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="text-sm">Time Slot</label>
                    <select id="time_slot_id" name="time_slot_id" class="mt-1 w-full rounded-xl border-black/20">
                        <option value="">Select date first…</option>
                    </select>
                    @error('time_slot_id')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="text-sm">Preferred Time (optional)</label>
                    <input name="preferred_time_text" value="{{ old('preferred_time_text') }}"
                           class="mt-1 w-full rounded-xl border-black/20" placeholder="e.g., 10:00 AM">
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-black/10 p-6">
            <h2 class="font-semibold">Event Details</h2>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm">Type of Event</label>
                    <input name="event_type" value="{{ old('event_type') }}" class="mt-1 w-full rounded-xl border-black/20">
                </div>

                <div>
                    <label class="text-sm">Outcall Service?</label>
                    <select name="is_outcall" class="mt-1 w-full rounded-xl border-black/20" required>
                        <option value="0" @selected(old('is_outcall') === "0")>No (I will come to the studio)</option>
                        <option value="1" @selected(old('is_outcall') === "1")>Yes</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm">If yes, provide address/location</label>
                    <textarea name="outcall_address" class="mt-1 w-full rounded-xl border-black/20" rows="3">{{ old('outcall_address') }}</textarea>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-black/10 p-6">
            <h2 class="font-semibold">Makeup Info</h2>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm">Skin Type</label>
                    <select name="skin_type" class="mt-1 w-full rounded-xl border-black/20">
                        <option value="">Select…</option>
                        @foreach(['Oily','Dry','Combination','Not sure'] as $t)
                            <option value="{{ $t }}" @selected(old('skin_type') == $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm">Have you done professional makeup before?</label>
                    <select name="has_done_pro_makeup" class="mt-1 w-full rounded-xl border-black/20" required>
                        <option value="1" @selected(old('has_done_pro_makeup') === "1")>Yes</option>
                        <option value="0" @selected(old('has_done_pro_makeup') === "0")>No</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm">Allergies / Skin conditions</label>
                    <textarea name="allergies_notes" class="mt-1 w-full rounded-xl border-black/20" rows="3">{{ old('allergies_notes') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm">Reference look (optional)</label>
                    <input type="file" name="reference_look" class="mt-1 w-full">
                    @error('reference_look')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-black/10 p-6">
            <h2 class="font-semibold">Payment & Terms</h2>
            <div class="mt-4 space-y-3 text-sm">
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="deposit_ack" value="1" class="mt-1">
                    <span>I understand that a deposit is required to secure my booking.</span>
                </label>
                @error('deposit_ack')<div class="text-sm text-red-600">{{ $message }}</div>@enderror

                <label class="flex items-start gap-2">
                    <input type="checkbox" name="lateness_ack" value="1" class="mt-1">
                    <span>I understand that late arrival may affect my booking time.</span>
                </label>
                @error('lateness_ack')<div class="text-sm text-red-600">{{ $message }}</div>@enderror

                <label class="flex items-start gap-2">
                    <input type="checkbox" name="info_confirmed" value="1" class="mt-1">
                    <span>I confirm that the information I provided is correct.</span>
                </label>
                @error('info_confirmed')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
        </div>

        <button class="w-full rounded-full bg-black px-5 py-3 text-white hover:bg-rosegold-600 transition">
            Submit Booking
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const dateInput = document.getElementById('appointment_date');
  const slotSelect = document.getElementById('time_slot_id');

  async function loadSlots(date) {
    slotSelect.innerHTML = '<option value="">Loading…</option>';
    const res = await fetch(`{{ route('availability.index') }}?date=${encodeURIComponent(date)}`);
    const data = await res.json();
    slotSelect.innerHTML = '<option value="">Select…</option>';
    data.slots.forEach(s => {
      const opt = document.createElement('option');
      opt.value = s.id;
      opt.textContent = `${String(s.start_time).slice(0,5)} - ${String(s.end_time).slice(0,5)} ${s.is_available ? '' : '(Booked)'}`;
      opt.disabled = !s.is_available;
      slotSelect.appendChild(opt);
    });
  }

  if (dateInput.value) loadSlots(dateInput.value);
  dateInput.addEventListener('change', e => {
    if (e.target.value) loadSlots(e.target.value);
  });
});
</script>
@endsection