<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;

use App\Models\Activity_Admin;
use App\Models\User;
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

            $timeZone = new CarbonTimeZone('Asia/Jakarta');
            $dateNow = Carbon::now($timeZone);

            // Melakukan otentikasi
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Periksa hak akses
                if ($user->id_hakakses === 1) {
                    $request->session()->regenerate();
                    Activity_Admin::create([
                        'nama' => $user->nama,
                        'keterangan' => 'Login',
                        'no_transaksi' => 'Login',
                        'tanggal' => $dateNow
                    ]);
                    return redirect()->intended('admin/dashboard');
                } elseif ($user->id_hakakses === 2) {
                    $request->session()->regenerate();
                    return redirect()->intended('superadmin/dashboard');
                }
            }

            return redirect('/')->with('error', 'Login Gagal. Coba Kembali');
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Terjadi kesalahan. Silakan coba kembali.');
        }
    }

    function registrasi()
    {
        return view('Auth.registrasi', [
            'title' => 'Daftar Akun'
        ]);
    }

    function createUser(Request $request)
    {
        try {
            $credentials = $request->validate([
                'nama' => 'required',
                'email' => 'required|email|unique:users',
                'no_hp' => 'required|unique:users|min:11|numeric',
                'password' => 'required|min:8',
                'password1' => 'required|same:password'
            ]);

            $credentials['id_hakakses'] = 2;
            $credentials['password'] = bcrypt($credentials['password']);

            $user = User::create($credentials);
            return redirect('/')->with('success', 'Daftar Akun Berhasil!');
        } catch (Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', 'Daftar Akun Gagal. Isi Form Pendaftaran Dengan Benar!!');
        }
    }

    function forgotpassword()
    {
        return view('auth.forgot-password', [
            'title' => 'Lupa Password'
        ]);
    }

    function logout()
    {
        $timeZone = new CarbonTimeZone('Asia/Jakarta');
        $dateNow = Carbon::now($timeZone);
        // Activity_Admin::create([
        //     'nama' => Auth::user()->nama,
        //     'keterangan' => 'Log Out',
        //     'no_transaksi' => 'Log Out',
        //     'tanggal' => $dateNow
        // ]);

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }
}
