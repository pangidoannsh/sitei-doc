<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekJenisUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $jenis_user = null;
        if (Auth::guard('dosen')->check()) {
            $jenis_user = "dosen";
            $user_id = Auth::guard('dosen')->user()->nip;
        } elseif (Auth::guard('mahasiswa')->check()) {
            $jenis_user = "mahasiswa";
            $user_id = Auth::guard('mahasiswa')->user()->nim;
        } elseif (Auth::guard('web')->check()) {
            $jenis_user = "admin";
            if (Auth::guard("web")->user()->role_id == 12) {
                $jenis_user = "plp";
            }
            $user_id = Auth::guard('web')->user()->username;
        }
        if ($jenis_user !== null) {
            $request->merge(['jenis_user' => $jenis_user, 'user_id' => $user_id]);
            return $next($request);
        }
        return redirect()->route('login');
    }
}
