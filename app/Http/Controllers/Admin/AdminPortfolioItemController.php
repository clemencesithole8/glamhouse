<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PortfolioItem;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;

class AdminPortfolioItemController extends Controller
{
    public function index(Request $request)
    {
        $items = PortfolioItem::query()
            ->orderBy('sort_order')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $items->where(function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        return view('admin.portfolio-items.index', [
            'items' => $items->paginate(24)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.portfolio-items.create', [
            'item' => new PortfolioItem([
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 0,
            ]),
        ]);
    }

    public function store(Request $request, ImageOptimizer $images)
    {
        $data = $this->validateData($request, true);
        $image = $images->store($request->file('image'), 'portfolio');
        $beforeImagePath = $this->storeSingleVariantImage($request, $images, 'before_image');
        $afterImagePath = $this->storeSingleVariantImage($request, $images, 'after_image');

        $item = PortfolioItem::create([
            ...$data,
            'image_path' => $image['path'],
            'webp_path' => $image['webp_path'],
            'thumbnail_path' => $image['thumbnail_path'],
            'before_image_path' => $beforeImagePath,
            'after_image_path' => $afterImagePath,
            'width' => $image['width'],
            'height' => $image['height'],
        ]);

        AuditLog::record('portfolio.created', $item, [], $item->fresh()->toArray(), 'Portfolio item created.');

        return redirect()->route('admin.portfolio-items.edit', $item)->with('success', 'Portfolio item created.');
    }

    public function edit(PortfolioItem $portfolioItem)
    {
        return view('admin.portfolio-items.edit', [
            'item' => $portfolioItem,
        ]);
    }

    public function update(Request $request, PortfolioItem $portfolioItem, ImageOptimizer $images)
    {
        $data = $this->validateData($request);
        $before = $portfolioItem->getOriginal();

        if ($request->hasFile('image')) {
            $images->deleteStoredImages($portfolioItem->storagePaths());
            $image = $images->store($request->file('image'), 'portfolio');

            $data = [
                ...$data,
                'image_path' => $image['path'],
                'webp_path' => $image['webp_path'],
                'thumbnail_path' => $image['thumbnail_path'],
                'width' => $image['width'],
                'height' => $image['height'],
            ];
        }

        if ($request->hasFile('before_image')) {
            $images->deleteStoredImages([$portfolioItem->before_image_path]);
            $data['before_image_path'] = $this->storeSingleVariantImage($request, $images, 'before_image');
        }

        if ($request->hasFile('after_image')) {
            $images->deleteStoredImages([$portfolioItem->after_image_path]);
            $data['after_image_path'] = $this->storeSingleVariantImage($request, $images, 'after_image');
        }

        $portfolioItem->update($data);

        AuditLog::record('portfolio.updated', $portfolioItem, $before, $portfolioItem->fresh()->toArray(), 'Portfolio item updated.');

        return back()->with('success', 'Portfolio item updated.');
    }

    public function destroy(PortfolioItem $portfolioItem, ImageOptimizer $images)
    {
        $before = $portfolioItem->toArray();
        $images->deleteStoredImages($portfolioItem->storagePaths());
        $portfolioItem->delete();

        AuditLog::record('portfolio.deleted', null, $before, [], 'Portfolio item deleted.');

        return redirect()->route('admin.portfolio-items.index')->with('success', 'Portfolio item deleted.');
    }

    private function validateData(Request $request, bool $imageRequired = false): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_featured' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'published_at' => ['nullable', 'date'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'max:8192', 'dimensions:min_width=200,min_height=200'],
            'before_image' => ['nullable', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'max:8192', 'dimensions:min_width=200,min_height=200'],
            'after_image' => ['nullable', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'max:8192', 'dimensions:min_width=200,min_height=200'],
        ]);
    }

    private function storeSingleVariantImage(Request $request, ImageOptimizer $images, string $field): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        $stored = $images->store($request->file($field), 'portfolio');
        $images->deleteStoredImages([$stored['path'], $stored['thumbnail_path']]);

        return $stored['webp_path'];
    }
}
