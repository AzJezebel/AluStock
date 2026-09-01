<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $groups = Settings::select('group')->distinct()->get();
        $settings = [];

        foreach ($groups as $group) {
            $settings[$group->group] = Settings::where('group', $group->group)->get();
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Settings::set($key, $value);
        }

        return redirect()->back()->with('success', 'Paramètres mis à jour avec succès.');
    }

    public function get($key)
    {
        return response()->json([
            'success' => true,
            'value' => Settings::get($key)
        ]);
    }
}