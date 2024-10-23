<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PatrolCheckpoint;
use App\Models\PatrolLocation;
use App\Models\PatrolUser;
use App\Models\PatrolUserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;
use Throwable;

class SettingController extends Controller
{
    public function cindex()
    {
        try
        {
            // role: admin $ client
            if (Auth::user()->patrol_role_id == 1 || Auth::user()->patrol_role_id == 4)
            {
                $get = PatrolLocation::with('checkpoint')
                    ->where('company_id', Auth::user()->company_id)
                    ->orderBy('name')
                    ->get();

                $list = [];

                foreach ($get as $data)
                {
                    $show = [];

                    foreach ($data->checkpoint as $row)
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
                        'total' => $data->checkpoint->count(),
                        'data' => $show
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'checkpoint data generated',
                    'data' => $list
                ], 200);
            }

            // role: chief & guard
            else
            {
                $get = PatrolLocation::with('checkpoint')
                    ->where('id', Auth::user()->patrol_location_id)
                    ->orderBy('name')
                    ->get();

                $list = [];

                foreach ($get as $data)
                {
                    $show = [];

                    foreach ($data->checkpoint as $row)
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
                        'total' => $data->checkpoint->count(),
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

    public function cstore(Request $request)
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

            $store = PatrolCheckpoint::create([
                'company_id' => $request->company_id,
                'patrol_location_id' => $request->patrol_location_id,
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

    public function cshow(PatrolCheckpoint $id)
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

    public function cedit(Request $request, PatrolCheckpoint $id)
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
                'message' => 'checkpoint upddated'
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
