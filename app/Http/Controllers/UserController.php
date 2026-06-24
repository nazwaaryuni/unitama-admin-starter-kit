<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:8',
        'passwordconfirm' => 'required|same:password',
        'role' => 'required|in:Super Admin,Admin',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:1048',
    ], [
        'name.required' => 'Nama Tidak Boleh Kosong.',
        'email.required' => 'Email Tidak Boleh Kosong.',
        'email.email' => 'Format Email Tidak Valid.',
        'email.unique' => 'Email Sudah Terdaftar.',
        'password.required' => 'Password Tidak Boleh Kosong.',
        'password.min' => 'Password Minimal 8 Karakter.',
        'passwordconfirm.required' => 'Konfirmasi Password Tidak Boleh Kosong.',
        'passwordconfirm.same' => 'Konfirmasi Password Tidak Cocok.',
        'role.required' => 'Role Harus Dipilih.',
        'role.in' => 'Role Harus Berupa Super Admin atau Admin.',
    ]);

        try {

        if($request->file('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatar', 'public');
        }
            DB::beginTransaction();
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
