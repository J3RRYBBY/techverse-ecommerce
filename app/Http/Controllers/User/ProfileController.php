<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        return view('user.profile.profile', compact('user'));
    }

    public function updateProfile(Request $request, CloudinaryService $cloudinary)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|max:15',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'address' => 'nullable|string|max:255',
        ]);

        // Update basic information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Update profile image
        if ($request->hasFile('profile')) {
            // Upload new image to Cloudinary
            $uploaded = $cloudinary->upload($request->file('profile'), 'techverse/profiles');

            // Delete old Cloudinary image
            if ($user->profile_public_id) {
                $cloudinary->delete($user->profile_public_id);
            }

            // Save new Cloudinary URL and public ID
            $user->update([
                'profile' => $uploaded['url'],
                'profile_public_id' => $uploaded['public_id'],
            ]);
        }

        return redirect()->route('user#profile')->with('success', 'Profile Updated successfully!');
    }
}
