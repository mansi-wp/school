<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('user')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('action', 'LIKE', "%{$request->search}%")
                  ->orWhere('entity', 'LIKE', "%{$request->search}%");
            })
            ->latest('created_at')
            ->paginate(50);

        return view('admin.audit.index', compact('logs'));
    }
}
