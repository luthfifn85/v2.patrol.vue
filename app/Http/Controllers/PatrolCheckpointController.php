<?php

namespace App\Http\Controllers;

use App\Models\PatrolCheckpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;
use Throwable;

class PatrolCheckpointController extends Controller
{
    public function index()
    {
        $title = 'Checkpoint';

        $get = PatrolCheckpoint::with('company:id,name', 'patrolLocation:id,name')
            ->paginate(200);

        return view('checkpoint.index', [
            'title' => $title,
            'get' => $get
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'company_id' => ['required'],
                'patrol_location_id' => ['required'],
                'name' => ['required', Rule::unique('patrol_checkpoints', 'name')->where('patrol_location_id', $request->patrol_location_id)],
                'mode' => ['required']
            ]);

            PatrolCheckpoint::create([
                'company_id' => $request->company_id,
                'patrol_location_id' => $request->patrol_location_id,
                'uuid' => Uuid::uuid4(),
                'name' => $request->name,
                'mode' => $request->mode
            ]);

            DB::commit();

            return back()->with('success', 'New checkpoint created');
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            throw $e;
        }
    }

    public function update(Request $request, PatrolCheckpoint $get)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'mode' => ['required']
            ]);

            $get->update([
                'mode' => $request->mode
            ]);

            DB::commit();

            return back()->with('success', 'Checkpoint updated');
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            throw $e;
        }
    }
}
