<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Show the form for editing the settings.
     */
    public function edit()
    {
        // Get all settings grouped by key
        $settings = Setting::pluck('value', 'key_name')->toArray();
        
        return view('settings.edit', compact('settings'));
    }

    /**
     * Update the specified settings in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'company_postal_city' => 'required|string|max:255',
            'company_phone' => 'required|string|max:50',
            'company_email' => 'required|email|max:100',
            'company_website' => 'nullable|string|max:100',
            'company_kvk' => 'required|string|max:50',
            'company_btw' => 'required|string|max:50',
            'bank_name' => 'required|string|max:100',
            'bank_iban' => 'required|string|max:50',
            'default_payment_days' => 'required|integer|min:1|max:90',
            'default_btw_percentage' => 'required|numeric|min:0|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoName = 'logo.' . $logoFile->getClientOriginalExtension();
            
            // Store in public storage
            $path = $logoFile->storeAs('', $logoName, 'public');
            
            // Save logo path
            Setting::setValue('logo_path', $path);
        }

        // Save other settings
        $settingsToUpdate = [
            'company_name',
            'company_address',
            'company_postal_city',
            'company_phone',
            'company_email',
            'company_website',
            'company_kvk',
            'company_btw',
            'bank_name',
            'bank_iban',
            'default_payment_days',
            'default_btw_percentage',
        ];

        foreach ($settingsToUpdate as $key) {
            if ($request->has($key)) {
                Setting::setValue($key, $request->input($key));
            }
        }

        return redirect()->route('settings.edit')
            ->with('success', 'Settings updated successfully.');
    }
}
