@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Kerja Praktek dan Skripsi
@endsection

@section('sub-title')
    <style>
        hr {
            display: none;
        }
    </style>
@endsection

@section('content')


    <!-- <div class="container">
          <div class="row">
            <div class="col-lg-6 col-md-12">
              <div class="card">
                <a href="" class="kpindex">
                  <img  src="/assets/img/il3.png" class="" width="100%" alt="Kerja Praktek">
                  <div class="row p-4">
                  <div class="col-6 text-dark"><h3 class="fw-bold">KERJA PRAKTEK</h3></div>
                  <div class="col-6"><span class="">BELUM DAFTAR</span></div>
                  </div>
                </a>
                <div class="col p-4">
                  <p>Keterangan</p>
                  <span>Belum melakukan Usulan KP</span>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-12">
              <div class="card">

              </div>
            </div>
          </div>
        </div> -->


    <div class="container px-md-5  ">

        <div class="row">

            @if (Str::length(Auth::guard('mahasiswa')->user()) > 0)
                @if (Auth::guard('mahasiswa')->user())
                    <div class="col-12 col-md-6 utama ">
                        @if ($pendaftaran_kp == null)
                            <a href="/usulankp/create">
                                <div class="card kpindex">
                                    <img src="/assets/img/il3.png"
                                        class="rounded mx-auto d-block card-img-top shadow-lg p-3 bg-body rounded"
                                        alt="Kerja Praktek">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-5 col-md-6">
                                                <h1>
                                                    <p class=" fs-3  text-bold text-dark">KERJA PRAKTEK</p>
                                                </h1>
                            </a>
                    </div>
                    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 mt-0">
                        <p class="card-text text-md-end status"><span
                                class="float-end badge p-2 bg-secondary text-bold pr-3 pl-3"
                                style="border-radius:20px;">BELUM DAFTAR</span></p>
                    </div>
        </div>
        <div class="row">
            <div class="col-sm-12 mt-3 mt-md-0 ">
                <p class="card-title text-secondary text-sm">Keterangan</p>
                <p class="card-text text-start text-dark"><span>Belum melakukan Usulan KP</span></p>
            </div>
        </div>
    </div>
    </div>
@elseif(
    $pendaftaran_kp->status_kp == 'USULAN KP' ||
        $pendaftaran_kp->status_kp == 'SURAT PERUSAHAAN' ||
        $pendaftaran_kp->status_kp == 'DAFTAR SEMINAR KP' ||
        $pendaftaran_kp->status_kp == 'BUKTI PENYERAHAN LAPORAN')
    <a href="/usulankp/index">
        <div class="card kpindex">
            <img src="/assets/img/il3.png" class="rounded mx-auto d-block card-img-top shadow-lg p-3 bg-body rounded"
                alt="Kerja Praktek">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5 col-md-6">
                        <h1>
                            <p class=" fs-3  text-bold text-dark">KERJA PRAKTEK</p>
                        </h1>
    </a></div>
    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 mt-0">
        <p class="card-text text-md-end status"><span class="float-end badge p-2 bg-secondary text-bold pr-3 pl-3"
                style="border-radius:20px;">{{ $pendaftaran_kp->status_kp }}</span></p>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mt-3 mt-md-0 ">
            <p class="card-title text-secondary text-sm">Keterangan</p>
            <p class="card-text text-start text-dark"><span>{{ $pendaftaran_kp->keterangan }}</span></p>
        </div>
    </div>

    </div>
    </div>
@elseif(
    $pendaftaran_kp->status_kp == 'USULAN KP DITOLAK' ||
        $pendaftaran_kp->status_kp == 'USULAN KP DITOLAK' ||
        $pendaftaran_kp->status_kp == 'USULKAN KP ULANG' ||
        $pendaftaran_kp->status_kp == 'SURAT PERUSAHAAN DITOLAK' ||
        $pendaftaran_kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' ||
        $pendaftaran_kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK')
    <a href="/usulankp/index">
        <div class="card kpindex">
            <img src="/assets/img/il3.png" class="rounded mx-auto d-block card-img-top shadow-lg p-3 bg-body rounded"
                alt="Kerja Praktek">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5 col-md-6">
                        <h1>
                            <p class=" fs-3  text-bold text-dark">KERJA PRAKTEK</p>
                        </h1>
    </a></div>
    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 mt-0">
        <p class="card-text text-md-end status"><span class="float-end badge p-2 bg-danger text-bold pr-3 pl-3"
                style="border-radius:20px;">{{ $pendaftaran_kp->status_kp }}</span></p>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mt-3 mt-md-0 ">
            <p class="card-title text-secondary text-sm">Keterangan</p>
            <p class="card-text text-start text-danger"><span>{{ $pendaftaran_kp->keterangan }}</span></p>
        </div>
    </div>

    </div>
    </div>
@elseif($pendaftaran_kp->status_kp == 'SEMINAR KP DIJADWALKAN')
    <a href="/usulankp/index">
        <div class="card kpindex">
            <img src="/assets/img/il3.png" class="rounded mx-auto d-block card-img-top shadow-lg p-3 bg-body rounded"
                alt="Kerja Praktek">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5 col-md-6">
                        <h1>
                            <p class=" fs-3  text-bold text-dark">KERJA PRAKTEK</p>
                        </h1>
    </a></div>
    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 mt-0">
        <p class="card-text text-md-end status"><span class="float-end badge p-2 bg-success text-bold pr-3 pl-3"
                style="border-radius:20px;">{{ $pendaftaran_kp->status_kp }}</span></p>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mt-3 mt-md-0 ">
            <p class="card-title text-secondary text-sm">Keterangan</p>
            <p class="card-text text-start text-dark"><span>{{ $pendaftaran_kp->keterangan }}</span></p>
        </div>
    </div>

    </div>
    </div>
@elseif(
    $pendaftaran_kp->status_kp == 'USULAN KP DITERIMA' ||
        $pendaftaran_kp->status_kp == 'KP DISETUJUI' ||
        $pendaftaran_kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI' ||
        $pendaftaran_kp->status_kp == 'SEMINAR KP SELESAI' ||
        $pendaftaran_kp->status_kp == 'KP SELESAI')
    <a href="/usulankp/index">
        <div class="card kpindex">
            <img src="/assets/img/il3.png" class="rounded mx-auto d-block card-img-top shadow-lg p-3 bg-body rounded"
                alt="Kerja Praktek">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5 col-md-6">
                        <h1>
                            <p class=" fs-3  text-bold text-dark">KERJA PRAKTEK</p>
                        </h1>
    </a></div>
    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 mt-0">
        <p class="card-text text-md-end status"><span class="float-end badge p-2 bg-info text-bold pr-3 pl-3"
                style="border-radius:20px;">{{ $pendaftaran_kp->status_kp }}</span></p>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mt-3 mt-md-0 ">
            <p class="card-title text-secondary text-sm">Keterangan</p>
            <p class="card-text text-start text-dark"><span>{{ $pendaftaran_kp->keterangan }}</span></p>
        </div>
    </div>
    </div>
    </div>
    @endif
    </div>
    <div class="col-12 col-md-6 utama utama-skripsi">
        @if ($pendaftaran_skripsi == null)
            <a href="/usuljudul/create">
                <div class="card kpindex">
                    <img src="/assets/img/il8.png"
                        class="rounded mx-auto d-block card-img-top shadow-lg p-3 bg-body rounded" alt="...">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-5 col-md-6 ">
                                <h1>
                                    <p class=" fs-3 text-bold text-dark">SKRIPSI</p>
                                </h1>
            </a>
    </div>
    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 mt-0">
        <p class="card-text text-md-end status"><span class="float-end badge p-2 bg-secondary text-bold pr-3 pl-3"
                style="border-radius:20px;">BELUM DAFTAR</span></p>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mt-3 mt-md-0 ">
            <p class="card-title text-secondary text-sm">Keterangan</p>
            <p class="card-text text-start text-dark"><span>Belum melakukan Usul Judul</span></p>
        </div>
    </div>

    </div>
    </div>
@elseif(
    $pendaftaran_skripsi->status_skripsi == 'USULAN JUDUL DITOLAK' ||
        $pendaftaran_skripsi->status_skripsi == 'USULKAN JUDUL ULANG' ||
        $pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO DITOLAK' ||
        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK' ||
        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK' ||
        $pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK' ||
        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK' ||
        $pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG' ||
        $pendaftaran_skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK' ||
        $pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG ULANG' || $pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK')
    <a href="/usuljudul/index">
        <div class="card kpindex">
            <img src="/assets/img/il8.png" class="rounded mx-auto d-block card-img-top shadow-lg p-3 bg-body rounded"
                alt="...">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5 col-md-6">
                        <h1>
                            <p class=" fs-3 text-bold text-dark">SKRIPSI</p>
                        </h1>
    </a></div>
    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 mt-0">
        <p class="card-text text-md-end status"><span class="float-end badge p-2 bg-danger text-bold pr-3 pl-3"
                style="border-radius:20px;">{{ $pendaftaran_skripsi->status_skripsi }}</span></p>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mt-3 mt-md-0 ">
            <p class="card-title text-secondary text-sm">Keterangan</p>
            <p class="card-text text-start text-danger"><span>{{ $pendaftaran_skripsi->keterangan }}</span></p>
        </div>
    </div>

    </div>
    </div>
@elseif(
    $pendaftaran_skripsi->status_skripsi == 'USULAN JUDUL' ||
        $pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO' ||
        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1' ||
        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2' ||
        $pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG' ||
        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN REVISI' ||
        $pendaftaran_skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI')
    <a href="/usuljudul/index">
        <div class="card kpindex cardskripsi">
            <img src="/assets/img/il8.png" class="rounded mx-auto d-block card-img-top shadow-lg p-3 bg-body rounded"
                alt="...">
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-5 col-md-6">
                        <h1>
                            <p class=" fs-3 text-bold text-dark">SKRIPSI</p>
                        </h1>
    </a></div>
    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 mt-0">
        <p class="card-text text-md-end status"><span class="float-end badge p-2 bg-secondary text-bold pr-3 pl-3"
                style="border-radius:20px;">{{ $pendaftaran_skripsi->status_skripsi }}</span></p>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mt-3 mt-md-0 ">
            <p class="card-title text-secondary text-sm">Keterangan</p>
            <p class="card-text text-start text-dark"><span>{{ $pendaftaran_skripsi->keterangan }}</span></p>
        </div>
    </div>

    </div>
    </div>
@elseif(
    $pendaftaran_skripsi->status_skripsi == 'SEMPRO DIJADWALKAN' ||
        $pendaftaran_skripsi->status_skripsi == 'SIDANG DIJADWALKAN')
    <a href="/usuljudul/index">
        <div class="card kpindex">
            <img src="/assets/img/il8.png" class="rounded mx-auto d-block card-img-top shadow-lg p-3 bg-body rounded"
                alt="...">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5 col-md-6">
                        <h1>
                            <p class=" fs-3 text-bold text-dark">SKRIPSI</p>
                        </h1>
    </a></div>
    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 mt-0">
        <p class="card-text text-md-end status"><span class="float-end badge p-2 bg-success text-bold pr-3 pl-3"
                style="border-radius:20px;">{{ $pendaftaran_skripsi->status_skripsi }}</span></p>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mt-3 mt-md-0 ">
            <p class="card-title text-secondary text-sm">Keterangan</p>
            <p class="card-text text-start text-dark"><span>{{ $pendaftaran_skripsi->keterangan }}</span></p>
        </div>
    </div>

    </div>
    </div>
@elseif(
    $pendaftaran_skripsi->status_skripsi == 'JUDUL DISETUJUI' ||
        $pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO DISETUJUI' ||
        $pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI' ||
        $pendaftaran_skripsi->status_skripsi == 'SEMPRO SELESAI' ||
        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI' ||
        $pendaftaran_skripsi->status_skripsi == 'SIDANG SELESAI' ||
        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI' ||
        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI' ||
        $pendaftaran_skripsi->status_skripsi == 'SKRIPSI SELESAI' ||
        $pendaftaran_skripsi->status_skripsi == 'LULUS')
    <a href="/usuljudul/index">
        <div class="card kpindex">
            <img src="/assets/img/il8.png" class="rounded mx-auto d-block card-img-top shadow-lg p-3 bg-body rounded"
                alt="...">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5 col-md-6">
                        <h1>
                            <p class=" fs-3 text-bold text-dark">SKRIPSI</p>
                        </h1>
    </a></div>
    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 mt-0">
        <p class="card-text text-md-end status"><span class="float-end badge p-2 bg-info text-bold pr-3 pl-3"
                style="border-radius:20px;">{{ $pendaftaran_skripsi->status_skripsi }}</span></p>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mt-3 mt-md-0 ">
            <p class="card-title text-secondary text-sm">Keterangan</p>
            <p class="card-text text-start text-dark"><span>{{ $pendaftaran_skripsi->keterangan }}</span></p>
        </div>
    </div>
    </div>
    </div>
    @endif
    </div>
    @endif
    @endif



    </div>
    </div>




    <!-- <div class="footer-daftar text-center pt-5">
                <div class="container">
                  <p class="developer text-sm">Dikembangkan oleh Prodi Teknik Informatika UNRI <br> <a href="/developer/m-seprinaldi" class="text-dark text-sm fw-bold text-decoration-underline" target="_blank">(M. Seprinaldi)</a>  <br>Copyright &copy; {{ now()->year }} - {{ now()->addYears(1)->year }} SITEI All Rights Reserved</p>
                </div>
              </div> -->


<br>
<br>
<br>
@endsection

@section('footer')
    <section class="bg-dark p-1">
        <div class="container">
            <p class="developer">Dikembangkan oleh Prodi Teknik Informatika UNRI <a class="text-success fw-bold"
                    formtarget="_blank" target="_blank" href="/developer/m-seprinaldi">( M. Seprinaldi )</a></p>
        </div>
    </section>
@endsection
