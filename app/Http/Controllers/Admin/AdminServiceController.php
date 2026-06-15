<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query()->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($where) use ($search): void {
                $where->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return view('admin.services.index', [
            'services' => $query->paginate(20)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.services.create', [
            'service' => new Service([
                'is_active' => true,
                'is_consultation_based' => false,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $service = Service::create($this->validatedData($request));

        return redirect()
            ->route('admin.services.edit', $service)
            ->with('success', 'Service and price created.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', [
            'service' => $service,
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->validatedData($request));

        return back()->with('success', 'Service and price updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required','string','max:255'],
            'price' => ['nullable','integer','min:0'],
            'description' => ['nullable','string','max:4000'],
            'is_consultation_based' => ['required','boolean'],
            'is_active' => ['required','boolean'],
        ]);
    }
}
