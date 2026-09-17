<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile()
    {
        $admin = Auth::user();

        return view('admin.profile.profile', compact('admin'));
    }

    public function updateProfile(Request $request, CloudinaryService $cloudinary)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'phone' => 'required|max:15',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png,webp,avif|max:2048',
        ]);

        // Update basic information
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update profile image if user selected one
        if ($request->hasFile('profile')) {
            /*
            |--------------------------------------------------------------------------
            | Upload new image to Cloudinary
            |--------------------------------------------------------------------------
            */

            $uploaded = $cloudinary->upload($request->file('profile'), 'techverse/profiles');

            /*
            |--------------------------------------------------------------------------
            | Delete old Cloudinary image
            |--------------------------------------------------------------------------
            */

            if ($admin->profile_public_id) {
                $cloudinary->delete($admin->profile_public_id);
            }

            /*
            |--------------------------------------------------------------------------
            | Save new Cloudinary information
            |--------------------------------------------------------------------------
            */

            $admin->update([
                'profile' => $uploaded['url'],
                'profile_public_id' => $uploaded['public_id'],
            ]);
        }

        return redirect()->route('admin#profile')->with('success', 'Profile Updated successfully!');
    }
}
