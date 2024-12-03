<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\PatrolRole;
use App\Models\PatrolUser;
use Illuminate\Http\Request;
use App\Models\PatrolLocation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class PatrolUserController extends Controller
{
    public function index()
    {
        $guards = PatrolUser::whereHas('company', function ($query) {
            $query->where('is_active', 1);
        })->whereHas('patrolRole', function ($query) {
            $query->where('is_active', 1);
        })->with('company:id,name', 'patrolLocation:id,name', 'patrolRole:id,name')
            ->get();

        $companies = Company::where('is_active', 1)
            ->get();

        $locations = PatrolLocation::where('is_active', 1)->get();

        $roles = PatrolRole::where('is_active', 1)->get();

        return Inertia::render('PatrolUser/Index', [
            'title' => "Guards",
            'guards' => $guards->toArray(),
            'guardCount' => $guards->count(),
            'companies' => $companies->toArray(),
            'locations' => $locations->toArray(),
            'roles' => $roles->toArray()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_id' => ['required', 'exists:companies,id,is_active,1'],
                'patrol_role_id' => ['required', 'exists:patrol_roles,id,is_active,1'],
                'patrol_location_id' => [
                    Rule::requiredIf(function () use ($request) {
                        return $request->patrol_role_id != 1 && $request->patrol_role_id != 4;
                    }),
                    'exists:patrol_locations,id,is_active,1,company_id,' . $request->company_id,
                ],
                'mobile_no' => ['required', 'numeric', 'digits_between:10,13', 'unique:patrol_users,mobile_no'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'unique:patrol_users,email', 'email', 'max:255', 'string'],
                'password' => ['required', 'min:8']
            ], [
                'company_id.required' => 'The company field is required',
                'company_id.exists' => 'The selected company is invalid',
                'patrol_role_id.required' => 'The role field is required',
                'patrol_role_id.exists' => 'The selected role is invalid',
                'patrol_location_id.required' => 'The location field is required',
                'patrol_location_id.exists' => 'The selected location is invalid',
                'mobile_no.required' => 'The mobile number field is required',
                'mobile_no.numeric' => 'The mobile number must be a number',
                'mobile_no.digits_between' => 'The mobile number must be between 10 and 13 digits',
                'mobile_no.unique' => 'The mobile number has already been taken',
                'name.required' => 'The name field is required',
                'name.string' => 'The name must be a string',
                'name.max' => 'The name may not be greater than 255 characters',
                'email.required' => 'The email field is required',
                'email.unique' => 'The email has already been taken',
                'email.email' => 'The email must be a valid email address',
                'email.max' => 'The email may not be greater than 255 characters',
                'email.string' => 'The email must be a string',
                'password.required' => 'The password field is required',
                'password.min' => 'The password must be at least 8 characters'
            ]);

            PatrolUser::create([
                ...$validated,
                'patrol_location_id' => $validated['patrol_role_id'] && ($validated['patrol_role_id'] != 1 && $validated['patrol_role_id'] != 4) ? $validated['patrol_location_id'] : null,
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()->back()->with('success', [
                'message' => 'New Guard successfully added',
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

    public function update(Request $request, PatrolUser $get)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'mobile_no' => ['required', 'numeric', 'digits_between:10,13']
            ]);

            $get->update([
                'mobile_no' => $request->mobile_no
            ]);

            DB::commit();

            return back()->with('success', 'Checkpoint updated');
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function changePassword(Request $request, PatrolUser $patrolUserBind)
    {
        try {
            $validated = $request->validate([
                'password' => ['required', 'min:8']
            ]);

            $patrolUserBind->update([
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()->back()->with('success', [
                'message' => 'Password successfully changed',
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

    public function resetSession(PatrolUser $patrolUserBind)
    {
        try {
            $patrolUserBind->update([
                'is_login' => null
            ]);

            return redirect()->back()->with('success', [
                'message' => 'Session successfully reset',
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
