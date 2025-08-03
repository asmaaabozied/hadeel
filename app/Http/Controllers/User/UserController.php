<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Sheet;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{


    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }


    public function store(Request $request, Group $group)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|unique:users,phone',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($e->validator->errors()->has('phone')) {
                return back()->with('error', 'This phone number is already registered.')->withInput();
            }

            throw $e; // Let other validation errors bubble normally
        }

        // Generate a 4-digit unique identifier
        do {
            $identifier = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (\App\Models\User::where('unique_identifier', $identifier)->exists());

        $user = \App\Models\User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'unique_identifier' => $identifier,
        ]);

        $group->users()->syncWithoutDetaching([$user->id]);

        foreach ($group->sheets as $sheet) {
            $sheet->users()->syncWithoutDetaching([
                $user->id => [
                    'production' => 0,
                    'admin_id' => auth()->id(),
                    'consumption' => 0,
                    'note' => null,
                ]
            ]);
        }

        return back()->with('success', 'User added to group and assigned to all sheets.');


        // return redirect()->route('users.index')->with('success', 'User added successfully.');
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    //Admin's

    public function index()
    {
        $users = User::where('admin', 1)->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function createAdmin()
    {
        return view('admins.create');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Generate a 6-digit unique identifier
        do {
            $identifier = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (User::where('unique_identifier', $identifier)->exists());

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'admin' => 1,
            'unique_identifier' => $identifier,
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
    }


    public function showAdmin(User $admin)
    {
        abort_unless($admin->admin, 404);
        return view('users.show', ['user' => $admin]);
    }

    public function editAdmin(User $admin)
    {
        abort_unless($admin->admin, 404);
        return view('admins.edit', compact('admin'));
    }

    public function updateAdmin(Request $request, User $admin)
    {
        abort_unless($admin->admin, 404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->admin = 1; // ensure admin stays true

        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }

        $admin->save();

        return redirect()->route('admins.index')->with('success', 'Admin updated successfully.');
    }


}
