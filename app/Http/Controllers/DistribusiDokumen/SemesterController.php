<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        return view('semester.index', [
            'semesters' => Semester::all()
        ]);
    }
    public function create()
    {
        return view('semester.create');
    }

    public function store(Request $request)
    {
        Semester::create([
            'semester' => $request->semester,
            'tahun_ajaran' => $request->tahun_ajaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('semester');
    }
    public function edit($id)
    {
        return view('semester.edit', [
            "data" => Semester::findOrFail($id)
        ]);
    }
    public function update($id, Request $request)
    {
        $data = Semester::findOrFail($id);
        $data->semester = $request->semester;
        $data->tahun_ajaran = $request->tahun_ajaran;
        $data->tanggal_mulai = $request->tanggal_mulai;
        $data->tanggal_selesai = $request->tanggal_selesai;

        $data->update();

        return redirect()->route("semester");
    }

    public function delete($id)
    {
        Semester::find($id)->delete();
        return redirect()->route("semester");
    }
}
