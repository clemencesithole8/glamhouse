<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => ['required','string','max:100','regex:/^[a-z0-9_]+$/','unique:media_assets,key'],
            'title' => ['nullable','string','max:255'],
            'alt' => ['nullable','string','max:255'],
            'is_active' => ['required','boolean'],
            'image' => ['required','file','mimes:jpg,jpeg,png,webp','max:8192'],
        ], [
            'key.regex' => 'Key must be lowercase letters/numbers/underscores only (e.g. home_hero).',
        ]);

        $path = $request->file('image')->store('media', 'public');

        $asset = MediaAsset::create([
            'key' => $data['key'],
            'path' => $path,
            'disk' => 'public',
            'title' => $data['title'] ?? null,
            'alt' => $data['alt'] ?? null,
            'is_active' => (bool)$data['is_active'],
        ]);

        return redirect()->route('admin.media-assets.edit', $asset)->with('success', 'Image asset created.');
    }

    public function edit(MediaAsset $mediaAsset)
    {
        return view('admin.media-assets.edit', [
            'asset' => $mediaAsset,
        ]);
    }

    public function update(Request $request, MediaAsset $mediaAsset)
    {
        $data = $request->validate([
            'key' => ['required','string','max:100','regex:/^[a-z0-9_]+$/','unique:media_assets,key,'.$mediaAsset->id],
            'title' => ['nullable','string','max:255'],
            'alt' => ['nullable','string','max:255'],
            'is_active' => ['required','boolean'],
            'image' => ['nullable','file','mimes:jpg,jpeg,png,webp','max:8192'],
        ], [
            'key.regex' => 'Key must be lowercase letters/numbers/underscores only (e.g. home_hero).',
        ]);

        // Replace image if uploaded
        if ($request->hasFile('image')) {
            // delete old file
            if ($mediaAsset->path && Storage::disk($mediaAsset->disk)->exists($mediaAsset->path)) {
                Storage::disk($mediaAsset->disk)->delete($mediaAsset->path);
            }

            $mediaAsset->path = $request->file('image')->store('media', 'public');
            $mediaAsset->disk = 'public';
        }

        $mediaAsset->key = $data['key'];
        $mediaAsset->title = $data['title'] ?? null;
        $mediaAsset->alt = $data['alt'] ?? null;
        $mediaAsset->is_active = (bool)$data['is_active'];
        $mediaAsset->save();

        return back()->with('success', 'Image asset updated.');
    }

    public function destroy(MediaAsset $mediaAsset)
    {
        if ($mediaAsset->path && Storage::disk($mediaAsset->disk)->exists($mediaAsset->path)) {
            Storage::disk($mediaAsset->disk)->delete($mediaAsset->path);
        }

        $mediaAsset->delete();

        return redirect()->route('admin.media-assets.index')->with('success', 'Image asset deleted.');
    }
}