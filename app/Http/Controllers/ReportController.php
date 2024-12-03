<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Sos;
use Inertia\Inertia;
use App\Models\Patrol;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\PatrolLocation;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Report/Index', [
            'title' => 'Reports'
        ]);
    }

    public function history(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_id' => ['nullable', 'exists:companies,id,is_active,1'],
                'patrol_location_id' => ['nullable', 'exists:patrol_locations,id,is_active,1,company_id,' . $request->company_id],
                'month' => ['nullable', 'numeric', 'min:1', 'max:12'],
                'year' => ['nullable', 'numeric', 'min:' . now()->year - 1, 'max:' . now()->year],
            ]);

            $companies = Company::orderBy('name')
                ->with([
                    'patrolLocations' => function ($query) {
                        $query->where('is_active', 1)->orderBy('name')->select('id', 'name', 'company_id');
                    }
                ])
                ->select('id', 'name')
                ->get();

            $patrols = collect();

            if ($request->has(['company_id', 'patrol_location_id', 'month', 'year'])) {
                $patrols = Patrol::whereYear('created_at', $validated['year'])
                    ->whereMonth('created_at', $validated['month'])
                    ->where('company_id', $validated['company_id'])
                    ->where('patrol_location_id', $validated['patrol_location_id'])
                    ->with(
                        'company:id,name',
                        'patrolCheckpoint:id,name',
                        'patrolLocation:id,name',
                        'patrolEvent:id,name'
                    )
                    ->get();
            }

            return Inertia::render(
                'Report/PatrolHistory',
                [
                    'title' => 'Patrol History',
                    'companies' => $companies,
                    'patrols' => $patrols->toArray(),
                ]
            );
        } catch (Throwable $th) {
            dd($th);
            Log::error($th);
            return redirect()->back()->with('error', [
                'message' => 'Something went wrong',
                'id' => uniqid()
            ]);
        }
    }

    public function incident(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_id' => ['nullable', 'exists:companies,id,is_active,1'],
                'patrol_location_id' => ['nullable', 'exists:patrol_locations,id,is_active,1,company_id,' . $request->company_id],
                'month' => ['nullable', 'numeric', 'min:1', 'max:12'],
                'year' => ['nullable', 'numeric', 'min:' . now()->year - 1, 'max:' . now()->year],
            ]);

            $companies = Company::orderBy('name')
                ->with([
                    'patrolLocations' => function ($query) {
                        $query->where('is_active', 1)->orderBy('name')->select('id', 'name', 'company_id');
                    }
                ])
                ->select('id', 'name')
                ->get();

            $incidents = collect();

            if ($request->has(['company_id', 'patrol_location_id', 'month', 'year'])) {
                $incidents = Sos::whereYear('created_at', $validated['year'])
                    ->whereMonth('created_at', $validated['month'])
                    ->where('company_id', $validated['company_id'])
                    ->where('patrol_location_id', $validated['patrol_location_id'])
                    ->get();
            }

            return Inertia::render(
                'Report/IncidentHistory',
                [
                    'title' => 'SOS / Incident History',
                    'companies' => $companies,
                    'incidents' => $incidents->toArray(),
                ]
            );
        } catch (Throwable $th) {
            dd($th);
            Log::error($th);
            return redirect()->back()->with('error', [
                'message' => 'Something went wrong',
                'id' => uniqid()
            ]);
        }
    }
}
