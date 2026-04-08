<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('agent')->user();
        $currencies = \App\Models\Currency::getActive();
        return view('agent.profile.index', compact('agent', 'currencies'));
    }

    public function update(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'currency_code' => 'nullable|string|size:3|exists:currencies,code',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($agent->profile_photo) {
                Storage::disk('public')->delete($agent->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('agent-photos', 'public');
        }

        $agent->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function password()
    {
        return view('agent.profile.password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $agent = Auth::guard('agent')->user();

        if (!Hash::check($validated['current_password'], $agent->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $agent->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    public function notifications()
    {
        $agent = Auth::guard('agent')->user();
        
        $notifications = Notification::where('notifiable_type', 'App\Models\Agent')
            ->where('notifiable_id', $agent->id)
            ->latest()
            ->paginate(20);

        return view('agent.profile.notifications', compact('notifications'));
    }
}
