<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::select('id', 'name', 'is_active')
            ->withCount([
                'patrolLocations' => function ($query) {
                    $query->where('is_active', 1);
                },
                'checkpoints' => function ($query) {
                    $query->where('is_active', 1);
                }
            ])
            ->get()
            ->toArray();

        return Inertia::render('Company/Index', [
            'title' => "Companies",
            'companies' => $companies
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'unique:companies,name'],
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ], [
                'name.required' => 'Company name is required',
                'name.unique' => 'Company name already exists',
                'image.max' => 'Image size must not exceed 2MB',
                'image.required' => 'Company logo is required',
                'image.image' => 'Company logo must be an image',
                'image.mimes' => 'Company logo must be a jpeg, png, or jpg file',
            ]);

            $imageName = pathinfo($validated['image']->getClientOriginalName(), PATHINFO_FILENAME) . '_' . uniqid() . '.' . $validated['image']->extension();
            // Define the path relative to the root of the app (ensure the folder exists and has write permissions)
            $imagePath = base_path('images/company'); // This will resolve to /path/to/your/app/images/company

            // Check if the directory exists, and create it if not
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            // Move the file to the target directory
            $validated['image']->move($imagePath, $imageName);

            Company::create([
                ...$validated,
                'photo' => $imageName
            ]);

            return redirect()->back()->with('success', [
                'message' => "Company with name {$validated['name']} created successfully!",
                'id' => uniqid()
            ]);
        } catch (Throwable $th) {
            dd($th);
            Log::error($th);
            return redirect()->back()->with('error', [
                'message' => 'Failed to create company',
                'id' => uniqid()
            ]);
        }
    }

    public function update(Request $request, Company $companyBind)
    {
        try {
            $validated = $request->validate([
                'name' => ['nullable', Rule::unique('companies', 'name')->ignore($companyBind->id)],
                'status' => ['nullable', 'in:0,1'],
            ]);

            $companyBind->update([
                ...$validated,
                'is_active' => $validated['status'] ?? $companyBind->is_active
            ]);

            return redirect()->back()->with('success', [
                'message' => 'Company updated successfully!',
                'id' => uniqid()
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', [
                'message' => $th->getMessage() ?? 'Failed to update company',
                'id' => uniqid()
            ]);
        }
    }
}
