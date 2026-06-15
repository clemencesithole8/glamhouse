<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminAuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::query()
            ->with('user')
            ->latest();

        if ($request->filled('action')) {
            $logs->where('action', $request->string('action'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $logs->where(function ($query) use ($search): void {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('auditable_type', 'like', "%{$search}%");
            });
        }

        return view('admin.audit-logs.index', [
            'logs' => $logs->paginate(30)->withQueryString(),
        ]);
    }
}
