<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\PatrolLocation;
use Illuminate\Validation\Rule;
use App\Models\PatrolCheckpoint;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CController extends Controller
{
    public function index()
    {
        try
        {
            // role: admin or client
            if (Auth::user()->patrol_role_id == 1 || Auth::user()->patrol_role_id == 4)
            {
                $get = PatrolLocation::with('checkpoints')
                    ->where('company_id', Auth::user()->company_id)
                    ->orderBy('name')
                    ->get();

                $list = [];

                foreach ($get as $data)
                {
                    $show = [];

                    foreach ($data->checkpoints as $row)
                    {
                        $show[] = [
                            'id' => $row->id,
                            'uuid' => $row->uuid,
                            'name' => $row->name,
                            'mode' => $row->mode == 1 ? 'Realtime' : 'From Gallery',
                            'status' => $row->is_active == 1 ? 'Active' : 'Inactive'
                        ];
                    }

                    $list[] = [
                        'id' => $data->id,
                        'location' => $data->name,
                        'total' => $data->checkpoints->count(),
                        'data' => $show
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'checkpoint data generated',
                    'data' => $list
                ], 200);
            }

            // role: chief or guard
            else
            {
                $get = PatrolLocation::with('checkpoints')
                    ->where('id', Auth::user()->patrol_location_id)
                    ->orderBy('name')
                    ->get();

                $list = [];

                foreach ($get as $data)
                {
                    $show = [];

                    foreach ($data->checkpoints as $row)
                    {
                        $show[] = [
                            'id' => $row->id,
                            'uuid' => $row->uuid,
                            'name' => $row->name,
                            'mode' => $row->mode == 1 ? 'Realtime' : 'From Gallery',
                            'status' => $row->is_active == 1 ? 'Active' : 'Inactive'
                        ];
                    }

                    $list[] = [
                        'id' => $data->id,
                        'location' => $data->name,
                        'total' => $data->checkpoints->count(),
                        'data' => $show
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'checkpoint data generated',
                    'data' => $list
                ], 200);
            }
        }

        catch (Throwable $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'name' => ['required', Rule::unique('patrol_checkpoints', 'name')->where('patrol_location_id', $request->patrol_location_id)],
                'mode' => ['required']
            ]);

            $store = PatrolCheckpoint::create([
                'company_id' => Auth::user()->company_id,
                'patrol_location_id' => Auth::user()->patrol_location_id,
                'uuid' => Uuid::uuid4(),
                'name' => $request->name,
                'mode' => $request->mode
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'new checkpoint created',
                'data' => [
                    'uuid' => $store->uuid,
                    'name' => $store->name
                ]
            ], 201);
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(PatrolCheckpoint $id)
    {
        try
        {
            return response()->json([
                'status' => 'success',
                'message' => 'checkpoint data generated',
                'data' => [
                    'id' => $id->id,
                    'uuid' => $id->uuid,
                    'company' => $id->company->name,
                    'location' => $id->patrolLocation->name,
                    'name' => $id->name,
                    'mode' => $id->is_active == 1 ? 'Realtime' : 'From Gallery',
                    'status' => $id->is_active == 1 ? 'Active' : 'Inactive'
                ]
            ], 200);
        }

        catch (Throwable $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function edit(Request $request, PatrolCheckpoint $id)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'mode' => ['required'],
            ]);

            $id->update([
                'mode' => $request->mode,
                'is_active' => $request->is_active
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'checkpoint updated'
            ], 201);
        }

        catch (throwable $e)
        {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
