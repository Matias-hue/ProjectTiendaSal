<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->query('search');
            Log::debug('LogController: search = ' . $search . ', page = ' . $request->query('page', 1));

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
                Log::debug('LogController: AJAX request, returning JSON', [
                    'data_count' => count($logs->items()),
                    'links' => $logs->links()->toHtml(),
                ]);
                return response()->json([
                    'data' => $logs->items(),
                    'links' => $logs->links()->toHtml(),
                ], 200);
            }

            return view('registro', compact('logs', 'search'));
        } catch (\Exception $e) {
            Log::error('LogController: Error in index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            if ($request->ajax()) {
                return response()->json(['error' => 'Error al cargar los registros'], 500);
            }
            return view('registro', ['logs' => collect(), 'search' => $search])
                ->with('error', 'Error al cargar los registros');
        }
    }
}