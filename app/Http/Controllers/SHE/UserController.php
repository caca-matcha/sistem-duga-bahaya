<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('she.users.index', compact('users'));
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
'role' => ['required', 'string', 'in:karyawan,she,supervisor'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
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
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:karyawan,she,supervisor'],
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
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
        $data = json_decode($jsonContent, true);

        if (!isset($data['data']) || !is_array($data['data'])) {
            return redirect()->back()->with('error', 'Invalid JSON format. Expected "data" array.');
        }

        $count = 0;
        
        \Illuminate\Support\Facades\DB::transaction(function () use ($data, &$count) {
            foreach ($data['data'] as $employee) {
                // Mapping JSON keys to DB columns
                // "EMPLOYEE_NO": "11011083", "EMPLOYEE_NAME": "Samuel", ...
                
                $npk = $employee['EMPLOYEE_NO'] ?? null;
                
                if (!$npk) continue;

                $userData = [
                    'name' => $employee['EMPLOYEE_NAME'] ?? 'Unknown',
                    'division' => $employee['DIVISION'] ?? null,
                    'department' => $employee['DEPARTMENT'] ?? null,
                    'organization_unit' => $employee['ORGANIZATION_UNIT'] ?? null,
                    'job_family' => $employee['JOB_FAMILY'] ?? null,
                    // Default password is NPK, role defaults to employee (karyawan)? Or current role if exists?
                    // Request says: "password nya default npk untuk awal awal"
                ];

                // If user exists, update details. If not, create.
                // But we must be careful not to overwrite EXISTING users' roles if they are admins/she.
                // We use updateOrCreate.
                
                $user = User::where('npk', $npk)->first();

                if ($user) {
                    $user->update($userData);
                    $count++;
                } else {
                    $userData['npk'] = $npk;
                    $userData['password'] = Hash::make($npk);
                    $userData['role'] = 'karyawan'; // Default role
                    $userData['email'] = null; // No email in JSON
                    User::create($userData);
                    $count++;
                }
            }
        });

        return redirect()->route('she.users.index')->with('success', "Successfully imported/updated $count employees.");
    }
}
