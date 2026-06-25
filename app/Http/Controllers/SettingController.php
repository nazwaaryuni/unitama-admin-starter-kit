<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('setting.index', [
            'title' => 'Setting',
            'setting' => Setting::first(),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        {
        $validated = $request->validate([
    'app_name'     => 'required|string|max:255',
    'copyright'    => 'required|string|max:255',
    'login_title'  => 'required|string|max:255',
    'keywords'     => 'nullable|string|max:255',
    'description'  => 'nullable|string',
    'logo'         => 'nullable|image|mimes:jpeg,png,jpg|max:1048',
], [
    'app_name.required'    => 'Nama aplikasi tidak boleh kosong.',
    'app_name.max'         => 'Nama aplikasi tidak boleh lebih dari :max karakter.',
    
    'copyright.required'   => 'Hak cipta (copyright) tidak boleh kosong.',
    'copyright.max'        => 'Hak cipta tidak boleh lebih dari :max karakter.',
    
    'login_title.required' => 'Judul login tidak boleh kosong.',
    'login_title.max'      => 'Judul login tidak boleh lebih dari :max karakter.',
    
    'keywords.max'         => 'Kata kunci (keywords) tidak boleh lebih dari :max karakter.',
    
    'logo.image'           => 'Berkas yang diunggah harus berupa gambar.',
    'logo.mimes'           => 'Format logo harus berupa JPEG, PNG, atau JPG.',
    'logo.max'             => 'Ukuran logo tidak boleh lebih dari :max kilobita (KB).',
]);

        DB::beginTransaction();
        try {

        if($request->file('logo')) {
            $validated['logo'] = $request->file('logo')->store('logo', 'public');
            if($setting->logo){
                Storage::disk('public')->delete($setting->logo);
            }
        }

            $setting->update($validated);
            DB::commit();
        return to_route('setting.index')->withSuccess('Data Berhasil Disimpan');
        } catch (\Exception$e) {
            DB::rollBack();
        return to_route('setting.index')->withError('Data Gagal Disimpan');
        }
    }
}
}