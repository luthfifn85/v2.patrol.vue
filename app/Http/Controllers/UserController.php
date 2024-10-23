<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    public function index()
    {
        $title = 'User';

        $get = User::with('role:id,name')
            ->get();

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
            $request->validate([
                'role_id' => ['required'],
                'name' => ['required'],
                'email' => ['required', 'unique:users,email'],
                'password' => ['required', 'min:8']
            ]);

            User::create([
                'role_id' => $request->role_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            DB::commit();

            return back()->with('successs', 'New user created');
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            throw $e;
        }
    }

    public function update(Request $request, User $get)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'name' => ['required']
            ]);

            $get->update([
                'name' => $request->name
            ]);

            DB::commit();

            return back()->with('success', 'Name updated');
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            throw $e;
        }
    }

    public function password(Request $request, User $get)
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
}
