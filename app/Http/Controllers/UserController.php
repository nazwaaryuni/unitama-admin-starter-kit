<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index', [
            'title' => 'User',
            'users' => User::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create', [
            'title' => 'Tambah Data User',
        ]);
    }

    /**l
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'name'              => 'required|string|max:255',
        'email'             => 'required|string|email|max:255|unique:users,email',
        'password'          => 'required|string|min:8',
        'passwordconfirm'   => 'required|same:password',
        'role'              => 'required|in:Super Admin,Admin',
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
        'avatar.image'              => 'Berkas Yang Diunggah Harus Berupa Warna.',
        'avatar.mimes'              => 'Format Gambar Harus JPEG, PNG, atau JPG.',
        'avatar.max'                => 'Ukuran Gambar Tidak Boleh Lebih dari :max Kilobitan',
        'role.required'             => 'Role Harus Dipilih.',
        'role.in'                   => 'Role Yang Dipilih Tidak Valid',
    ]);
        DB::beginTransaction();
        try {

        if($request->file('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatar', 'public');
        }

        $validated['password'] = bcrypt($request->password);
        $validated['email_verified_at'] = now();

            
            User::create($validated);
            DB::commit();
        return to_route('user.index')->withSuccess('Data Berhasil Ditambahkan');
        } catch (\Exception$e) {
            DB::rollBack();
        return to_route('user.create')->withError('Data Gagal Ditambahkan');          
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('user.show', [
            'title' => 'Detail Data User',
            'user' => $user, 
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.edit', [
            'title' => 'Edit Data User',
            'user' => $user, 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
        'name'              => 'required|string|max:255',
        'email'             => 'required|string|email|max:255|unique:users,email,' .$user->id,
        'password'          => 'nullable|string|min:8',
        'passwordconfirm'   => 'nullable|same:password',
        'role'              => 'required|in:Super Admin,Admin',
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
        'avatar.image'              => 'Berkas Yang Diunggah Harus Berupa Warna.',
        'avatar.mimes'              => 'Format Gambar Harus JPEG, PNG, atau JPG.',
        'avatar.max'                => 'Ukuran Gambar Tidak Boleh Lebih dari :max Kilobitan',
        'role.required'             => 'Role Harus Dipilih.',
        'role.in'                   => 'Role Yang Dipilih Tidak Valid',
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
        return to_route('user.index')->withSuccess('Data Berhasil Diubah');
        } catch (\Exception$e) {
            DB::rollBack();
        return to_route('user.edit', $user)->withError('Data Gagal Diubah');          
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try{
        $user->delete();
        if($user->avatar){
                Storage::disk('public')->delete($user->avatar);
            }
            DB::commit();
        return to_route('user.index')->withSuccess('Data Berhasil Dihapus');
        } catch (\Exception$e) {
            DB::rollBack();
        return to_route('user.edit')->withError('Data Gagal Dihapus');   
    }
}
}