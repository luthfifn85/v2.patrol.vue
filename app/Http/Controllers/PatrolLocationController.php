<?php

namespace App\Http\Controllers;

use App\Models\PatrolLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class PatrolLocationController extends Controller
{
    public function index()
    {
        $title = 'Location';

        $get = PatrolLocation::with('company:id,name')
            ->get();

        return view('location.index', [
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
                'name' => ['required', Rule::unique('patrol_locations', 'name')->where('company_id', $request->company_id)],
                'address' => ['required'],
                'phone' => ['required']
            ]);

            PatrolLocation::create([
                'company_id' => $request->company_id,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone
            ]);

            DB::commit();

            return back()->with('success', 'New location created');
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            throw $e;
        }
    }

    public function update(Request $request, PatrolLocation $get)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'address' => ['required'],
                'phone' => ['required']
            ]);

            $get->update([
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            DB::commit();

            return back()->with('success', 'Location updated');
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            throw $e;
        }
    }
}
