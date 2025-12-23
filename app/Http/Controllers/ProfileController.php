<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Custom validation: if password is filled, current_password is required
        if ($request->filled('password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
            ]);
        }
        
        $data = $request->validated();

        // Remove current_password field (not a model field)
        unset($data['current_password']);
        
        // Remove empty password from data
        if (!$request->filled('password')) {
            unset($data['password']);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile')) {
            try {
                $path = $request->file('profile')->store('profiles', 'public');
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $data['profile_photo_path'] = $path;
            } catch (\Exception $e) {
                return Redirect::back()->withErrors(['profile' => 'Gagal mengunggah foto profil: ' . $e->getMessage()]);
            }
        }
        
        // Remove 'profile' file input from data
        unset($data['profile']);

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
