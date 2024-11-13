<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use Ramsey\Uuid\Uuid;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\PatrolLocation;
use Illuminate\Validation\Rule;
use App\Models\PatrolCheckpoint;
use Illuminate\Support\Facades\Log;

class PatrolCheckpointController extends Controller
{
    public function index()
    {
        $title = 'Checkpoint';

        $checkpoints = PatrolCheckpoint::whereHas('company', function ($query) {
            $query->where('is_active', 1);
        })
            ->whereHas('patrolLocation', function ($query) {
                $query->where('is_active', 1);
            })
            ->with('company:id,name', 'patrolLocation:id,name')
            ->get();

        $locations = PatrolLocation::where('is_active', 1)
            ->get();

        $companies = Company::where('is_active', 1)
            ->get();

        return Inertia::render('Checkpoint/Index', [
            'title' => $title,
            'checkpoints' => $checkpoints->toArray(),
            'checkpointCount' => $checkpoints->count(),
            'locations' => $locations->toArray(),
            'companies' => $companies->toArray()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_id' => ['required', 'exists:companies,id,is_active,1'],
                'patrol_location_id' => ['required', 'exists:patrol_locations,id,is_active,1'],
                'name' => ['required', Rule::unique('patrol_checkpoints', 'name')->where('patrol_location_id', $request->patrol_location_id)],
            ], [
                'company_id.required' => 'The company field is required',
                'company_id.exists' => 'The selected company is invalid',
                'patrol_location_id.required' => 'The location field is required',
                'patrol_location_id.exists' => 'The selected location is invalid',
                'name.required' => 'The name field is required',
                'name.unique' => 'The name has already been taken',
            ]);

            PatrolCheckpoint::create([
                ...$validated,
                'uuid' => Uuid::uuid4()
            ]);

            return redirect()->back()->with('success', [
                'message' => "Checkpoint with name {$validated['name']} successfully created",
                'id' => uniqid()
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', [
                'message' => 'Something went wrong',
                'id' => uniqid()
            ]);
        }
    }

    public function update(Request $request, PatrolCheckpoint $checkpointBind)
    {
        try {
            $validated = $request->validate([
                'mode' => ['nullable', 'in:0,1']
            ], [
                'mode.in' => 'The mode field is invalid'
            ]);

            $checkpointBind->update([
                ...$validated,
                'mode' => $validated['mode'] ? 1 : null
            ]);

            return redirect()->back()->with('success', [
                'message' => 'Checkpoint successfully updated',
                'id' => uniqid()
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', [
                'message' => 'Something went wrong',
                'id' => uniqid()
            ]);
        }
    }

    public function generateQRCode(PatrolCheckpoint $checkpointBind)
    {
        try {
            $checkpointBind->update([
                'uuid' => Uuid::uuid4()
            ]);

            return redirect()->back()->with('success', [
                'message' => 'New QR Code successfully generated',
                'id' => uniqid()
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', [
                'message' => 'Something went wrong',
                'id' => uniqid()
            ]);
        }
    }
}
