<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Client details
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('location_area');

            // Booking details
            $table->date('appointment_date');
            $table->foreignId('time_slot_id')->nullable()->constrained()->nullOnDelete();
            $table->string('preferred_time_text')->nullable(); // fallback if no slot system

            $table->foreignId('service_id')->constrained()->cascadeOnDelete();

            // Event details
            $table->string('event_type')->nullable();
            $table->boolean('is_outcall')->default(false);
            $table->text('outcall_address')->nullable();

            // Makeup info
            $table->enum('skin_type', ['Oily','Dry','Combination','Not sure'])->nullable();
            $table->text('allergies_notes')->nullable();
            $table->boolean('has_done_pro_makeup')->default(false);

            // Upload
            $table->string('reference_image_path')->nullable();

            // Compliance
            $table->boolean('deposit_ack')->default(false);
            $table->boolean('lateness_ack')->default(false);
            $table->boolean('info_confirmed')->default(false);

            // Status
            $table->enum('status', ['pending','confirmed','completed','cancelled'])->default('pending');

            // Money (optional now; useful for admin/revenue tracking)
            $table->unsignedInteger('deposit_amount')->nullable();
            $table->unsignedInteger('total_amount')->nullable();

            // Admin
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            // Prevent double booking for a slot/date.
            // NOTE: cancelling a booking should set time_slot_id = null to free the slot (handled in admin action).
            $table->unique(['appointment_date', 'time_slot_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('bookings');
    }
};
