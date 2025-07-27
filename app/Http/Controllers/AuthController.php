<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use App\Models\Jabatan;
use Exception;

class AuthController extends Controller
{
    function login()
    {
        return view('auth.login', [
            'title' => 'Masuk'
        ]);
    }

    function authentication(Request $request): RedirectResponse
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $request->session()->regenerate();

                if ($user->id_hakakses === 1) {
                    return redirect()->intended('admin/dashboard');
                } elseif ($user->id_hakakses === 2) {
                    return redirect()->intended('karyawan/dashboard');
                } else {
                    return redirect()->intended('superadmin/dashboard');
                }
            }

            return back()->with('error', 'Login Gagal. Email atau Password salah.');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan. Silakan coba kembali.');
        }
    }

    function registrasi()
    {
        $jabatan = Jabatan::all();
        return view('auth.registrasi', [
            'title' => 'Daftar Akun',
            'jabatan' => $jabatan
        ]);
    }

    function createUser(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'no_hp' => 'required|string|min:10|max:15',
                'email' => 'required|email|unique:users,email',
                'id_jabatan' => 'required|exists:jabatan,id',
                'password' => 'required|min:8|confirmed',
            ]);

            $jabatan = Jabatan::find($validatedData['id_jabatan']);

            User::create([
                'id' => $this->generateUserId(),
                'nama' => $validatedData['nama'],
                'alamat' => $validatedData['alamat'],
                'no_hp' => $validatedData['no_hp'],
                'email' => $validatedData['email'],
                'id_jabatan' => $validatedData['id_jabatan'],
                'id_hakakses' => $validatedData['id_jabatan'], // Asumsi id_hakakses 1 untuk admin, 2 untuk karyawan
                'password' => bcrypt($validatedData['password']),
                'status_karyawan' => 'Aktif',
            ]);

            return redirect('/')->with('success', 'Daftar Akun Berhasil! Silakan Login.');
        } catch (ValidationException $e) {
            // Kembali dengan error validasi spesifik untuk UX yang lebih baik
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            // Kembali dengan pesan error yang lebih umum untuk keamanan
            return back()->with('error', 'Daftar Akun Gagal. Pastikan semua data terisi dengan benar.')->withInput();
        }
    }

    private function generateUserId()
    {
        $lastUser = User::orderBy('id', 'desc')->first();
        $lastId = $lastUser ? intval(substr($lastUser->id, 1)) : 0;
        return 'U' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }


    function forgotpassword()
    {
        return view('auth.forgot-password', [
            'title' => 'Lupa Password'
        ]);
    }

    function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }
}
