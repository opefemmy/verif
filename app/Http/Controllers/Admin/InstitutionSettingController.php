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
            'seal' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
            'registrar_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'rector_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
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

        if ($request->hasFile('seal')) {
            if ($settings->seal) {
                Storage::disk('public')->delete($settings->seal);
            }
            $validated['seal'] = $request->file('seal')->store('institution', 'public');
        }

        if ($request->hasFile('registrar_signature')) {
            if ($settings->registrar_signature) {
                Storage::disk('public')->delete($settings->registrar_signature);
            }
            $validated['registrar_signature'] = $request->file('registrar_signature')->store('institution', 'public');
        }

        if ($request->hasFile('rector_signature')) {
            if ($settings->rector_signature) {
                Storage::disk('public')->delete($settings->rector_signature);
            }
            $validated['rector_signature'] = $request->file('rector_signature')->store('institution', 'public');
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->back()->with('success', 'Institution settings updated successfully.');
    }
}