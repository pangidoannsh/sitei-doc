<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Logo;
use App\Models\DistribusiDokumen\SertifikatLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class LogoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('doc.logo.index', [
            'logos' => Logo::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('doc.logo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $logoImage = $request->file('logo');
        Logo::create([
            "nama" => $request->nama,
            "url" => str_replace('public/', '', $logoImage->store('public/logo')),
            "is_mandatory" => $request->has('is_mandatory'),
            "position" => $request->position,
        ]);

        Alert::success('Berhasil!', 'Berhasil membuat logo baru')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('logo');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('doc.logo.edit', [
            'data' => Logo::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Logo::findOrFail($id);

        $logoImage = $request->file('logo');
        if ($logoImage) {
            if (Storage::exists("public/" . $data->url)) {
                Storage::delete("public/" . $data->url);
            }
            $data->url = str_replace('public/', '', $logoImage->store('public/logo'));
        }
        $data->nama = $request->nama;
        $data->position = $request->position;


        $data->is_mandatory = $request->has('is_mandatory');

        $data->update();
        Alert::success('Berhasil!', 'Berhasil mengubah logo')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('logo');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $data = Logo::findOrFail($id);
        if (Storage::exists("public/" . $data->url)) {
            Storage::delete("public/" . $data->url);
        }
        SertifikatLogo::where("logo_id", $id)->delete();
        $data->delete();
        Alert::success('Berhasil!', 'Berhasil menghapus logo')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('logo');
    }
}
