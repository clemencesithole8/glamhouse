<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class AdminSocialLinkController extends Controller
{
    public function index()
    {
        return view('admin.social-links.index', [
            'socialLinks' => SocialLink::query()
                ->orderBy('display_order')
                ->orderBy('platform')
                ->paginate(30),
        ]);
    }

    public function create()
    {
        return view('admin.social-links.create', [
            'socialLink' => new SocialLink([
                'is_active' => true,
                'display_order' => 0,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $socialLink = SocialLink::create($this->validatedData($request));

        AuditLog::record('settings.social_link_created', $socialLink, [], $socialLink->toArray(), 'Social link created.');

        return redirect()
            ->route('admin.social-links.edit', $socialLink)
            ->with('success', 'Social link created.');
    }

    public function edit(SocialLink $socialLink)
    {
        return view('admin.social-links.edit', [
            'socialLink' => $socialLink,
        ]);
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $before = $socialLink->getOriginal();
        $socialLink->update($this->validatedData($request));

        AuditLog::record('settings.social_link_updated', $socialLink, $before, $socialLink->fresh()->toArray(), 'Social link updated.');

        return back()->with('success', 'Social link updated.');
    }

    public function destroy(SocialLink $socialLink)
    {
        $before = $socialLink->toArray();
        $socialLink->delete();

        AuditLog::record('settings.social_link_deleted', null, $before, [], 'Social link deleted.');

        return redirect()
            ->route('admin.social-links.index')
            ->with('success', 'Social link deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'platform' => ['required','string','max:50'],
            'label' => ['nullable','string','max:255'],
            'url' => ['required','url','max:2048'],
            'display_order' => ['required','integer','min:0'],
            'is_active' => ['required','boolean'],
        ]);
    }
}
