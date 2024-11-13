<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\PatrolLocation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class PatrolLocationController extends Controller
{
    public function index()
    {
        try {
            $locations = PatrolLocation::whereHas('company', function ($query) {
                $query->where('is_active', 1);
            })
                ->with('company:id,name')
                ->withCount(['checkpoints' => function ($query) {
                    $query->where('is_active', 1);
                }, 'officers' => function ($query) {
                    $query->where('is_active', 1);
                }])
                ->get();

            $companies = Company::where('is_active', 1)
            ->get();

            return Inertia::render('Location/Index', [
                'title' => 'Locations',
                'locations' => $locations->toArray(),
                'companies' => $companies,
                'locationCount' => $locations->count(),
        ]);
        } catch (Throwable $th) {
            Log::error($th);
            return back()->with('error', 'Something went wrong');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'company_id' => ['required', 'exists:companies,id,is_active,1'],
                    'name' => ['required', 'max:255', Rule::unique('patrol_locations', 'name')->ignore($request->id)],
                    'address' => ['required', 'max:255'],
                    'phone' => ['required', 'digits_between:10,13', Rule::unique('patrol_locations', 'phone')->ignore($request->id), 'regex:/^[0-9]*$/'],
                ],
                [
                    'company_id.required' => 'The company field is required',
                    'company_id.exists' => 'The selected company is invalid',
                    'name.required' => 'The name field is required',
                    'name.max' => 'The name may not be greater than 255 characters',
                    'name.unique' => 'The name has already been taken',
                    'address.required' => 'The address field is required',
                    'address.max' => 'The address may not be greater than 255 characters',
                    'phone.required' => 'The phone field is required',
                    'phone.digits_between' => 'The phone must be between 10 and 13 digits',
                    'phone.unique' => 'The phone has already been taken',
                    'phone.regex' => 'The phone is invalid',
                ]
            );

            PatrolLocation::create($validated);

            return redirect()->back()->with('success', [
                'message' => "Location with name {$validated['name']} successfully created",
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
