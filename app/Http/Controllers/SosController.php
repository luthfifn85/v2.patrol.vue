<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Sos;
use Inertia\Inertia;
use App\Models\SosMedia;
use App\Models\SosComment;
use Illuminate\Support\Facades\Log;

class SosController extends Controller
{
    public function index()
    {
        $incidents = Sos::with('company:id,name', 'patrolLocation:id,name', 'patrolUser:id,name', 'mediaItems')
            ->withCount('comments')
            ->whereDate('created_at', now())
            ->latest()
            ->get();

        return Inertia::render('Sos/Index', [
            'title' => "SOS / Incident",
            'incidents' => $incidents,
            'incidentCount' => $incidents->count()
        ]);
    }

    public function show(Sos $sosBind)
    {
        try {

            $sosBind->load([
                'company:id,name',
                'patrolLocation:id,name',
                'patrolUser:id,name',
                'mediaItems:id,filename,sos_id',
                'comments' => function ($query) {
                    $query->with([
                        'patrolUser' => function ($query) {
                            $query->with('patrolRole:id,name');
                        }
                    ]);
                },
            ]);

            // dd($sosBind);

            return Inertia::render('Sos/Show', [
                'title' => "SOS / Incident",
                'incident' => $sosBind,
            ]);
        } catch (Throwable $th) {
            dd($th);
            Log::error($th);
            return redirect()->back()->with('error', [
                'message' => 'Failed to load SOS / Incident',
                'id' => uniqid()
            ]);
        }
    }
}
