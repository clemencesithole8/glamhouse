<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->text('content');
            $table->unsignedTinyInteger('rating')->nullable(); // 1-5 optional
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('testimonials');
    }
};
