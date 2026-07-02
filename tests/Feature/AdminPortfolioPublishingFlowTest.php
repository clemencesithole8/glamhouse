<?php

namespace Tests\Feature;

use App\Models\PortfolioItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPortfolioPublishingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_replacing_main_image_keeps_existing_before_and_after_images(): void
    {
        $this->skipIfImagePipelineIsUnavailable();

        Storage::fake('public');

        $item = $this->portfolioItem([
            'image_path' => 'portfolio/old-main.jpg',
            'webp_path' => 'portfolio/old-main.webp',
            'thumbnail_path' => 'portfolio/old-main-thumb.webp',
            'before_image_path' => 'portfolio/before.webp',
            'after_image_path' => 'portfolio/after.webp',
        ]);

        foreach ($item->storagePaths() as $path) {
            Storage::disk('public')->put($path, 'existing image');
        }

        $this->actingAs($this->adminUser())
            ->put(route('admin.portfolio-items.update', $item), [
                ...$this->validPortfolioPayload(),
                'image' => UploadedFile::fake()->image('fresh-look.jpg', 720, 960),
            ])
            ->assertRedirect();

        Storage::disk('public')->assertMissing('portfolio/old-main.jpg');
        Storage::disk('public')->assertMissing('portfolio/old-main.webp');
        Storage::disk('public')->assertMissing('portfolio/old-main-thumb.webp');
        Storage::disk('public')->assertExists('portfolio/before.webp');
        Storage::disk('public')->assertExists('portfolio/after.webp');
    }

    public function test_webp_before_image_upload_remains_available_after_variant_cleanup(): void
    {
        $this->skipIfImagePipelineIsUnavailable();

        Storage::fake('public');

        $item = $this->portfolioItem();

        foreach ($item->storagePaths() as $path) {
            Storage::disk('public')->put($path, 'existing image');
        }

        $this->actingAs($this->adminUser())
            ->put(route('admin.portfolio-items.update', $item), [
                ...$this->validPortfolioPayload(),
                'before_image' => $this->webpUpload('before-look.webp'),
            ])
            ->assertRedirect();

        $item->refresh();

        $this->assertNotNull($item->before_image_path);
        Storage::disk('public')->assertExists($item->before_image_path);
    }

    public function test_portfolio_create_screen_is_ready_for_fast_photo_publishing(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('admin.portfolio-items.create'));

        $response->assertOk();
        $response->assertSee('Publish Portfolio Item');
        $response->assertSee('data-image-preview="#portfolio-main-preview"', false);
        $response->assertSee('New items publish immediately by default.');
    }

    private function validPortfolioPayload(): array
    {
        return [
            'title' => 'Fresh Google-ready look',
            'alt_text' => 'Soft glam makeup look with luminous skin',
            'category' => 'Soft glam',
            'is_featured' => '0',
            'is_active' => '1',
            'sort_order' => '0',
            'published_at' => now()->subMinute()->format('Y-m-d H:i:s'),
        ];
    }

    private function portfolioItem(array $overrides = []): PortfolioItem
    {
        return PortfolioItem::create([
            'title' => 'Existing look',
            'alt_text' => 'Existing look',
            'category' => 'Soft glam',
            'image_path' => 'portfolio/main.jpg',
            'webp_path' => 'portfolio/main.webp',
            'thumbnail_path' => 'portfolio/main-thumb.webp',
            'width' => 720,
            'height' => 960,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
            'published_at' => now()->subDay(),
            ...$overrides,
        ]);
    }

    private function adminUser(): User
    {
        return User::factory()->create([
            'is_admin' => true,
        ]);
    }

    private function webpUpload(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'glamhouse-webp-').'.webp';
        $image = imagecreatetruecolor(320, 320);

        imagefilledrectangle($image, 0, 0, 320, 320, imagecolorallocate($image, 235, 214, 204));
        imagewebp($image, $path);
        imagedestroy($image);

        return new UploadedFile($path, $name, 'image/webp', null, true);
    }

    private function skipIfImagePipelineIsUnavailable(): void
    {
        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagewebp')) {
            $this->markTestSkipped('The GD image pipeline is not available in this PHP runtime.');
        }
    }
}
