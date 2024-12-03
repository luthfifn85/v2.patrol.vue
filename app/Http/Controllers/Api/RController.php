<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Carbon\Carbon;
use App\Models\Sos;
use App\Models\Patrol;
use Illuminate\Http\Request;
use App\Models\PatrolLocation;
use App\Models\PatrolCheckpoint;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RController extends Controller
{
    public function patrol(Request $request)
    {
        try
        {
            $date = $request->date;

            // role admin & client
            if (Auth::user()->patrol_role_id == 1 || Auth::user()->patrol_role_id == 4)
            {
                $get = Patrol::with('company:id,name', 'patrolLocation:id,name', 'patrolCheckpoint:id,name', 'patrolEvent:id,name', 'patrolUser:id,name')
                    ->withCount('comments')
                    ->whereDate('created_at', $date)
                    ->where('company_id', Auth::user()->company_id)
                    ->latest()
                    ->get();
            }

            // role chief & guard
            else
            {
                $get = Patrol::with('company:id,name', 'patrolLocation:id,name', 'patrolCheckpoint:id,name', 'patrolEvent:id,name', 'patrolUser:id,name')
                    ->withCount('comments')
                    ->whereDate('created_at', $date)
                    ->where('patrol_location_id', Auth::user()->patrol_location_id)
                    ->latest()
                    ->get();
            }

            $show = [];

            foreach ($get as $data)
            {
                $show[] = [
                    'id' => $data->id,
                    'datetime' => $data->created_at->format('d-M-Y, H:i A'),
                    'location' => $data->patrolLocation->name,
                    'checkpoint' => $data->patrolCheckpoint->name,
                    'event' => $data->patrolEvent->name,
                    'views' => $data->views,
                    'comment' => $data->comment_count
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'patrol report generated',
                'total' => $get->count(),
                'data' => $show
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

    public function sos(Request $request)
    {
        try
        {
            $date = $request->date;

            // role admin & client
            if (Auth::user()->patrol_role_id == 1 || Auth::user()->patrol_role_id == 4)
            {
                $get = Sos::with('company:id,name', 'patrolLocation:id,name', 'patrolUser:id,name')
                    ->withCount('comments')
                    ->whereDate('created_at', $date)
                    ->where('company_id', Auth::user()->company_id)
                    ->latest()
                    ->get();
            }

            // role chief & guard
            else
            {
                $get = Sos::with('company:id,name', 'patrolLocation:id,name', 'patrolUser:id,name')
                    ->withCount('comments')
                    ->whereDate('created_at', $date)
                    ->where('patrol_location_id', Auth::user()->patrol_location_id)
                    ->latest()
                    ->get();
            }

            $show = [];

            foreach ($get as $data)
            {
                $show[] = [
                    'id' => $data->id,
                    'datetime' => $data->created_at->format('d-M-Y, H:i A'),
                    'location' => $data->patrolLocation->name,
                    'title' => $data->title,
                    'views' => $data->views,
                    'comment' => $data->comment_count
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'sos report generated',
                'total' => $get->count(),
                'data' => $show
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

    public function summary()
    {
        try
        {
            $date = now();

            // role admin & client
            if (Auth::user()->patrol_role_id == 1 || Auth::user()->patrol_role_id == 4)
            {
                $location = PatrolLocation::with(['checkpoints' => function ($q) {
                   $q->withCount(['patrolTransactions' => function ($w) {
                        $w->whereDate('created_at', now());
                   }]);
                }], 'sos')
                    ->where('company_id', Auth::user()->company_id)
                    ->get();

                $sos = Sos::where('company_id', Auth::user()->company_id)
                    ->whereDate('created_at', $date)
                    ->get();

                $location_data = [];

                foreach ($location as $data)
                {
                    $checkpoint_data = [];

                    foreach ($data->checkpoints as $row)
                    {
                        $checkpoint_data[] = [
                            'name' => $row->name,
                            'total' => $row->patrol_count,
                        ];
                    }

                    $location_data[] = [
                        'name' => $data->name,
                        'checkpoint' => [
                            'total' => $data->checkpoints->count(),
                            'patrol' => array_sum(array_column($checkpoint_data, 'total')),
                            'unpatrol' => $data->checkpoints->count() - array_sum(array_column($checkpoint_data, 'total')),
                            'summary' => $checkpoint_data
                        ],
                        'sos' => [
                            'total' => $data->sos->count()
                        ]
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'summary report generated',
                    'data' => [
                        'date' => Carbon::parse($date)->format('d-M-Y'),
                        'company' => Auth::user()->company->name,
                        'data' => [
                            'total' => $location->count(),
                            'location' => $location_data
                        ]
                    ]
                ]);
            }

            // role chief & guard
            else
            {
                $checkpoint = PatrolCheckpoint::with('patrolTransactions')
                    ->withCount(['patrolTransactions' => function ($q) {
                        $q->whereDate('created_at', now());
                    }])
                    ->where('patrol_location_id', Auth::user()->patrol_location_id)
                    ->get();

                $sos = Sos::where('patrol_location_id', Auth::user()->patrol_location_id)
                    ->whereDate('created_at', $date)
                    ->get();

                $checkpoint_data = [];

                foreach ($checkpoint as $data)
                {
                    $checkpoint_data[] = [
                        'name' => $data->name,
                        'total' => $data->patrol_count
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'summary report generated',
                    'data' =>  [
                        'date' => Carbon::parse($date)->format('d-M-Y'),
                        'company' => Auth::user()->company->name,
                        'location' => Auth::user()->patrolLocation->name,
                        'checkpoint' => [
                            'total' => $checkpoint->count(),
                            'patrol' => array_sum(array_column($checkpoint_data, 'total')),
                            'unpatrol' => $checkpoint->count() - array_sum(array_column($checkpoint_data, 'total')),
                            'summary' => $checkpoint_data
                        ],
                        'sos' => [
                            'total' => $sos->count()
                        ]
                    ]
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
}
