<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\SuratCuti;
use App\Models\Dosen;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratCutiController extends Controller
{
    private $jenis_cuti = ['tahunan', 'besar', 'sakit', 'melahirkan', 'kepentingan'];

    public function create(Request $request)
    {
        $jenis_user = $request->jenis_user;
        $jenis_cuti = ['tahunan', 'besar', 'sakit', 'melahirkan', 'kepentingan'];
        $jenis_user = $request->jenis_user;

        $jabatan = null;
        $role = Role::where('id', Auth::guard($request->jenis_user == 'admin' ? 'web' : $request->jenis_user)->user()->role_id)->first();
        if ($role) {
            $jabatan = $role->role_akses;
        }

        return view("doc.suratcuti.create", compact('jenis_cuti', 'jenis_user', 'jabatan'));
    }
    public function store(Request $request)
    {
        // Validiasi
        $request->validate([
            "jenis_cuti" => "required",
            "alasan_cuti" => "required",
            "mulai_cuti" => "required|date",
            "selesai_cuti" => "required|date|after:mulai_cuti",
            "alamat_cuti" => "required",
            "lampiran" => "file|max:5120"
        ], [
            'jenis_cuti.required' => 'Jenis cuti harus diisi.',
            'alasan_cuti.required' => 'Alasan cuti harus diisi.',
            'mulai_cuti.required' => 'Tanggal mulai cuti harus diisi.',
            'selesai_cuti.required' => 'Tanggal selesai cuti harus diisi.',
            'selesai_cuti.after' => 'Tanggal selesai cuti harus melebihi tanggal mulai cuti',
            'alamat_cuti.required' => 'Alamat selama cuti harus diisi.',
            'lampiran.max' => 'Lampiran tidak dapat lebih dari 5MB'
        ]);

        $mulai_cuti = Carbon::parse($request->mulai_cuti);
        $selesai_cuti = Carbon::parse($request->selesai_cuti);

        $lampiran = $request->file("lampiran");
        if ($lampiran) {
            SuratCuti::create([
                'jenis_user' => $request->jenis_user,
                'user_created' => $request->user_id,
                "jenis_cuti" => $request->jenis_cuti,
                "alasan_cuti" => $request->alasan_cuti,
                "mulai_cuti" => $mulai_cuti,
                "selesai_cuti" => $selesai_cuti,
                "alamat_cuti" => $request->alamat_cuti,
                "lama_cuti" => $mulai_cuti->diffInDays($selesai_cuti),
                "url_lampiran" => $request->url_lampiran,
                "url_lampiran_lokal" => str_replace('public/', '', $lampiran->store('public/suratcuti')),
            ]);
        } else {
            SuratCuti::create([
                'jenis_user' => $request->jenis_user,
                'user_created' => $request->user_id,
                "jenis_cuti" => $request->jenis_cuti,
                "alasan_cuti" => $request->alasan_cuti,
                "mulai_cuti" => $mulai_cuti,
                "selesai_cuti" => $selesai_cuti,
                "alamat_cuti" => $request->alamat_cuti,
                "lama_cuti" => $mulai_cuti->diffInDays($selesai_cuti),
                "url_lampiran" => $request->url_lampiran
            ]);
        }
        Alert::success('Berhasil!', 'Berhasil mengajukan Surat Cuti')->showConfirmButton('Ok', '#28a745');
        return redirect()->route("doc.index");
    }

    public function detail($id, Request $request)
    {
        $jenisUser = $request->jenis_user;
        $userId = $request->user_id;
        if ($jenisUser == "mahasiswa") abort(403);

        if ($jenisUser == "dosen") {
            $userRole = optional(Dosen::where("nip", $userId)->first())->role_id;
        } else {
            $userRole = User::where("username", $userId)->first()->role_id;
        }

        $data = SuratCuti::find($id);
        return view('doc.suratcuti.detail', compact('data', 'userRole', 'userId'));
    }

    public function edit($id, Request $request)
    {
        $jenis_cuti = $this->jenis_cuti;
        $jenis_user = $request->jenis_user;
        $suratCuti = SuratCuti::find($id);
        $jabatan = $request->jenis_user;
        $role = Role::where('id', Auth::guard($request->jenis_user == 'admin' ? 'web' : $request->jenis_user)->user()->role_id)->first();
        if ($role) {
            $jabatan = $role->role_akses;
        }
        return view('doc.suratcuti.edit', compact('suratCuti', 'jenis_user', 'jenis_cuti', 'jabatan'));
    }

    public function update($id, Request $request)
    {
        // Validiasi
        $request->validate([
            "jenis_cuti" => "required",
            "alasan_cuti" => "required",
            "mulai_cuti" => "required|date",
            "selesai_cuti" => "required|date|after:mulai_cuti",
            "alamat_cuti" => "required",
            "lampiran" => "file|max:5120"
        ], [
            'jenis_cuti.required' => 'Jenis cuti harus diisi.',
            'alasan_cuti.required' => 'Alasan cuti harus diisi.',
            'mulai_cuti.required' => 'Tanggal mulai cuti harus diisi.',
            'selesai_cuti.required' => 'Tanggal selesai cuti harus diisi.',
            'selesai_cuti.after' => 'Tanggal selesai cuti harus melebihi tanggal mulai cuti',
            'alamat_cuti.required' => 'Alamat selama cuti harus diisi.',
            'lampiran.max' => 'Lampiran tidak dapat lebih dari 5MB'
        ]);
        $mulai_cuti = Carbon::parse($request->mulai_cuti);
        $selesai_cuti = Carbon::parse($request->selesai_cuti);

        $suratCuti = SuratCuti::find($id);
        $suratCuti->jenis_cuti = $request->jenis_cuti;
        $suratCuti->alasan_cuti = $request->alasan_cuti;
        $suratCuti->mulai_cuti = $mulai_cuti;
        $suratCuti->selesai_cuti = $selesai_cuti;
        $suratCuti->alamat_cuti = $request->alamat_cuti;
        $suratCuti->url_lampiran = $request->url_lampiran;
        $suratCuti->lama_cuti = $mulai_cuti->diffInDays($selesai_cuti);
        $lampiran = $request->file("lampiran");
        if ($lampiran) {
            $suratCuti->url_lampiran_lokal = str_replace('public/', '', $lampiran->store('public/suratcuti'));
        }
        $suratCuti->update();

        return redirect()->route('doc.index');
    }

    public function destroy($id)
    {
        // Hapus Data Surat Cuti
        SuratCuti::where("id", $id)->delete();
        Alert::success('Berhasil!', 'Berhasil menghapus Surat Cuti')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.arsip');
    }

    public function approve($id)
    {
        $suratCuti = SuratCuti::where("id", $id)->first();
        $suratCuti->status = "diterima";
        $suratCuti->update();
        Alert::success('Berhasil!', 'Surat Cuti Disetujui')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }
    public function reject($id)
    {
        $suratCuti = SuratCuti::where("id", $id)->first();
        $suratCuti->status = "ditolak";
        $suratCuti->update();
        Alert::success('Berhasil!', 'Surat Cuti Ditolak')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }

    public function download($id)
    {
        $data = SuratCuti::where("id", $id)->where("status", "diterima")->first();
        if (!$data) return abort(404);
        $kajur = Role::where("role.id", 5)
            ->rightJoin("dosen", "role.id", "=", "dosen.role_id")
            ->select(
                "dosen.nama as nama",
                "dosen.nip as nip"
            )->first();
        // $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/sertifikat') . '/' . $slug));
        $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper("a4", 'potrait');
        $pdf->loadView("doc.pdf.suratcuti", compact('data', 'kajur'));
        // return $pdf->download("Surat Cuti " . data_get($data, $data->jenis_user . ".nama") . '.pdf');
        return $pdf->stream("Surat Cuti " . data_get($data, $data->jenis_user . ".nama") . '.pdf', array("Attachment" => false));
    }
}
