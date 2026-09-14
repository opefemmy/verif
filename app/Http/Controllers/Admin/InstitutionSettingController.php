<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstitutionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstitutionSettingController extends Controller
{
    public function edit()
    {
        $settings = InstitutionSetting::first() ?? new InstitutionSetting();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:50',
            'motto' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url',
            'official_email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'verification_title' => 'nullable|string|max:255',
            'verification_footer' => 'nullable|string',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg,ico|max:512',
        ]);

        $settings = InstitutionSetting::first() ?? new InstitutionSetting();

        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('institution', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('institution', 'public');
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->back()->with('success', 'Institution settings updated successfully.');
    }
}