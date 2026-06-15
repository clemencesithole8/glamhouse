<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MediaAsset;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;

class AdminMediaAssetController extends Controller
{
    public function index(Request $request)
    {
        $q = MediaAsset::query()->orderBy('key');

        if ($request->filled('search')) {
            $s = $request->string('search');
            $q->where('key', 'like', "%{$s}%")
              ->orWhere('title', 'like', "%{$s}%");
        }

        return view('admin.media-assets.index', [
            'assets' => $q->paginate(30)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.media-assets.create');
    }

    public function store(Request $request, ImageOptimizer $images)
    {
        $data = $request->validate([
            'key' => ['required','string','max:100','regex:/^[a-z0-9_]+$/','unique:media_assets,key'],
            'title' => ['nullable','string','max:255'],
            'alt' => ['nullable','string','max:255'],
            'is_active' => ['required','boolean'],
            'image' => ['required','image','mimetypes:image/jpeg,image/png,image/webp','max:8192','dimensions:min_width=200,min_height=200'],
        ], [
            'key.regex' => 'Key must be lowercase letters/numbers/underscores only (e.g. home_hero).',
        ]);

        $image = $images->store($request->file('image'), 'media');

        $asset = MediaAsset::create([
            'key' => $data['key'],
            'path' => $image['path'],
            'webp_path' => $image['webp_path'],
            'thumbnail_path' => $image['thumbnail_path'],
            'disk' => 'public',
            'title' => $data['title'] ?? null,
            'alt' => $data['alt'] ?? null,
            'width' => $image['width'],
            'height' => $image['height'],
            'mime_type' => $image['mime_type'],
            'size' => $image['size'],
            'original_name' => $image['original_name'],
            'is_active' => (bool)$data['is_active'],
        ]);

        AuditLog::record('media.created', $asset, [], $asset->toArray(), 'Media asset created.');

        return redirect()->route('admin.media-assets.edit', $asset)->with('success', 'Image asset created.');
    }

    public function edit(MediaAsset $mediaAsset)
    {
        return view('admin.media-assets.edit', [
            'asset' => $mediaAsset,
        ]);
    }

    public function update(Request $request, MediaAsset $mediaAsset, ImageOptimizer $images)
    {
        $data = $request->validate([
            'key' => ['required','string','max:100','regex:/^[a-z0-9_]+$/','unique:media_assets,key,'.$mediaAsset->id],
            'title' => ['nullable','string','max:255'],
            'alt' => ['nullable','string','max:255'],
            'is_active' => ['required','boolean'],
            'image' => ['nullable','image','mimetypes:image/jpeg,image/png,image/webp','max:8192','dimensions:min_width=200,min_height=200'],
        ], [
            'key.regex' => 'Key must be lowercase letters/numbers/underscores only (e.g. home_hero).',
        ]);

        $before = $mediaAsset->getOriginal();

        if ($request->hasFile('image')) {
            $images->deleteStoredImages($mediaAsset->storagePaths(), $mediaAsset->disk);
            $image = $images->store($request->file('image'), 'media');

            $mediaAsset->path = $image['path'];
            $mediaAsset->webp_path = $image['webp_path'];
            $mediaAsset->thumbnail_path = $image['thumbnail_path'];
            $mediaAsset->disk = 'public';
            $mediaAsset->width = $image['width'];
            $mediaAsset->height = $image['height'];
            $mediaAsset->mime_type = $image['mime_type'];
            $mediaAsset->size = $image['size'];
            $mediaAsset->original_name = $image['original_name'];
        }

        $mediaAsset->key = $data['key'];
        $mediaAsset->title = $data['title'] ?? null;
        $mediaAsset->alt = $data['alt'] ?? null;
        $mediaAsset->is_active = (bool)$data['is_active'];
        $mediaAsset->save();

        AuditLog::record('media.updated', $mediaAsset, $before, $mediaAsset->fresh()->toArray(), 'Media asset updated.');

        return back()->with('success', 'Image asset updated.');
    }

    public function destroy(MediaAsset $mediaAsset, ImageOptimizer $images)
    {
        $before = $mediaAsset->toArray();
        $images->deleteStoredImages($mediaAsset->storagePaths(), $mediaAsset->disk);
        $mediaAsset->delete();

        AuditLog::record('media.deleted', null, $before, [], 'Media asset deleted.');

        return redirect()->route('admin.media-assets.index')->with('success', 'Image asset deleted.');
    }
}
