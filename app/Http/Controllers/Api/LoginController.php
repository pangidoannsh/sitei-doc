<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (Auth::guard('dosen')->attempt(['nip' => $request->username, 'password' => $request->password])) {
                $user = Dosen::where('nip', $request->username)->first();
            } elseif (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
                $user = User::where('username', $request->username)->first();
            } elseif (Auth::guard('mahasiswa')->attempt(['nim' => $request->username, 'password' => $request->password])) {
                $user = Mahasiswa::where('nim', $request->username)->first();
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'errors' => 'email atau password salah!',
                    'message' => 'Gagal login',
                ], 400);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $user->createToken('token')->plainTextToken,
                ],
                'errors' => null,
                'message' => 'Pengguna berhasil login!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'errors' => $e->getMessage(),
                'message' => 'Terdapat kesalahan pada Api/AuthController.login!'
            ], 500);
        }
    }

    function profile(Request $request)
    {
        try {
            $data = $request->user();
            return response()->json([
                'status' => true,
                'data' => new UserResource($data),
                'errors' => null,
                'message' => 'Profile pengguna berhasil ditampilkan!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'errors' => $e->getMessage(),
                'message' => "Terdapat kesalahan pada Api/AuthController.profile!",
            ], 500);
        }
    }

    function mhs()
    {
        return response()->json(Mahasiswa::all());
    }
}
