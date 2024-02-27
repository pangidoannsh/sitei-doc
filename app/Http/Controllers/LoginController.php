<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function postlogin(Request $request)
    {
        if (Auth::guard('dosen')->attempt(['nip' => $request->username, 'password' => $request->password])) {
            $user = Auth::guard('dosen')->user();
            return redirect()->route('pengumuman');
            // if ($user->role_id == 5) {
            //     return redirect('/persetujuan-kp-skripsi');
            // }elseif ($user->role_id == 6) {
            //     return redirect('persetujuan-kp-skripsi');
            // }elseif ($user->role_id == 7) {
            //     return redirect('persetujuan-kp-skripsi');
            // }elseif ($user->role_id == 8) {
            //     return redirect('persetujuan-kp-skripsi');
            // }elseif ($user->role_id == 9) {
            //     return redirect('persetujuan-kp-skripsi');
            // }elseif ($user->role_id == 10) {
            //     return redirect('persetujuan-kp-skripsi');
            // }elseif ($user->role_id == 11) {
            //     return redirect('persetujuan-kp-skripsi');
            // }else {
            //     return redirect('/persetujuan-kp-skripsi');
            // }

        } elseif (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::guard('web')->user();
            return redirect()->route('pengumuman');

            // if ($user->role_id == 1) {
            //     return redirect('/form');
            // } elseif ($user->role_id == 12) {
            //     return redirect('/inventaris/peminjaman-plp');
            // } else {
            //     return redirect('/persetujuan/admin/index');
            // }
        } elseif (Auth::guard('mahasiswa')->attempt(['nim' => $request->username, 'password' => $request->password])) {
            // return redirect('/kp-skripsi');
            return redirect()->route('pengumuman');
        }


        Alert::error('Login Gagal!', 'Silahkan masukan NIP/NIM dan Password yang benar.')->showConfirmButton('Ok', '#dc3545');
        //  return redirect('/')->with('loginError', 'Login Gagal!');
        return back()->with('loginError', 'Login Gagal!');
    }

    public function logout()
    {
        if (Auth::guard('dosen')->check()) {
            Auth::guard('dosen')->logout();
        } elseif (Auth::guard('mahasiswa')->check()) {
            Auth::guard('mahasiswa')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        return redirect('/');
    }
}
