@extends('layouts.main')

@php
    Use Carbon\Carbon;
@endphp

@section('title')
    Kerja Praktek | SIA ELEKTRO
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

        @if (Str::length(Auth::guard('web')->user()) > 0)
    @if (Auth::guard('web')->user()->role_id == 1 || Auth::guard('web')->user()->role_id == 2 || Auth::guard('web')->user()->role_id == 3 || Auth::guard('web')->user()->role_id == 4 )

    @if (Auth::guard('web')->user()->role_id == 2 || Auth::guard('web')->user()->role_id == 3 || Auth::guard('web')->user()->role_id == 4 )
    <li><a href="/persetujuan/admin/index" class="px-1">Persetujuan</a></li>
    (<span id="waitingApprovalCount"></span>)
    <span class="px-2">|</span> 
    @endif
    <li><a href="/kerja-praktek/admin/index" class="breadcrumb-item active fw-bold text-success px-1">Data KP</a></li>
    (<span id="seminarKPCount"></span>)  
    <span class="px-2">|</span>
    <li><a href="/sidang/admin/index" class="px-1">Data Skripsi</a></li>
    (<span id="seminarKPCount"></span>)  
    <span class="px-2">|</span>
    <li><a href="/kp-skripsi/prodi/riwayat" class="px-1">Riwayat</a></li>
    (<span id=""></span>)
    
    @endif
    @endif
  
</ol>

<div class="container-fluid">

          <table class="table table-responsive-lg table-bordered table-striped" width="100%" id="datatables">
  <thead class="table-dark">
    <tr>      
        <th class="text-center p-2" scope="col">No.</th>
        <th class="text-center" scope="col">NIM</th>
        <th class="text-center" scope="col">Nama</th>
        <!-- <th class="text-center" scope="col">Konsentrasi</th>-->
        <th class="text-center" scope="col">Jenis Usulan</th>
        <th class="text-center" scope="col">Status KP</th>
        <th class="text-center" scope="col">Tanggal Usulan</th>
        <th class="text-center" scope="col">Keterangan</th> 
        <th class="text-center" scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($pendaftaran_kp as $kp)

        @php
  $tanggalDisetujui = $kp->tgl_disetujui_usulankp;
@endphp
@php
  $tanggalSaatIni = date('Y-m-d');
@endphp

<!-- Menghitung selisih hari -->
@php
  $waktuTersisa = strtotime($tanggalSaatIni) - strtotime($tanggalDisetujui);
  $selisihHari = floor($waktuTersisa / (60 * 60 * 24));
  $selisihHari30 = 30;
  $waktuMuncul = $selisihHari + $selisihHari30;
@endphp
  <div></div>
        <tr>        
            <td class="text-center">{{$loop->iteration}}</td>                             
            <td class="text-center">{{$kp->mahasiswa->nim}}</td>                             
            <td class="text-center">{{$kp->mahasiswa->nama}}</td>
            <!-- <td class="text-center">{{$kp->konsentrasi->nama_konsentrasi}}</td>                    -->
                       
            <td class="text-center">{{$kp->jenis_usulan}}</td>      
            
            @if ($kp->status_kp == 'USULAN KP' || $kp->status_kp == 'SURAT PERUSAHAAN' || $kp->status_kp == 'DAFTAR SEMINAR KP'|| $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN')           
            <td class="text-center bg-secondary">{{$kp->status_kp}}</td>
            @endif
            @if ($kp->status_kp == 'USULAN KP DITERIMA' || $kp->status_kp == 'KP DISETUJUI'|| $kp->status_kp == 'SEMINAR KP SELESAI' || $kp->status_kp == 'KP SELESAI')           
            <td class="text-center bg-info">{{$kp->status_kp}}</td>
            @endif
            

            @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')           
            <td class="text-center bg-success">{{$kp->status_kp}}</td>
            @endif

           @if ( $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK' || $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' || $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK' )           
            <td class="text-center bg-danger">{{$kp->status_kp}}</td>
            @endif
            
            @if ($kp->status_kp == 'USULAN KP')           
            <td class="text-center">{{Carbon::parse($kp->tgl_created_usulan)->translatedFormat('l, d F Y')}}</td>
            @endif

              @if ($kp->status_kp == 'USULAN KP DITERIMA')           
            <td class="text-center"> Batas Unggah Surat Balasan: <br>
@if ($waktuMuncul >= 0)
    <span class="text-danger"> {{ $waktuMuncul }}  hari lagi</span> ({{Carbon::parse($kp->tgl_disetujui_usulankp)->translatedFormat('l, d F Y')}})
  @else
    Batas Waktu Unggah Surat Balasan telah habis
  @endif
</td>
            @endif

             @if ($kp->status_kp == 'SURAT PERUSAHAAN')           
            <td class="text-center">Tanggal Usulan: <br>{{Carbon::parse($kp->tgl_created_balasan)->translatedFormat('l, d F Y')}}</td>
            @endif

            @if ($kp->status_kp == 'KP DISETUJUI')           
            <td class="text-center">Tanggal Usulan: <br>{{Carbon::parse($kp->tgl_disetujui_balasan)->translatedFormat('l, d F Y')}}</td>
            @endif

            @if ($kp->status_kp == 'DAFTAR SEMINAR KP')           
            <td class="text-center">{{Carbon::parse($kp->tgl_created_semkp)->translatedFormat('l, d F Y')}}</td>
            @endif
            @if ($kp->status_kp == 'KP SELESAI')           
            <td class="text-center">{{Carbon::parse($kp->tgl_created_kpti10)->translatedFormat('l, d F Y')}}</td>
            @endif
            
            @if ( $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK' || $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' || $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK')           
             <td class="text-center text-danger">{{$kp->keterangan}}</td>
             @else
              <td class="text-center">{{$kp->keterangan}}</td>
            @endif

            @if ($kp->status_kp == 'USULAN KP' || $kp->status_kp == 'USULAN KP DITERIMA'  )
            <td class="text-center">
              <a href="/usulan/detail/pembimbingprodi/{{($kp->id)}}" class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
            </td>
            @endif
            @if ($kp->status_kp == 'SURAT PERUSAHAAN' || $kp->status_kp == 'KP DISETUJUI' || $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK' )
            <td class="text-center">
              <a href="/suratperusahaan/detail/pembimbingprodi/{{($kp->id)}}" class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
            </td>
            @endif

            @if ($kp->status_kp == 'DAFTAR SEMINAR KP' || $kp->status_kp == 'SEMINAR KP DIJADWALKAN' || $kp->status_kp == 'SEMINAR KP SELESAI' || $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK')
            <td class="text-center">
              <a href="/daftar-semkp/detail/{{($kp->id)}}" class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
            </td>
            @endif

            @if ($kp->status_kp == 'BUKTI PENYERAHAN LAPORAN' || $kp->status_kp == 'KP SELESAI' || $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK')
            <td class="text-center">
              <a href="/kpti10/detail/pembimbingprodi/{{($kp->id)}}" class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
            </td>
            @endif 



        </tr>

    @endforeach
  </tbody>


</table>
</div>
</div>


@endsection
