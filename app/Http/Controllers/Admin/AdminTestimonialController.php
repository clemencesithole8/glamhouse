<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class AdminTestimonialController extends Controller
{
    public function index(Request $request)
    {
        $testimonials = Testimonial::query()
            ->orderBy('sort_order')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $testimonials->where(function ($query) use ($search): void {
                $query->where('client_name', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        return view('admin.testimonials.index', [
            'testimonials' => $testimonials->paginate(30)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.testimonials.create', [
            'testimonial' => new Testimonial([
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 0,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $testimonial = Testimonial::create($this->validateData($request));

        AuditLog::record('testimonial.created', $testimonial, [], $testimonial->toArray(), 'Testimonial created.');

        return redirect()->route('admin.testimonials.edit', $testimonial)->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', [
            'testimonial' => $testimonial,
        ]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $before = $testimonial->getOriginal();
        $testimonial->update($this->validateData($request));

        AuditLog::record('testimonial.updated', $testimonial, $before, $testimonial->fresh()->toArray(), 'Testimonial updated.');

        return back()->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $before = $testimonial->toArray();
        $testimonial->delete();

        AuditLog::record('testimonial.deleted', null, $before, [], 'Testimonial deleted.');

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_role' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'source' => ['nullable', 'string', 'max:100'],
            'is_featured' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'published_at' => ['nullable', 'date'],
        ]);
    }
}
