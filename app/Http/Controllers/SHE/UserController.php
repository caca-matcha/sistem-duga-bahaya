<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('npk', 'LIKE', "%{$search}%");
            });
        }

        // Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $stats = [
            'total' => $query->count(),
            'karyawan' => (clone $query)->where('role', 'karyawan')->count(),
            'she' => (clone $query)->where('role', 'she')->count(),
            'supervisor' => (clone $query)->where('role', 'supervisor')->count(),
            'magang' => (clone $query)->where('role', 'magang')->count(),
        ];

        $users = $query->orderByRaw("CASE WHEN role = 'magang' THEN 1 ELSE 0 END ASC")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('she.users.index', compact('users', 'stats'));
    }

    public function export(Request $request)
    {
        $query = User::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('npk', 'LIKE', "%{$search}%");
            });
        }

        // Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderByRaw("CASE WHEN role = 'magang' THEN 1 ELSE 0 END ASC")
            ->latest()
            ->get();

        $exportData = $users->map(function ($user) {
            return [
                'EMPLOYEE_NO' => $user->npk,
                'EMPLOYEE_NAME' => $user->name,
                'DIVISION' => $user->division,
                'DEPARTMENT' => $user->department,
                'ORGANIZATION_UNIT' => $user->organization_unit,
                'JOB_FAMILY' => $user->job_family,
                'POSITION' => $user->position,
                'ROLE' => $user->role,
                'EMAIL' => $user->email,
            ];
        });

        $jsonContent = json_encode(['data' => $exportData], JSON_PRETTY_PRINT);
        $fileName = 'users_export_'.now()->format('Ymd_His').'.json';

        return response($jsonContent)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('she.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'npk' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'position' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:karyawan,she,supervisor'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'npk' => $request->npk,
            'position' => $request->position,
            'division' => $request->division,
            'department' => $request->department,
            'organization_unit' => $request->organization_unit,
            'job_family' => $request->job_family,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('she.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('she.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'npk' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'position' => ['nullable', 'string', 'max:255'],
            'division' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'organization_unit' => ['nullable', 'string', 'max:255'],
            'job_family' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:karyawan,she,supervisor'],
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'npk' => $request->npk,
            'position' => $request->position,
            'division' => $request->division,
            'department' => $request->department,
            'organization_unit' => $request->organization_unit,
            'job_family' => $request->job_family,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('she.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === 1) {
            return redirect()->route('she.users.index')->with('error', 'Cannot delete the main administrator account.');
        }

        if (auth()->id() === $user->id) {
            return redirect()->route('she.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('she.users.index')->with('success', 'User deleted successfully.');
    }

    public function import(Request $request)
    {
        set_time_limit(300); // Increase time limit to 5 minutes

        $request->validate([
            'file' => 'required|file|mimes:json',
        ]);

        $file = $request->file('file');
        $jsonContent = file_get_contents($file->getRealPath());

        // Remove UTF-8 BOM if present
        $jsonContent = preg_replace('/^[\xEF\xBB\xBF]*/', '', $jsonContent);

        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Invalid JSON syntax: '.json_last_error_msg());
        }

        // If the JSON is a direct array of employees, wrap it in 'data'
        if (is_array($data) && ! isset($data['data']) && isset($data[0]['EMPLOYEE_NO'])) {
            $data = ['data' => $data];
        }

        if (! isset($data['data']) || ! is_array($data['data'])) {
            return redirect()->back()->with('error', 'Format JSON salah. Pastikan data karyawan berada di dalam array "data". Contoh: {"data": [...]}');
        }

        $count = 0;

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, &$count) {
            foreach ($data['data'] as $employee) {
                // Mapping JSON keys to DB columns
                // "EMPLOYEE_NO": "11011083", "EMPLOYEE_NAME": "Samuel", ...

                $npk = $employee['EMPLOYEE_NO'] ?? null;

                if (! $npk) {
                    continue;
                }

                $userData = [
                    'name' => $employee['EMPLOYEE_NAME'] ?? 'Unknown',
                    'division' => $employee['DIVISION'] ?? null,
                    'department' => $employee['DEPARTMENT'] ?? null,
                    'organization_unit' => $employee['ORGANIZATION_UNIT'] ?? null,
                    'job_family' => $employee['JOB_FAMILY'] ?? null,
                    'position' => $employee['POSITION'] ?? $employee['JOB_FAMILY'] ?? null,
                ];

                // Support optional ROLE column from JSON
                $roleFromJson = isset($employee['ROLE']) ? strtolower($employee['ROLE']) : null;
                $allowedRoles = ['karyawan', 'she', 'supervisor', 'magang'];

                // If user exists, update details.
                $user = User::where('npk', $npk)->first();

                if ($user) {
                    // Only update role if it's a valid role and the user isn't already an admin (safety)
                    if ($roleFromJson && in_array($roleFromJson, $allowedRoles)) {
                        $userData['role'] = $roleFromJson;
                    }
                    $user->update($userData);
                    $count++;
                } else {
                    $userData['npk'] = $npk;
                    $userData['password'] = Hash::make($npk);
                    $userData['role'] = ($roleFromJson && in_array($roleFromJson, $allowedRoles)) ? $roleFromJson : 'karyawan';
                    $userData['email'] = null; // No email in JSON
                    User::create($userData);
                    $count++;
                }
            }
        });

        return redirect()->route('she.users.index')->with('success', "Successfully imported/updated $count employees.");
    }
}
