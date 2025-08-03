<?php

namespace App\Http\Controllers\GeneralSetting;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Group;

// Add this at the top


class GeneralSettingController extends Controller
{
    // Display all settings
    public function edit()
    {
        $settings = GeneralSetting::all();

        $groups = Group::all(); // Get list of all groups

        return view('general_settings.edit', compact('settings', 'groups'));
    }

    // Store new setting
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'account_number' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        // Prevent duplicates per group
        if (GeneralSetting::where('group_id', $request->group_id)->exists()) {
            return back()->with('error', 'Setting for this group already exists.');
        }

        $data = GeneralSetting::create([
            'group_id' => $request->group_id,
//            'account_number' => $request->account_number,
            'message' => $request->message,
//            'bank_name' => $request->bank_name,
//            'iban' => $request->iban,
        ]);
        if (!empty($request->bank_name)) {


            foreach ($request->bank_name as $key => $value) {
                Bank::create([
                    'general_setting_id' => $data['id'],
                    'bank_name' => $value,
                    'iban' => $request['iban'][$key],

                ]);
            }
        }

        return redirect()->route('general_settings.edit')->with('success', 'Setting created successfully.');
    }

    // Update existing setting
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:general_settings,id',
            'group_id' => 'required|exists:groups,id',
            'account_number' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        $setting = GeneralSetting::findOrFail($request->id);
        $setting->group_id = $request->group_id;
//        $setting->account_number = $request->account_number;
        $setting->message = $request->message;
//        $setting->bank_name = $request->bank_name;
//        $setting->iban = $request->iban;
        $setting->save();
        if (!empty($request->bank_name)) {
            Bank::where('general_setting_id', $request->id)->delete();
            foreach ($request->bank_name as $key => $value) {
                Bank::create([
                    'general_setting_id' => $request['id'],
                    'bank_name' => $value,
                    'iban' => $request['iban'][$key],

                ]);
            }
        }


        return redirect()->route('general_settings.edit')->with('success', 'Setting updated successfully.');
    }

    // Delete setting
    public function delete($id)
    {
        Bank::where('general_setting_id', $id)->delete();
        $setting = GeneralSetting::findOrFail($id);
        $setting->delete();

        return redirect()->route('general_settings.edit')->with('success', 'Setting deleted successfully.');
    }
}
