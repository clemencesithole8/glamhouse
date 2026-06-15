<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY status ENUM('pending','reviewed','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'reminder_sent_at')) {
                $table->timestamp('reminder_sent_at')->nullable()->after('admin_notes');
            }

            if (! Schema::hasColumn('bookings', 'reschedule_requested_at')) {
                $table->timestamp('reschedule_requested_at')->nullable()->after('reminder_sent_at');
            }

            if (! Schema::hasColumn('bookings', 'reschedule_note')) {
                $table->text('reschedule_note')->nullable()->after('reschedule_requested_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'reschedule_note')) {
                $table->dropColumn('reschedule_note');
            }

            if (Schema::hasColumn('bookings', 'reschedule_requested_at')) {
                $table->dropColumn('reschedule_requested_at');
            }

            if (Schema::hasColumn('bookings', 'reminder_sent_at')) {
                $table->dropColumn('reminder_sent_at');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }
};
