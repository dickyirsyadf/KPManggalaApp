<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $users = User::all();
        $absensi = Absensi::with('user')->get();
        $data = ['menu'=>'Absensi'];

        return view('admin.absensi', compact('users', 'absensi','data'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'id_karyawan' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);
        Absensi::create([
            'id_karyawan' => $request->id_karyawan,
            'tanggal' => $request->date ?? Carbon::today()->format('Y-m-d'),
            'kehadiran' => $request->has('kehadiran') ? 1 : 0,
        ]);
        return redirect()->route('absensi.index')->with('success', 'Absensi saved successfully!');
    }
        public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:absensis,id',
            'tanggal' => 'required|date',
            'kehadiran' => 'required|boolean',
            'admin_password' => 'required',
        ]);

        // Find admin user
        $admin = User::where('id_hakakses', 1)->first(); // Assuming '1' is the admin role ID

        // Validate the admin password
        if (!$admin || !Hash::check($request->admin_password, $admin->password)) {
            return redirect()->back()->withErrors(['admin_password' => 'Invalid admin password.']);
        }

        // Update absensi record
        $absensi = Absensi::findOrFail($request->id);
        $absensi->tanggal = $request->tanggal;
        $absensi->kehadiran = $request->kehadiran;
        $absensi->save();

        return redirect()->route('absensi.index')->with('success', 'Absensi updated successfully!');
    }

}
