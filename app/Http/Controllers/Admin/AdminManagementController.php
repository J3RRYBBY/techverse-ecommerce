<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
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

    public function editAdmin($id)
    {
        // Only Super Admin can edit other admins
        if (Auth::user()->role !== 'superadmin') {
            abort(403);
        }

        $admin = User::findOrFail($id);

        return view('admin.adminManagement.editAdmin', compact('admin'));
    }

    public function updateAdmin(Request $request, int $id)
    {
        // Only Super Admin can update admins
        if (Auth::user()->role !== 'superadmin') {
            abort(403);
        }

        $admin = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',

            'phone' => 'required|string|max:20',

            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,

            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->phone = $request->phone;
        $admin->email = $request->email;

        // Only change password if Super Admin entered a new password
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin#management')->with('updateSuccess', 'Admin account updated successfully.');
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
