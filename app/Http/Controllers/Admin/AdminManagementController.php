<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminManagementController extends Controller
{
    public function manageAdmin()
    {
        $admins = User::whereIn('role', ['superadmin', 'admin'])->get();

        $adminCount = User::whereIn('role', ['superadmin', 'admin'])->count();

        return view('admin.adminManagement.adminList', compact('admins', 'adminCount'));
    }

    public function addAdminPage()
    {
        return view('admin.adminManagement.addAdmin');
    }

    public function addAdmin(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => 'required',
            'phone' => 'required|max:15',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'admin',
        ]);

        return redirect()->route('admin#management')->with('success', 'Admin added successfully!');
    }

    public function deleteAdmin(int $id)
    {
        $admin = User::findOrFail($id);

        if ($admin->role === 'superadmin') {
            return back()->with('error', 'You cannot delete a superadmin.');
        }

        $admin->delete();

        return back()->with('success', 'Admin deleted successfully.');
    }
}
