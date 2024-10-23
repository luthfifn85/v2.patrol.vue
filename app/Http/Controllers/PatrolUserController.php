<?php

namespace App\Http\Controllers;

use App\Models\PatrolUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;

class PatrolUserController extends Controller
{
    public function index()
    {
        $title = 'Guard';

        $get = PatrolUser::with('company:id,name', 'patrolLocation:id,name', 'patrolRole:id,name')
            ->paginate(200);

        return view('guard.index', [
            'title' => $title,
            'get' => $get
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try
        {
            if ($request->patrol_role_id == 1 || $request->patrol_role_id == 4)
            {
                $request->validate([
                    'company_id' => ['required'],
                    'patrol_role_id' => ['required'],
                    'name' => ['required'],
                    'email' => ['required', 'unique:patrol_users,email'],
                    'password' => ['required', 'min:8']
                ]);
            }

            else
            {
                $request->validate([
                    'company_id' => ['required'],
                    'patrol_location_id' => ['required'],
                    'patrol_role_id' => ['required'],
                    'name' => ['required'],
                    'email' => ['required', 'unique:patrol_users,email'],
                    'password' => ['required', 'min:8']
                ]);
            }

            PatrolUser::create([
                'company_id' => $request->company_id,
                'patrol_location_id' => $request->patrol_location_id,
                'patrol_role_id' => $request->patrol_role_id,
                'name' => $request->name,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'password' => Hash::make($request->password)
            ]);

            DB::commit();

            return back()->with('successs', 'New guard created');
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            throw $e;
        }
    }

    public function update(Request $request, PatrolUser $get)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'mobile_no' => ['required', 'numeric', 'digits_between:10,13']
            ]);

            $get->update([
                'mobile_no' => $request->mobile_no
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

    public function password(Request $request, PatrolUser $get)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'password' => ['required', 'min:8']
            ]);

            $get->update([
                'password' => Hash::make($request->password)
            ]);

            DB::commit();

            return back()->with('success', 'Password updated');
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            throw $e;
        }
    }

    public function reset(PatrolUser $get)
    {
        DB::beginTransaction();

        try
        {
            $get->update([
                'is_login' => null
            ]);

            DB::commit();

            return back()->with('success', 'Session resetted');
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            throw $e;
        }
    }
}
