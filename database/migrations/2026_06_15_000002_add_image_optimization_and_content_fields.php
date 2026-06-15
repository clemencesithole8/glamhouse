<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_assets', function (Blueprint $table): void {
            $table->string('webp_path')->nullable()->after('path');
            $table->string('thumbnail_path')->nullable()->after('webp_path');
            $table->string('mime_type', 100)->nullable()->after('height');
            $table->unsignedBigInteger('size')->nullable()->after('mime_type');
            $table->string('original_name')->nullable()->after('size');
        });

        Schema::table('portfolio_items', function (Blueprint $table): void {
            $table->string('alt_text')->nullable()->after('title');
            $table->string('webp_path')->nullable()->after('image_path');
            $table->string('thumbnail_path')->nullable()->after('webp_path');
            $table->unsignedInteger('width')->nullable()->after('thumbnail_path');
            $table->unsignedInteger('height')->nullable()->after('width');
            $table->boolean('is_active')->default(true)->after('is_featured');
            $table->unsignedInteger('sort_order')->default(0)->after('is_active');
            $table->timestamp('published_at')->nullable()->after('sort_order');

            $table->index(['is_active', 'is_featured']);
            $table->index(['published_at']);
        });

        Schema::table('testimonials', function (Blueprint $table): void {
            $table->string('client_role')->nullable()->after('client_name');
            $table->string('source')->nullable()->after('rating');
            $table->boolean('is_active')->default(true)->after('is_featured');
            $table->unsignedInteger('sort_order')->default(0)->after('is_active');
            $table->timestamp('published_at')->nullable()->after('sort_order');

            $table->index(['is_active', 'is_featured']);
            $table->index(['published_at']);
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropIndex(['is_active', 'is_featured']);
            $table->dropIndex(['published_at']);
            $table->dropColumn([
                'client_role',
                'source',
                'is_active',
                'sort_order',
                'published_at',
            ]);
        });

        Schema::table('portfolio_items', function (Blueprint $table): void {
            $table->dropIndex(['is_active', 'is_featured']);
            $table->dropIndex(['published_at']);
            $table->dropColumn([
                'alt_text',
                'webp_path',
                'thumbnail_path',
                'width',
                'height',
                'is_active',
                'sort_order',
                'published_at',
            ]);
        });

        Schema::table('media_assets', function (Blueprint $table): void {
            $table->dropColumn([
                'webp_path',
                'thumbnail_path',
                'mime_type',
                'size',
                'original_name',
            ]);
        });
    }
};
