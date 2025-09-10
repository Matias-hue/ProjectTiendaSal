<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $logs = ActivityLog::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'data' => $logs->items(),
                'links' => $logs->links()->toHtml(),
            ]);
        }

        return view('registro', compact('logs', 'search'));
    }
}