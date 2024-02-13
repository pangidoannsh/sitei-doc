<?php

use App\Http\Controllers\DistribusiDokumen\DistribusiDokumenController;
use App\Http\Controllers\DistribusiDokumen\PengumumanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/mahasiswa", [DistribusiDokumenController::class, 'mahasiswa']);
