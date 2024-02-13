<?php

namespace App\Http\Controllers;

use App\Models\PenilaianKP;
use Illuminate\Http\Request;
use App\Models\PendaftaranKP;
use App\Models\PenjadwalanKP;
use App\Models\PenjadwalanSempro;
use App\Models\PendaftaranSkripsi;
use App\Models\PenjadwalanSkripsi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\PenilaianSemproPenguji;
use App\Models\PenilaianSkripsiPenguji;
use App\Models\PenilaianSemproPembimbing;
use App\Models\PenilaianSkripsiPembimbing;

class QRController extends Controller
{
    public function detailkp($id)
    {        
        $penjadwalan = PenjadwalanKP::findorfail($id);

        return view('penjadwalankp.detaildata-kp', [
            'penjadwalan' => $penjadwalan,            
        ]);
    }
    public function suratpermohonankp($id)
    {        
        $pendaftaran_kp = PendaftaranKP::findorfail($id);

        return view('pendaftaran.kerja-praktek.usulan-kp.suratpermohonan-kp-data', [
            'pendaftaran_kp' => $pendaftaran_kp,            
        ]);
    }

    public function detail_surat_permohonan_kp($id)
    {        
        $pendaftaran_kp = PendaftaranKP::findorfail($id);

        return view('pendaftaran.kerja-praktek.balasan-kp.detail-surat-permohonan-kp', [
            'pendaftaran_kp' => $pendaftaran_kp,            
        ]);
    }

    
    public function detail_form_permohonan_kp($id)
    {        
        $pendaftaran_kp = PendaftaranKP::findorfail($id);
        
        return view('pendaftaran.kerja-praktek.balasan-kp.detail-form-permohonan-kp', [
            'pendaftaran_kp' => $pendaftaran_kp,            
        ]);
    }

    public function detailsuratpermohonanpengajuantopikskripsi($id)
    {        
        $pendaftaran_skripsi = PendaftaranSkripsi::findorfail($id);
        
        return view('pendaftaran.skripsi.usul-judul.detail-surat-permohonan-pengajuan-topik-skripsi', [
            'pendaftaran_skripsi' => $pendaftaran_skripsi,            
        ]);
    }

    public function detailformpengajuantopikskripsi($id)
    {        
        $pendaftaran_skripsi = PendaftaranSkripsi::findorfail($id);
        
        return view('pendaftaran.skripsi.usul-judul.detail-form-pengajuan-topik-skripsi', [
            'pendaftaran_skripsi' => $pendaftaran_skripsi,            
        ]);
    }
    
    
    public function detailsempro($id)
    {        
        $penjadwalan = PenjadwalanSempro::find($id);
        $pembimbing = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->get();
        $nilaipenguji1 = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penjadwalan->pengujisatu_nip)->first();

        $nilaipenguji2 = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penjadwalan->pengujidua_nip)->first();

        $nilaipenguji3 = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penjadwalan->pengujitiga_nip)->first();

        if ($pembimbing->count() > 1) {
            $pembimbingnilai = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->get();
        } else {
            $pembimbingnilai = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $penjadwalan->id)->first();
        }

        $nilaipembimbing1 = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->where('pembimbing_nip', $penjadwalan->pembimbingsatu_nip)->first();

        if ($penjadwalan->pembimbingdua_nip == null) {
            return view('penjadwalansempro.detaildata-sempro', [
                'pembimbing' => $pembimbing,
                'pembimbingnilai' => $pembimbingnilai,
                'penjadwalan' => $penjadwalan,
                'nilaipenguji1' => $nilaipenguji1,
                'nilaipenguji2' => $nilaipenguji2,
                'nilaipenguji3' => $nilaipenguji3,
                'nilaipembimbing1' => $nilaipembimbing1,
            ]);
        } else {
            $nilaipembimbing2 = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->where('pembimbing_nip', $penjadwalan->pembimbingdua_nip)->first();

            return view('penjadwalansempro.detaildata-sempro', [
                'pembimbing' => $pembimbing,
                'pembimbingnilai' => $pembimbingnilai,
                'penjadwalan' => $penjadwalan,
                'nilaipenguji1' => $nilaipenguji1,
                'nilaipenguji2' => $nilaipenguji2,
                'nilaipenguji3' => $nilaipenguji3,
                'nilaipembimbing1' => $nilaipembimbing1,
                'nilaipembimbing2' => $nilaipembimbing2,
            ]);
        }
    }

    public function detailskripsi($id)
    {        
        $penjadwalan = PenjadwalanSkripsi::find($id);
        $pembimbing = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $id)->get();

        $nilaipenguji1 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $id)->where('penguji_nip', $penjadwalan->pengujisatu_nip)->first();

        $nilaipenguji2 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $id)->where('penguji_nip', $penjadwalan->pengujidua_nip)->first();

        $nilaipenguji3 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $id)->where('penguji_nip', $penjadwalan->pengujitiga_nip)->first();

        if ($pembimbing->count() > 1) {
            $pembimbingnilai = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $id)->get();
        } else {
            $pembimbingnilai = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan->id)->first();
        }

        $nilaipembimbing1 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $id)->where('pembimbing_nip', $penjadwalan->pembimbingsatu_nip)->first();

        if ($penjadwalan->pembimbingdua_nip == null) {
            return view('penjadwalanskripsi.detaildata-skripsi', [
                'pembimbing' => $pembimbing,
                'pembimbingnilai' => $pembimbingnilai,
                'penjadwalan' => $penjadwalan,
                'nilaipenguji1' => $nilaipenguji1,
                'nilaipenguji2' => $nilaipenguji2,
                'nilaipenguji3' => $nilaipenguji3,
                'nilaipembimbing1' => $nilaipembimbing1,
            ]);
        } else {
            $nilaipembimbing2 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $id)->where('pembimbing_nip', $penjadwalan->pembimbingdua_nip)->first();

            return view('penjadwalanskripsi.detaildata-skripsi', [
                'pembimbing' => $pembimbing,
                'pembimbingnilai' => $pembimbingnilai,
                'penjadwalan' => $penjadwalan,
                'nilaipenguji1' => $nilaipenguji1,
                'nilaipenguji2' => $nilaipenguji2,
                'nilaipenguji3' => $nilaipenguji3,
                'nilaipembimbing1' => $nilaipembimbing1,
                'nilaipembimbing2' => $nilaipembimbing2,
            ]);
        }
    }
    
    
    public function detail_sidang_skripsi($id)
    {        
        $penjadwalan = PenjadwalanSkripsi::find($id);
        $pembimbing = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $id)->get();

        $nilaipenguji1 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $id)->where('penguji_nip', $penjadwalan->pengujisatu_nip)->first();

        $nilaipenguji2 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $id)->where('penguji_nip', $penjadwalan->pengujidua_nip)->first();

        $nilaipenguji3 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $id)->where('penguji_nip', $penjadwalan->pengujitiga_nip)->first();

        if ($pembimbing->count() > 1) {
            $pembimbingnilai = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $id)->get();
        } else {
            $pembimbingnilai = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan->id)->first();
        }

        $nilaipembimbing1 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $id)->where('pembimbing_nip', $penjadwalan->pembimbingsatu_nip)->first();

        if ($penjadwalan->pembimbingdua_nip == null) {
            return view('penjadwalanskripsi.detaildata-skripsi', [
                'pembimbing' => $pembimbing,
                'pembimbingnilai' => $pembimbingnilai,
                'penjadwalan' => $penjadwalan,
                'nilaipenguji1' => $nilaipenguji1,
                'nilaipenguji2' => $nilaipenguji2,
                'nilaipenguji3' => $nilaipenguji3,
                'nilaipembimbing1' => $nilaipembimbing1,
            ]);
        } else {
            $nilaipembimbing2 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $id)->where('pembimbing_nip', $penjadwalan->pembimbingdua_nip)->first();

            return view('penjadwalanskripsi.detaildata-skripsi', [
                'pembimbing' => $pembimbing,
                'pembimbingnilai' => $pembimbingnilai,
                'penjadwalan' => $penjadwalan,
                'nilaipenguji1' => $nilaipenguji1,
                'nilaipenguji2' => $nilaipenguji2,
                'nilaipenguji3' => $nilaipenguji3,
                'nilaipembimbing1' => $nilaipembimbing1,
                'nilaipembimbing2' => $nilaipembimbing2,
            ]);
        }
    }
   
   
//    DETAIL UNDANGAN SIDANG
   
    public function detail_undangan_sidang($id)
    {        
        $penjadwalan = PenjadwalanSkripsi::find($id);

        return view('undanganseminar.detail-undangan-sidang', [
                'penjadwalan' => $penjadwalan,
            ]);

    }

    public function detail_undangan_sempro($id)
    {        
        $penjadwalan = PenjadwalanSempro::find($id);

        if ($penjadwalan->pembimbingdua_nip == null) {
            return view('undanganseminar.detail-undangan-sempro', [
                'penjadwalan' => $penjadwalan,
            ]);
        } else {

            return view('undanganseminar.detail-undangan-sempro', [
                'penjadwalan' => $penjadwalan,
            ]);
        }
    }

    public function detail_undangan_kp($id)
    {        
        $penjadwalan = PenjadwalanKP::find($id);

        if ($penjadwalan->pembimbingdua_nip == null) {
            return view('undanganseminar.detail-undangan-kp', [
                'penjadwalan' => $penjadwalan,
            ]);
        } else {

            return view('undanganseminar.detail-undangan-kp', [
                'penjadwalan' => $penjadwalan,
            ]);
        }
    }

   
}
