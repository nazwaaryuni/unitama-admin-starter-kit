<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.index', [
            'title' => 'Dashboard',
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        return view('dashboard.show', [
            'title' => 'Detail Data User',
            'user' => Auth::user(), 
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('dashboard.edit', [
            'title' => 'Edit Data User',
            'user' => Auth::user(), 
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
        'name'              => 'required|string|max:255',
        'email'             => 'required|string|email|max:255|unique:users,email,' .$user->id,
        'password'          => 'nullable|string|min:8',
        'passwordconfirm'   => 'nullable|same:password',
        'avatar'            => 'nullable|image|mimes:jpeg,png,jpg|max:1048',
    ], [
        'name.required'             => 'Nama Tidak Boleh Kosong.',
        'name.max'                  => 'Nama Tidak Boleh Lebih Dari :max Karakter.',
        'email.required'            => 'Email Tidak Boleh Kosong.',
        'email.email'               => 'Format Email Tidak Valid.',
        'email.unique'              => 'Email Sudah Terdaftar.',
        'password.required'         => 'Password Tidak Boleh Kosong.',
        'password.min'              => 'Password Minimal Harus :min Karakter',
        'passwordconfirm.required'  => 'Konfirmasi Password Tidak Boleh Kosong.',
        'passwordconfirm.same'      => 'Konfirmasi Password Tidak Cocok.',
        'avatar.image'              => 'Berkas Yang Diunggah Harus Berupa Gambar.',
        'avatar.mimes'              => 'Format Gambar Harus JPEG, PNG, atau JPG.',
        'avatar.max'                => 'Ukuran Gambar Tidak Boleh Lebih dari :max Kilobita',
    ]);

        DB::beginTransaction();
        try {

        if($request->file('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatar', 'public');
            if($user->avatar){
                Storage::disk('public')->delete($user->avatar);
            }
        }

        if($request->password){
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

            $user->update($validated);
            DB::commit();
        return to_route('dashboard.show')->withSuccess('Data Berhasil Diubah');
        } catch (\Exception$e) {
            DB::rollBack();
        return to_route('dashboard.edit')->withError('Data Gagal Diubah');          
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
