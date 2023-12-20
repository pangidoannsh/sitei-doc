@extends('layouts.main')

@php
    Use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Data Kerja Praktek Mahasiswa
@endsection

@section('sub-title')
    Data Kerja Praktek Mahasiswa
@endsection

@section('content')

@if (session()->has('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{session('message')}}
</div>
@endif



<div class="container card p-4">

<ol class="breadcrumb col-lg-12">     

  @if (Str::length(Auth::guard('dosen')->user()) > 0)
        @if (Auth::guard('dosen')->user()->role_id == 5 || Auth::guard('dosen')->user()->role_id == 6 || Auth::guard('dosen')->user()->role_id == 7 || Auth::guard('dosen')->user()->role_id == 8 || Auth::guard('dosen')->user()->role_id == 9 || Auth::guard('dosen')->user()->role_id == 10 || Auth::guard('dosen')->user()->role_id == 11 )
  <li><a href="/kp-skripsi/seminar" class="px-1">Seminar  (<span>{{ $jml_seminar_kp + $jml_sempro + $jml_sidang }}</span>) </a></li>

        <span class="px-2">|</span>
        <li><a href="/kerja-praktek" class="breadcrumb-item active fw-bold text-success px-1">Kerja Praktek (<span>{{ $jml_prodi_kp }}</span>)</a></li>
        
        <span class="px-2">|</span>
        <li><a href="/skripsi" class="px-1">Skripsi (<span>{{ $jml_prodi_skripsi }}</span>)</a></li>


        <span class="px-2">|</span>
        <li><a href="/kp-skripsi/prodi/riwayat" class="px-1">Riwayat (<span>{{ $jml_riwayat_prodi_kp + $jml_riwayat_prodi_skripsi + $jml_riwayat_seminar_kp + $jml_riwayat_sempro + $jml_riwayat_skripsi }}</span>)</a></li>
      @endif
  @endif
    
  
</ol>

<div class="container-fluid">

          <table class="table table-responsive-lg table-bordered table-striped" width="100%" id="datatables">
  <thead class="table-dark">
    <tr>      
        <th class="text-center px-0" scope="col">No.</th>
        <th class="text-center" scope="col">NIM</th>
        <th class="text-center" scope="col">Nama</th>
        <th class="text-center" scope="col">Status KP</th>
        <th class="text-center" scope="col">Tanggal Penting</th>
        <!-- <th class="text-center" scope="col">Batas Waktu</th> -->
        <th class="text-center" scope="col">Keterangan</th>   
        <th class="text-center" scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($pendaftaran_kp as $kp)

<div></div>
        <tr>        
            <td class="text-center ">{{$loop->iteration}}</td>                             
            <td class="text-center">{{$kp->mahasiswa->nim}}</td>                             
            <td class="text-center fw-bold">{{$kp->mahasiswa->nama}}</td>
            @if ($kp->status_kp == 'USULAN KP' || $kp->status_kp == 'SURAT PERUSAHAAN'|| $kp->status_kp == 'DAFTAR SEMINAR KP' ||$kp->status_kp == 'BUKTI PENYERAHAN LAPORAN' )           
            <td class="text-center bg-secondary">{{$kp->status_kp}}</td>
            @endif
            @if ($kp->status_kp == 'USULAN KP DITERIMA' || $kp->status_kp == 'KP DISETUJUI'|| $kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI' || $kp->status_kp == 'SEMINAR KP SELESAI' ||$kp->status_kp == 'KP SELESAI' )           
            <td class="text-center bg-info">{{$kp->status_kp}}</td>
            @endif
            @if ( $kp->status_kp == 'SEMINAR KP DIJADWALKAN')           
            <td class="text-center bg-success">{{$kp->status_kp}}</td>
            @endif
            @if ( $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK' || $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' || $kp->status_kp == 'DAFTAR SEMINAR KP ULANG' || $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK' )           
            <td class="text-center bg-danger">{{$kp->status_kp}}</td>
            @endif
            
            @if ($kp->status_kp == 'USULAN KP')           
            <td class="text-center"> Tanggal Usulan: <br><b>{{Carbon::parse($kp->tgl_created_usulan)->translatedFormat('l, d F Y')}}</b></td>
            @endif
            @if ($kp->status_kp == 'USULAN KP DITERIMA')           
            <td class="text-center"> Tanggal Diterima: <br><b>{{Carbon::parse($kp->tgl_disetujui_usulankp_kaprodi)->translatedFormat('l, d F Y')}}</b></td>
            @endif

             @if ($kp->status_kp == 'SURAT PERUSAHAAN' || $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK')           
            <td class="text-center">Tanggal Usulan: <br><b>{{Carbon::parse($kp->tgl_created_balasan)->translatedFormat('l, d F Y')}}</b></td>
            @endif

             @if ($kp->status_kp == 'KP DISETUJUI')           
            <td class="text-center">Tanggal Disetujui: <br><b>{{Carbon::parse($kp->tgl_disetujui_balasan)->translatedFormat('l, d F Y')}}</b></td>
            @endif

            @if ($kp->status_kp == 'DAFTAR SEMINAR KP' || $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' || $kp->status_kp == 'DAFTAR SEMINAR KP ULANG')           
            <td class="text-center">Tanggal Usulan: <br><b>{{Carbon::parse($kp->tgl_created_semkp)->translatedFormat('l, d F Y')}}</b></td>
            @endif
            @if ($kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI')           
            <td class="text-center">Tanggal Disetujui: <br><b>{{Carbon::parse($kp->tgl_created_semkp_kaprodi)->translatedFormat('l, d F Y')}}</b></td>
            @endif
            @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')           
            <td class="text-center">Tanggal Dijadwalkan: <br><b>{{Carbon::parse($kp->tgl_dijadwalkan)->translatedFormat('l, d F Y')}}</b></td>
            @endif
            @if ($kp->status_kp == 'SEMINAR KP SELESAI')           
            <td class="text-center">Tanggal Selesai: <br><b>{{Carbon::parse($kp->tgl_selesai_semkp)->translatedFormat('l, d F Y')}}</b></td>
            @endif
            @if ($kp->status_kp == 'BUKTI PENYERAHAN LAPORAN' || $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK')           
            <td class="text-center">Tanggal Usulan: <br><b>{{Carbon::parse($kp->tgl_created_kpti10)->translatedFormat('l, d F Y')}}</b></td>
            @endif
            @if ($kp->status_kp == 'KP SELESAI')           
            <td class="text-center">Tanggal Selesai: <br><b>{{Carbon::parse($kp->tgl_selesai_semkp)->translatedFormat('l, d F Y')}}</b></td>
            @endif
     
            @if ( $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK' || $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' || $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK' || $kp->status_kp == 'DAFTAR SEMINAR KP ULANG')           
             <td class="text-center text-danger">{{$kp->keterangan}}</td>
             @else
              <td class="text-center">{{$kp->keterangan}}</td>
            @endif

            @if ($kp->status_kp == 'USULAN KP' || $kp->status_kp == 'USULAN KP DITERIMA' )
            <td class="text-center">
              <a href="/usulan/detail/{{($kp->id)}}" class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
            <!-- <a href="/usulan/detail/ {{($kp->id)}}" class="badge bg-success rounded-pill p-2 fas fa-eye"> Lihat Detail</a> -->
            </td>
            @endif
            
            @if ($kp->status_kp == 'SURAT PERUSAHAAN' || $kp->status_kp == 'KP DISETUJUI' || $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK')
            <td class="text-center">
            <a href="/balasan-kp/detail/ {{($kp->id)}}" class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
            </td>
            @endif
            @if ($kp->status_kp == 'DAFTAR SEMINAR KP' || $kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI' || $kp->status_kp == 'SEMINAR KP DIJADWALKAN'|| $kp->status_kp == 'SEMINAR KP SELESAI' || $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' || $kp->status_kp == 'DAFTAR SEMINAR KP ULANG')
            <td class="text-center">
            <a href="/daftar-semkp/detail/{{($kp->id)}}" class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
            </td>
            @endif

            @if ($kp->status_kp == 'BUKTI PENYERAHAN LAPORAN' || $kp->status_kp == 'KP SELESAI' || $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK')
            <td class="text-center">
            <a href="/kpti10-kp/detail/{{($kp->id)}}" class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
            </td>
            @endif  

        </tr>

    @endforeach
  </tbody>


</table>
</div>
</div>


@endsection

@section('footer')
<section class="bg-dark p-1">
<div class="container">
          <p class="developer">Dikembangkan oleh Prodi Teknik Informatika UNRI <a class="text-success fw-bold" formtarget="_blank" target="_blank" href="/developer/m-seprinaldi">( M. Seprinaldi )</a></p>
        </div>
</section>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const waitingApprovalCount = {!! json_encode($jml_prodi_kp) !!};
    if (waitingApprovalCount > 0) {
        Swal.fire({
            title: 'Ini adalah halaman Kerja Praktek',
            html: `Ada <strong class="text-info"> ${waitingApprovalCount} Mahasiswa</strong> yang melaksanakan Kerja Praktek.`,
            icon: 'info',
            showConfirmButton: true,
            confirmButtonColor: '#28a745',
        });
    } else {
        Swal.fire({
            title: 'Ini adalah halaman Kerja Praktek',
            html: `Belum ada mahasiswa yang melaksanakan Kerja Praktek.`,
            icon: 'info',
            showConfirmButton: true,
            confirmButtonColor: '#28a745',
        });
    }
});
</script>
@endpush()


