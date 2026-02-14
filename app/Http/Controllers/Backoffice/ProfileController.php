<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth('backoffice')->user();
        $agency = $user->agency;

        return view('Backoffice.profile.profile-setting', [
            'agency' => $agency,
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => $request->password,
            ]);
        }

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatar');

            $user->addMediaFromRequest('avatar')
                ->toMediaCollection('avatar'); // ✅ disk sera pris depuis la collection
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the change password form
     */
    public function showChangePassword()
    {
        $user = auth('backoffice')->user();
        $agency = $user->agency;

        return view('Backoffice.profile.change-password', [
            'agency' => $agency,
        ]);
    }

    /**
     * Update the user's password with timestamp tracking
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password:backoffice'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = auth('backoffice')->user();

        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
