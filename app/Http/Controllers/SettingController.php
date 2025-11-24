<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $autoGenerateBA = Setting::getValue('auto_generate_ba', '1');
        return view('settings.index', compact('autoGenerateBA'));
    }

    public function update(Request $request)
    {
        // Jika checkbox dicentang kirim '1', jika tidak ada kirim '0'
        $value = $request->has('auto_generate_ba') ? '1' : '0';

        Setting::updateOrCreate(
            ['key' => 'auto_generate_ba'],
            ['value' => $value]
        );

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}