<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class CompanyController extends Controller
{
    public function index()
    {
        $title = 'Companies';

        $get = Company::all();

        return Inertia::render('Company/Index', [
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
                'name' => ['required', 'unique:companies,name'],
            ]);

            Company::create([
                'name' => $request->name
            ]);

            DB::commit();

            return back()->with('success', 'New company created');
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            throw $e;
        }
    }
}
