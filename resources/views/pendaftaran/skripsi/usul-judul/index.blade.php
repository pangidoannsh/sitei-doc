@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Skripsi
@endsection
@section('sub-title')
    Skripsi
@endsection


@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif

    @php
        $tanggalDisetujuiUsulJudul = $pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi;
    @endphp

    @php
        $tanggalGagalSempro = $pendaftaran_skripsi->tgl_created_sempro;
    @endphp

    @php
        $tanggalSelesaiSempro = $pendaftaran_skripsi->tgl_semproselesai;
    @endphp

    @php
        $tanggalPerpanjangan1 = $pendaftaran_skripsi->tgl_disetujui_perpanjangan1;
    @endphp

    @php
        $tanggalDitolakSidang = $pendaftaran_skripsi->tgl_created_sidang;
    @endphp

    @php
        $tanggalDisetujuiSidang = $pendaftaran_skripsi->tgl_selesai_sidang;
    @endphp


    <div class="container-fluid">

        <div class="card card-timeline px-2 border-none">
            <h5 class="text-center">
                <div class="row text-center justify-content-center mb-5">
                    <div class="col-xl-6 col-lg-8">
                        <!-- <h2 class="font-weight-bold mt-3">Timeline Progress Skripsi</h2> -->
                        <!-- <p class="text-muted"></p> -->
                    </div>
                </div>

                <ul class="bs4-order-skripsi">
                    @if ($pendaftaran_skripsi->status_skripsi == 'USULAN JUDUL')
                        <li class="step active">
                            <div>
                                <i class="fas"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if (
                        $pendaftaran_skripsi->status_skripsi == 'USULAN JUDUL DITOLAK' ||
                            $pendaftaran_skripsi->status_skripsi == 'USULKAN JUDUL ULANG')
                        <li class="step aktip">
                            <div>
                                <i class="fas"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if ($pendaftaran_skripsi->status_skripsi == 'JUDUL DISETUJUI')
                        <li class="step active">
                            <div>
                                <i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>

                        </li>

                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>

                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if (
                        $pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO' ||
                            $pendaftaran_skripsi->status_skripsi == 'SEMPRO DIJADWALKAN' ||
                            $pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO DISETUJUI')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>

                        <li class="step active">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if ($pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>

                        </li>

                        <li class="step aktip">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>

                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif

                    @if ($pendaftaran_skripsi->status_skripsi == 'SEMPRO SELESAI')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>

                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>

                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>

                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if (
                        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1' ||
                            $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI' ||
                            $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>

                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>

                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>

                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if (
                        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2' ||
                            $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI' ||
                            $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>

                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>

                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if ($pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>

                        </li>

                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>

                        </li>
                        <li class="step active">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if ($pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG ULANG' || $pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>

                        </li>

                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>

                        </li>
                        <li class="step aktip">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>

                        </li>
                        <li class="step">
                            <div><i class="fas"></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if (
                        $pendaftaran_skripsi->status_skripsi == 'SIDANG DIJADWALKAN' ||
                            $pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>

                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>
                        </li>
                        <li class="step active">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if ($pendaftaran_skripsi->status_skripsi == 'SIDANG SELESAI')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>

                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>
                        </li>
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if (
                        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN REVISI' ||
                            $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK' ||
                            $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>

                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>
                        </li>
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step ">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if ($pendaftaran_skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>

                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>
                        </li>
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step active">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                    @if ($pendaftaran_skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>

                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>
                        </li>
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step aktip">
                            <div><i class="fas "></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif

                    @if ($pendaftaran_skripsi->status_skripsi == 'SKRIPSI SELESAI' || $pendaftaran_skripsi->status_skripsi == 'LULUS')
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> USUL JUDUL</p>
                        </li>

                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SEMPRO </p>
                        </li>
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2">SIDANG </p>
                        </li>
                        <li class="step active">
                            <div><i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2"> PENYERAHAN BUKU SKRIPSI </p>
                        </li>
                    @endif
                </ul>


                <!-- ----------BATAS -------------->

                @if (
                    $pendaftaran_skripsi->status_skripsi == 'USULAN JUDUL' ||
                        $pendaftaran_skripsi->status_skripsi == 'USULAN JUDUL DITOLAK' ||
                        $pendaftaran_skripsi->status_skripsi == 'USULKAN JUDUL ULANG')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 text-muted"> Tanggal diusulkan <br></span>
                            <span
                                class="mt-2 text-muted text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_created_usuljudul)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col ">

                        </div>
                        <div class="col">

                        </div>
                        <div class="col">

                        </div>
                    </div>
                @endif
                @if (
                    $pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO' ||
                        $pendaftaran_skripsi->status_skripsi == 'SEMPRO DIJADWALKAN')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col ">
                            <span class="mt-0 text-muted"> Tanggal diusulkan <br></span>
                            <span
                                class="mt-2 text-muted text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_created_sempro)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">

                        </div>
                        <div class="col">

                        </div>
                    </div>
                @endif

                @if ($pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO DISETUJUI')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_sempro_admin)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">

                        </div>
                        <div class="col">

                        </div>
                    </div>
                @endif

                @if (
                    $pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG' ||
                        $pendaftaran_skripsi->status_skripsi == 'JUDUL DISETUJUI')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-2 text-danger"> Batas Daftar Sempro<br></span>
                            <strong class="mt-2 text-danger"><strong class="text-bold"
                                    id="timer-batas-daftar-sempro"></strong></strong>
                        </div>
                        <div class="col">

                        </div>
                        <div class="col">

                        </div>
                    </div>
                @endif
                @if (
                    $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2' ||
                        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI' ||
                        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK' ||
                        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1' ||
                        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI' ||
                        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_semproselesai)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">

                            @if (
                                $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1' ||
                                    $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK')
                                <span class="mt-0 text-muted"> Tanggal diusulkan <br></span>
                                <span
                                    class="mt-2 text-muted text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_created_perpanjangan1)->translatedFormat('l, d F Y') }}</span>
                            @endif
                            @if ($pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2')
                                <span class="mt-0 text-muted"> Tanggal diusulkan <br></span>
                                <span
                                    class="mt-2 text-muted text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_created_perpanjangan2)->translatedFormat('l, d F Y') }}</span>
                            @endif
                            @if (
                                $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK' ||
                                    $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI')
                                <span class="mt-2 text-danger"> Batas Daftar Sidang<br></span>
                                <strong class="mt-2 text-danger"><strong class="text-bold"
                                        id="timer-batas-daftar-sidang-p1"></strong></strong>
                            @endif
                            @if ($pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI')
                                <span class="mt-2 text-danger"> Batas Daftar Sidang<br></span>
                                <strong class="mt-2 text-danger"><strong class="text-bold"
                                        id="timer-batas-daftar-sidang-p2"></strong></strong>
                            @endif

                        </div>
                        <div class="col">

                        </div>
                    </div>
                @endif

                @if (
                    $pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG ULANG' || $pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK' ||
                        $pendaftaran_skripsi->status_skripsi == 'SEMPRO SELESAI')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_semproselesai)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-2 text-danger"> Batas Daftar Sidang<br></span>
                            <strong class="mt-2 text-danger"><strong class="text-bold"
                                    id="timer-batas-daftar-sidang"></strong></strong>
                        </div>
                        <div class="col">
                        </div>
                    </div>
                @endif
                @if ($pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_semproselesai)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 text-muted"> Tanggal diusulkan <br></span>
                            <span
                                class="mt-2 text-muted text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_created_sidang)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                        </div>
                    </div>
                @endif
                @if ($pendaftaran_skripsi->status_skripsi == 'SIDANG DIJADWALKAN')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_semproselesai)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                        </div>
                        <div class="col">
                        </div>
                    </div>
                @endif
                @if ($pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_semproselesai)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_sidang_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                        </div>
                    </div>
                @endif

                @if ($pendaftaran_skripsi->status_skripsi == 'SIDANG SELESAI')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_semproselesai)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_selesai_sidang)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 text-danger"> Batas Unggah <br></span>
                            <strong class="mt-2 text-danger"><strong class="text-bold"
                                    id="timer-batas-buku-skripsi"></strong></strong>
                        </div>
                    </div>
                @endif
                @if ($pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN REVISI')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_semproselesai)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_selesai_sidang)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 text-muted"> Tanggal Usulan <br></span>
                            <span
                                class="mt-2 text-muted text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_created_revisi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                @endif

                @if (
                    $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK' ||
                        $pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                          <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_semproselesai)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_selesai_sidang)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 text-danger"> Batas Unggah Penyerahan Buku Skripsi <br></span>
                            <strong class="mt-2 text-danger"><strong class="text-bold"
                                    id="timer-batas-buku-skripsi"></strong></strong>
                        </div>
                    </div>
                @endif

                @if (
                    $pendaftaran_skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK' ||
                        $pendaftaran_skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                       <div class="col">
                          <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_semproselesai)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_selesai_sidang)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 text-muted"> Tanggal Usulan <br></span>
                            <span
                                class="mt-2 fw-bold text-muted">{{ Carbon::parse($pendaftaran_skripsi->tgl_created_sti_17)->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                @endif
                @if ($pendaftaran_skripsi->status_skripsi == 'SKRIPSI SELESAI' || $pendaftaran_skripsi->status_skripsi == 'LULUS')
                    <div class="row biru mb-4">
                        <div class="col">
                            <span class="mt-0 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat('l, d F Y') }}</span>
                        </div>
                       <div class="col">
                          <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_semproselesai)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-0 "> Tanggal Selesai <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_selesai_sidang)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col">
                            <span class="mt-2 "> Tanggal disetujui <br></span>
                            <span
                                class="mt-2 text-bold">{{ Carbon::parse($pendaftaran_skripsi->tgl_disetujui_sti_17_koordinator)->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                @endif


        </div>



        @if ($pendaftaran_skripsi->status_skripsi == 'USULAN JUDUL DITOLAK' || $pendaftaran_skripsi->status_skripsi == 'USULKAN JUDUL ULANG')
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle fw-bold"></i> <span
                        class="pl-2 fw-bold">{{ $pendaftaran_skripsi->alasan }}</span>, <span>Silahkan Usulkan Judul
                        Skripsi Ulang!</span>
                </div>
            </div>
        @endif

        @if ($pendaftaran_skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG')
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle fw-bold"></i> <span
                        class="pl-2 fw-bold">{{ $pendaftaran_skripsi->alasan }}</span>, <span>Silahkan Daftar Sempro
                        Ulang!</span>

                </div>
            </div>
        @endif
        <!-- @if ($pendaftaran_skripsi->status_skripsi == 'SEMPRO SELESAI')
    <div class="container">
        <div class="alert alert-info" role="alert">
            <img height="25" width="25" src="/assets/img/wink.png"  alt="..." class="bg-light rounded-pill"> <span class="pl-2 fw-bold">Silahkan Daftar Sidang Skripsi!</span>
            
        </div>
    </div>
    @endif -->

        @if ($pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG ULANG' || $pendaftaran_skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK')
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle fw-bold"></i> <span
                        class="pl-2 fw-bold">{{ $pendaftaran_skripsi->alasan }}</span>, <span>Silahkan Daftar Sidang
                        Skripsi Ulang!</span>

                </div>
            </div>
        @endif
        @if ($pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK')
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle fw-bold"></i> <span
                        class="pl-2 fw-bold">{{ $pendaftaran_skripsi->alasan }}</span>, <span>Silahkan Daftar Sidang atau
                        Usulkan Perpanjangan Waktu Skripsi Ulang!</span>

                </div>
            </div>
        @endif
        @if ($pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK')
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle fw-bold"></i> <span
                        class="pl-2 fw-bold">{{ $pendaftaran_skripsi->alasan }}</span>, <span>Silahkan Daftar Sidang atau
                        Usulkan Perpanjangan Waktu Skripsi Ulang!</span>

                </div>
            </div>
        @endif
        @if ($pendaftaran_skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK')
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle fw-bold"></i> <span
                        class="pl-2 fw-bold">{{ $pendaftaran_skripsi->alasan }}</span>, <span>Silahkan Unggah Bukti
                        Penyerahan Buku Skripsi atau Usulkan Perpanjangan Revisi Skripsi Ulang!</span>

                </div>
            </div>
        @endif
        @if ($pendaftaran_skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK')
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle fw-bold"></i> <span
                        class="pl-2 fw-bold">{{ $pendaftaran_skripsi->alasan }}</span>, <span>Silahkan Unggah Bukti
                        Penyerahan Buku Skripsi Ulang!</span>

                </div>
            </div>
        @endif

        <!-- @if (
            $pendaftaran_skripsi->status_skripsi == 'JUDUL DISETUJUI' &&
                $pendaftaran_skripsi->keterangan == 'Usulan Judul Skripsi Disetujui')
    @if ($pendaftaran_skripsi->status_skripsi == 'JUDUL DISETUJUI')
    <div class="container">
    <div class="alert alert-info" role="alert">
        <img height="25" width="25" src="/assets/img/wink.png"  alt="..." class="bg-light rounded-pill"> <span class="px-2">Silahkan Daftar Seminar Proposal. </span>

    </div>
    </div>
    @endif
    @endif -->


        <div class="card p-4">
            <table class="table table-responsive-lg table-bordered table-striped" width="100%">
                <thead class="table-dark">
                    <p class="alert-warning p-2"><i class="fas fa-exclamation-triangle px-2 fw-bold"></i> Jika Anda lewat
                        batas, Pembimbing berhak menghapus anda dari daftar bimbingan (Ajukan Judul baru)</p>
                    <tr>
                        <th class="text-center" scope="col">NIM</th>
                        <th class="text-center" scope="col">Nama</th>
                        <!-- <th class="text-center" scope="col">Konsentrasi</th> -->
                        <th class="text-center" scope="col">Jenis Usulan</th>
                        <th class="text-center" scope="col">Status Skripsi</th>
                        <th class="text-center" scope="col">Keterangan</th>
                        <th class="text-center" scope="col" style="padding-left: 50px; padding-right:50px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <div></div>
                    @foreach ($skripsi as $skripsi)
                        <tr>
                            <td class="text-center">{{ $skripsi->mahasiswa->nim }}</td>
                            <td class="text-center fw-bold">{{ $skripsi->mahasiswa->nama }}</td>
                            <!-- <td class="text-center">{{ $skripsi->mahasiswa->konsentrasi->nama_konsentrasi }}</td>                              -->
                            <td class="text-center">{{ $skripsi->jenis_usulan }}</td>
                            @if (
                                $skripsi->status_skripsi == 'USULAN JUDUL' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2' ||
                                    $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI')
                                <td class="text-center bg-secondary">{{ $skripsi->status_skripsi }}</td>
                            @endif
                            @if (
                                $skripsi->status_skripsi == 'JUDUL DISETUJUI' ||
                                    $skripsi->status_skripsi == 'SEMPRO SELESAI' ||
                                    $skripsi->status_skripsi == 'SIDANG SELESAI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI' ||
                                    $skripsi->status_skripsi == 'SKRIPSI SELESAI' ||
                                    $skripsi->status_skripsi == 'LULUS' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO DISETUJUI')
                                <td class="text-center bg-info">{{ $skripsi->status_skripsi }}</td>
                            @endif
                            @if ($skripsi->status_skripsi == 'SEMPRO DIJADWALKAN' || $skripsi->status_skripsi == 'SIDANG DIJADWALKAN')
                                <td class="text-center bg-success">{{ $skripsi->status_skripsi }}</td>
                            @endif
                            @if (
                                $skripsi->status_skripsi == 'USULAN JUDUL DITOLAK' ||
                                    $skripsi->status_skripsi == 'USULKAN JUDUL ULANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG ULANG'
                                    || $skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK'||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK' ||
                                    $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK')
                                <td class="text-center bg-danger">{{ $skripsi->status_skripsi }}</td>
                            @endif

                            @if (
                                $skripsi->status_skripsi == 'USULAN JUDUL DITOLAK' ||
                                    $skripsi->status_skripsi == 'USULKAN JUDUL ULANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG ULANG'
                                    || $skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK' ||
                                    $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK')
                                <td class="text-center text-danger">{{ $skripsi->keterangan }}</td>
                            @else
                                <td class="text-center">{{ $skripsi->keterangan }}</td>
                            @endif

                            @if (
                                $skripsi->status_skripsi == 'USULAN JUDUL' ||
                                    $skripsi->status_skripsi == 'JUDUL DISETUJUI' ||
                                    $skripsi->status_skripsi == 'USULAN JUDUL DITOLAK' ||
                                    $skripsi->status_skripsi == 'USULKAN JUDUL ULANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG')
                                <td class="text-center">
                                    <a href="/usuljudul/detail/{{ $skripsi->id }}" class="badge btn btn-info p-1 mb-1"
                                        data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                    @if ($skripsi->status_skripsi == 'USULAN JUDUL DITOLAK' || $skripsi->status_skripsi == 'USULKAN JUDUL ULANG')
                                        <a href="/usuljudul-ulang/create" class="badge p-1  mb-1" data-bs-toggle="tooltip"
                                            title="Usul Judul Ulang"><img height="25" width="25"
                                                src="/assets/img/add.png" alt="..." class="zoom-image"></a>
                                        <!-- <a href="/usuljudul-ulang/create/{{ $skripsi->id }}" class="badge p-1  mb-1" data-bs-toggle="tooltip" title="Daftar Seminar Proposal"><img height="25" width="25" src="/assets/img/add.png"  alt="..." class="zoom-image"></a> -->
                                    @endif
                                    @if ($skripsi->status_skripsi == 'JUDUL DISETUJUI')
                                        <a href="/daftar-sempro/create/{{ $skripsi->id }}" class="badge p-1  mb-1"
                                            data-bs-toggle="tooltip" title="Daftar Seminar Proposal"><img height="25"
                                                width="25" src="/assets/img/add.png" alt="..."
                                                class="zoom-image"></a>
                                    @endif
                                    @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG')
                                        <a href="/daftar-sempro/create/{{ $skripsi->id }}" class="badge p-1  mb-1"
                                            data-bs-toggle="tooltip" title="Daftar Seminar Proposal"><img height="25"
                                                width="25" src="/assets/img/add.png" alt="..."
                                                class="zoom-image"></a>
                                    @endif

                                </td>
                            @endif

                            @if (
                                $skripsi->status_skripsi == 'DAFTAR SEMPRO' ||
                                    $skripsi->status_skripsi == 'SEMPRO DIJADWALKAN' ||
                                    $skripsi->status_skripsi == 'SEMPRO SELESAI' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG ULANG' 
                                    || $skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO DISETUJUI')
                                <td class="text-center">
                                    <a href="/daftar-sempro/detail/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1 mb-1" data-bs-toggle="tooltip"><i
                                            class="fas fa-info-circle"></i> <span class="custom-tooltip">Lihat
                                            Detail</span></a>

                                    @if ($skripsi->status_skripsi == 'SEMPRO DIJADWALKAN')
                                        <a href="/jadwal/mahasiswa" class="badge p-1 mb-1" data-bs-toggle="tooltip"
                                            title="Lihat Jadwal"><img height="25" width="25"
                                                src="/assets/img/calendar.png" alt="..." class="zoom-image"></a>
                                    @endif
                                    @if ($skripsi->status_skripsi == 'SEMPRO SELESAI')
                                        <a href="/seminar" class="badge btn btn-dark p-1 mb-1"
                                            data-bs-toggle="tooltip"><i class="fas fa-history"></i> <span
                                                class="custom-tooltip">Riwayat Seminar</span></a>
                                        <a type="button" data-toggle="modal" data-target="#GFG">
                                            <img height="25" width="25" src="/assets/img/clockplus.png"
                                                alt="..." class=""> <span class="custom-tooltip">Permohonan
                                                Perpanjangan 1 Waktu Skripsi</span></a>


                                        <div class="modal fade" id="GFG">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content ">
                                                    <div class="modal-header bg-secondary justify-content-center">
                                                        <h5 class="modal-title ">
                                                            Perpanjangan Waktu Skripsi ke-1
                                                        </h5>

                                                    </div>
                                                    <div class="modal-body ">
                                                        <form action="/perpanjangan1-skripsi/create/{{ $skripsi->id }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @method('put')
                                                            @csrf
                                                            <div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="mb-3">
                                                                            <label for="formFile"
                                                                                class="form-label float-start">STI/TE-22/Surat
                                                                                Pernyataan Perpanjangan Skripsi<span
                                                                                    class="text-danger">*</span> <small
                                                                                    class="text-secondary">( Format .pdf |
                                                                                    Maks. 200 KB ) </small></label>
                                                                            <input name="sti_22"
                                                                                class="form-control @error('sti_22') is-invalid @enderror"
                                                                                value="{{ old('sti_22') }}"
                                                                                type="file" id="formFile" required
                                                                                autofocus>

                                                                            @error('sti_22')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>


                                                                        <!-- <button type="submit" class="btn btn-success px-3 py-2 mt-4 float-end" data-bs-toggle="modal" data-bs-target="#ModalApprove">Kirim</button> -->

                                                                        <a href="#ModalApprove" data-toggle="modal"
                                                                            class="btn mt-4 btn-lg btn-success float-right">Kirim</a>
                                                                        <div class="modal fade"id="ModalApprove">
                                                                            <div
                                                                                class="modal-dialog modal-dialog-centered">
                                                                                <div class="modal-content shadow-sm">
                                                                                    <div class="modal-body">
                                                                                        <div
                                                                                            class="container px-5 pt-5 pb-2">
                                                                                            <h3 class="text-center">Apakah
                                                                                                Anda Yakin?</h3>
                                                                                            <p class="text-center">Jika
                                                                                                belum, silahkan cek kembali
                                                                                                Data yang akan Anda Kirim.
                                                                                            </p>
                                                                                            <div class="row text-center">
                                                                                                <div class="col-3">
                                                                                                </div>
                                                                                                <div class="col-3">
                                                                                                    <button type="button"
                                                                                                        class="btn p-2 px-3 btn-secondary"
                                                                                                        data-dismiss="modal">Tidak</button>
                                                                                                </div>
                                                                                                <div class="col-3">
                                                                                                    <button type="submit"
                                                                                                        class="btn btn-success py-2 px-3">Kirim</button>
                                                                                                </div>
                                                                                                <div class="col-3">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer ">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <a href="/daftar-sidang/create/{{ $skripsi->id }}" class="mb-1"
                                            data-bs-toggle="tooltip"><img height="25" width="25"
                                                src="/assets/img/add.png" alt="..." class="zoom-image"> <span
                                                class="custom-tooltip">Daftar Sidang skripsi</span></a>
                                    @endif


                                    @if ($skripsi->status_skripsi == 'DAFTAR SIDANG ULANG' || $skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK')
                                        <a href="/seminar" class="badge btn btn-dark p-1 mb-1" data-bs-toggle="tooltip"
                                            title="Lihat Riwayat Seminar"><i class="fas fa-history"></i></a>
                                        <a href="/daftar-sidang/create/{{ $skripsi->id }}" class="badge p-1 mb-1"
                                            data-bs-toggle="tooltip" title="Daftar Sidang skripsi"><img height="25"
                                                width="25" src="/assets/img/add.png" alt="..."
                                                class="zoom-image"></a>
                                    @endif

                                </td>
                            @endif


                            @if (
                                $skripsi->status_skripsi == 'PERPANJANGAN 1' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI')
                                <td class="text-center">

                                    <a href="/perpanjangan-1/detail/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1 mb-1" data-bs-toggle="tooltip"
                                        title="Lihat Detail"><i class="fas fa-info-circle"></i></a>

                                    @if ($skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK')
                                        <a type="button" data-toggle="modal"
                                            title="Permohonan Perpanjangan 1 Waktu Skripsi" data-target="#GFG">
                                            <img height="25" width="25" src="/assets/img/clockplus.png"
                                                alt="..." class=""> </a>


                                        <div class="modal fade" id="GFG">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content ">
                                                    <div class="modal-header bg-secondary justify-content-center">
                                                        <h5 class="modal-title ">
                                                            Perpanjangan Waktu Skripsi ke-1
                                                        </h5>

                                                    </div>
                                                    <div class="modal-body ">
                                                        <form action="/perpanjangan1-skripsi/create/{{ $skripsi->id }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @method('put')
                                                            @csrf
                                                            <div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="mb-3">
                                                                            <label for="formFile"
                                                                                class="form-label float-start">STI/TE-22/Surat
                                                                                Pernyataan Perpanjangan Skripsi<span
                                                                                    class="text-danger">*</span> <small
                                                                                    class="text-secondary">( Format .pdf |
                                                                                    Maks. 200 KB ) </small></label>
                                                                            <input name="sti_22"
                                                                                class="form-control @error('sti_22') is-invalid @enderror"
                                                                                value="{{ old('sti_22') }}"
                                                                                type="file" id="formFile" required
                                                                                autofocus>

                                                                            @error('sti_22')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>


                                                                        <!-- <button type="submit" class="btn btn-success px-3 py-2 mt-4 float-end" data-bs-toggle="modal" data-bs-target="#ModalApprove">Kirim</button> -->

                                                                        <a href="#ModalApprove" data-toggle="modal"
                                                                            class="btn mt-4 btn-lg btn-success float-right">Kirim</a>
                                                                        <div class="modal fade"id="ModalApprove">
                                                                            <div
                                                                                class="modal-dialog modal-dialog-centered">
                                                                                <div class="modal-content shadow-sm">
                                                                                    <div class="modal-body">
                                                                                        <div
                                                                                            class="container px-5 pt-5 pb-2">
                                                                                            <h3 class="text-center">Apakah
                                                                                                Anda Yakin?</h3>
                                                                                            <p class="text-center">Jika
                                                                                                belum, silahkan cek kembali
                                                                                                Data yang akan Anda Kirim.
                                                                                            </p>
                                                                                            <div class="row text-center">
                                                                                                <div class="col-3">
                                                                                                </div>
                                                                                                <div class="col-3">
                                                                                                    <button type="button"
                                                                                                        class="btn p-2 px-3 btn-secondary"
                                                                                                        data-dismiss="modal">Tidak</button>
                                                                                                </div>
                                                                                                <div class="col-3">
                                                                                                    <button type="submit"
                                                                                                        class="btn btn-success py-2 px-3">Kirim</button>
                                                                                                </div>
                                                                                                <div class="col-3">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer ">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="/daftar-sidang/create/{{ $skripsi->id }}" class="mb-1"
                                            data-bs-toggle="tooltip" title="Daftar Sidang skripsi"><img height="25"
                                                width="25" src="/assets/img/add.png" alt="..."
                                                class="zoom-image"></a>

                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI')
                                <a href="/seminar" class="badge btn btn-dark p-1 mb-1" data-bs-toggle="tooltip"
                                    title="Lihat Riwayat Seminar"><i class="fas fa-history"></i></a>
                                <a type="button" data-toggle="modal" title="Permohonan Perpanjangan 2 Waktu Skripsi"
                                    data-target="#GFG">
                                    <img height="25" width="25" src="/assets/img/clockplus2.png" alt="..."
                                        class=""> </a>


                                <div class="modal fade" id="GFG">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content ">
                                            <div class="modal-header bg-secondary justify-content-center">
                                                <h5 class="modal-title ">
                                                    Perpanjangan Waktu Skripsi ke-2
                                                </h5>

                                            </div>
                                            <div class="modal-body ">
                                                <form action="/perpanjangan2-skripsi/create/{{ $skripsi->id }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @method('put')
                                                    @csrf
                                                    <div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="mb-3">
                                                                    <label for="formFile"
                                                                        class="form-label float-start">STI/TE-22/Surat
                                                                        Pernyataan Perpanjangan Skripsi<span
                                                                            class="text-danger">*</span> <small
                                                                            class="text-secondary">( Format .pdf | Maks.
                                                                            200 KB ) </small></label>
                                                                    <input name="sti_22"
                                                                        class="form-control @error('sti_22') is-invalid @enderror"
                                                                        value="{{ old('sti_22') }}" type="file"
                                                                        id="formFile" required autofocus>

                                                                    @error('sti_22')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <!-- <button type="submit" class="btn btn-success px-3 py-2 mt-4 float-end" data-bs-toggle="modal" data-bs-target="#ModalApprove">Kirim</button> -->

                                                                <a href="#ModalApprove" data-toggle="modal"
                                                                    class="btn mt-4 btn-lg btn-success float-right">Kirim</a>
                                                                <div class="modal fade"id="ModalApprove">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content shadow-sm">
                                                                            <div class="modal-body">
                                                                                <div class="container px-5 pt-5 pb-2">
                                                                                    <h3 class="text-center">Apakah Anda
                                                                                        Yakin?</h3>
                                                                                    <p class="text-center">Jika belum,
                                                                                        silahkan cek kembali Data yang akan
                                                                                        Anda Kirim.</p>
                                                                                    <div class="row text-center">
                                                                                        <div class="col-3">
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <button type="button"
                                                                                                class="btn p-2 px-3 btn-secondary"
                                                                                                data-dismiss="modal">Tidak</button>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <button type="submit"
                                                                                                class="btn btn-success py-2 px-3">Kirim</button>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer ">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="/daftar-sidang/create/{{ $skripsi->id }}" class="mb-1"
                                    data-bs-toggle="tooltip" title="Daftar Sidang skripsi"><img height="25"
                                        width="25" src="/assets/img/add.png" alt="..." class="zoom-image"></a>

                                </td>
                            @endif
                            @if ($skripsi->status_skripsi == 'PERPANJANGAN 1')
                                <a href="/seminar" class="badge btn btn-dark p-1 mb-1" data-bs-toggle="tooltip"
                                    title="Lihat Riwayat Seminar"><i class="fas fa-history"></i></a>


                                </td>
                            @endif
                    @endif
                    @if (
                        $skripsi->status_skripsi == 'PERPANJANGAN 2' ||
                            $skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK' ||
                            $skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI')
                        <td class="text-center">

                            <a href="/perpanjangan-2/detail/{{ $skripsi->id }}" class="badge btn btn-info p-1 mb-1"
                                data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>

                            @if ($skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK')
                                <a type="button" data-toggle="modal" title="Permohonan Perpanjangan 2 Waktu Skripsi"
                                    data-target="#GFG">
                                    <img height="25" width="25" src="/assets/img/clockplus2.png" alt="..."
                                        class=""> </a>


                                <div class="modal fade" id="GFG">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content ">
                                            <div class="modal-header bg-secondary justify-content-center">
                                                <h5 class="modal-title ">
                                                    Perpanjangan Waktu Skripsi ke-2
                                                </h5>

                                            </div>
                                            <div class="modal-body ">
                                                <form action="/perpanjangan2-skripsi/create/{{ $skripsi->id }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @method('put')
                                                    @csrf
                                                    <div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="mb-3">
                                                                    <label for="formFile"
                                                                        class="form-label float-start">STI/TE-22/Surat
                                                                        Pernyataan Perpanjangan Skripsi<span
                                                                            class="text-danger">*</span> <small
                                                                            class="text-secondary">( Format .pdf | Maks.
                                                                            200 KB ) </small></label>
                                                                    <input name="sti_22"
                                                                        class="form-control @error('sti_22') is-invalid @enderror"
                                                                        value="{{ old('sti_22') }}" type="file"
                                                                        id="formFile" required autofocus>

                                                                    @error('sti_22')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>


                                                                <!-- <button type="submit" class="btn btn-success px-3 py-2 mt-4 float-end" data-bs-toggle="modal" data-bs-target="#ModalApprove">Kirim</button> -->

                                                                <a href="#ModalApprove" data-toggle="modal"
                                                                    class="btn mt-4 btn-lg btn-success float-right">Kirim</a>
                                                                <div class="modal fade"id="ModalApprove">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content shadow-sm">
                                                                            <div class="modal-body">
                                                                                <div class="container px-5 pt-5 pb-2">
                                                                                    <h3 class="text-center">Apakah Anda
                                                                                        Yakin?</h3>
                                                                                    <p class="text-center">Jika belum,
                                                                                        silahkan cek kembali Data yang akan
                                                                                        Anda Kirim.</p>
                                                                                    <div class="row text-center">
                                                                                        <div class="col-3">
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <button type="button"
                                                                                                class="btn p-2 px-3 btn-secondary"
                                                                                                data-dismiss="modal">Tidak</button>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <button type="submit"
                                                                                                class="btn btn-success py-2 px-3">Kirim</button>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer ">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="/daftar-sidang/create/{{ $skripsi->id }}" class="mb-1"
                                    data-bs-toggle="tooltip" title="Daftar Sidang skripsi"><img height="25"
                                        width="25" src="/assets/img/add.png" alt="..." class="zoom-image"></a>

                        </td>
                    @endif

                    @if ($skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI')
                        <a href="/seminar" class="badge btn btn-dark p-1 mb-1" data-bs-toggle="tooltip"
                            title="Lihat Riwayat Seminar"><i class="fas fa-history"></i></a>


                        <a href="/daftar-sidang/create/{{ $skripsi->id }}" class="mb-1" data-bs-toggle="tooltip"
                            title="Daftar Sidang skripsi"><img height="25" width="25" src="/assets/img/add.png"
                                alt="..." class="zoom-image"></a>

                        </td>
                    @endif
                    @if ($skripsi->status_skripsi == 'PERPANJANGAN 2')
                        <a href="/seminar" class="badge btn btn-dark p-1 mb-1" data-bs-toggle="tooltip"
                            title="Lihat Riwayat Seminar"><i class="fas fa-history"></i></a>


                        </td>
                    @endif
                    @endif

                    @if (
                        $skripsi->status_skripsi == 'DAFTAR SIDANG' ||
                            $skripsi->status_skripsi == 'SIDANG DIJADWALKAN' ||
                            $skripsi->status_skripsi == 'SIDANG SELESAI' ||
                            $skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI')
                        <td class="text-center">

                            <a href="/daftar-sidang/detail/{{ $skripsi->id }}" class="badge btn btn-info p-1 mb-1"
                                data-bs-toggle="tooltip"><i class="fas fa-info-circle"></i> <span
                                    class="custom-tooltip">Lihat Detail</span></a>


                            @if ($skripsi->status_skripsi == 'SIDANG DIJADWALKAN')
                                <a href="/jadwal/mahasiswa" class="badge p-1" data-bs-toggle="tooltip"
                                    title="Lihat Jadwal"><img height="25" width="25"
                                        src="/assets/img/calendar.png" alt="..." class="zoom-image"></a>
                            @endif


                            @if ($skripsi->status_skripsi == 'SIDANG SELESAI')
                                <a href="/seminar" class="badge btn btn-dark p-1 mb-1" data-bs-toggle="tooltip"
                                    title="Riwayat Seminar skripsi"><i class="fas fa-history"></i></a>
                                <!-- <a href="/perpanjangan/revisi/create/{{ $skripsi->id }}" class=" mb-1"data-bs-toggle="tooltip" title="Unggah STI/TE-23/SURAT PERNYATAAN PERPANJANGAN REVISI SKRIPSI"><img height="25" width="25" src="/assets/img/clockb.png"  alt="..." class=""></a> -->

                                <a type="button" data-toggle="modal" title="Permohonan Perpanjangan 2 Waktu Skripsi"
                                    data-target="#GFG">
                                    <img height="25" width="25" src="/assets/img/clockb.png" alt="..."
                                        class=""> </a>


                                <div class="modal fade" id="GFG">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content ">
                                            <div class="modal-header bg-secondary justify-content-center">
                                                <h5 class="modal-title">
                                                    Perpanjangan Revisi Skripsi
                                                </h5>

                                            </div>
                                            <div class="modal-body ">
                                                <form action="/perpanjangan-revisi/create/{{ $skripsi->id }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @method('put')
                                                    @csrf
                                                    <div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="mb-3">
                                                                    <label for="formFile"
                                                                        class="form-label float-start">STI/TE-23/Surat
                                                                        Perpanjangan Waktu Revisi Skripsi<span
                                                                            class="text-danger">*</span> <small
                                                                            class="text-secondary">( Format .pdf | Maks.
                                                                            200 KB ) </small></label>
                                                                    <input name="sti_23"
                                                                        class="form-control @error('sti_23') is-invalid @enderror"
                                                                        value="{{ old('sti_23') }}" type="file"
                                                                        id="formFile" required autofocus>

                                                                    @error('sti_23')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>


                                                                <!-- <button type="submit" class="btn btn-success px-3 py-2 mt-4 float-end" data-bs-toggle="modal" data-bs-target="#ModalApprove">Kirim</button> -->

                                                                <a href="#ModalApprove" data-toggle="modal"
                                                                    class="btn mt-4 btn-lg btn-success float-right">Kirim</a>
                                                                <div class="modal fade"id="ModalApprove">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content shadow-sm">
                                                                            <div class="modal-body">
                                                                                <div class="container px-5 pt-5 pb-2">
                                                                                    <h3 class="text-center">Apakah Anda
                                                                                        Yakin?</h3>
                                                                                    <p class="text-center">Jika belum,
                                                                                        silahkan cek kembali Data yang akan
                                                                                        Anda Kirim.</p>
                                                                                    <div class="row text-center">
                                                                                        <div class="col-3">
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <button type="button"
                                                                                                class="btn p-2 px-3 btn-secondary"
                                                                                                data-dismiss="modal">Tidak</button>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <button type="submit"
                                                                                                class="btn btn-success py-2 px-3">Kirim</button>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer ">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="/penyerahan-buku-skripsi/create/{{ $skripsi->id }}" class="mb-1"
                                    data-bs-toggle="tooltip" title="Unggah STI/TE-17/Bukti Penyerahan Buku Skripsi"><img
                                        height="25" width="25" src="/assets/img/add.png" alt="..."
                                        class="zoom-image"></a>
                            @endif
                        </td>
                    @endif
                    @if (
                        $skripsi->status_skripsi == 'PERPANJANGAN REVISI' ||
                            $skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI' ||
                            $skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK')
                        <td class="text-center">

                            <a href="/perpanjangan-revisi/detail/{{ $skripsi->id }}"
                                class="badge btn btn-info p-1 mb-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                    class="fas fa-info-circle"></i></a>
                            <a href="/seminar" class="badge btn btn-dark p-1 mb-1" data-bs-toggle="tooltip"
                                title="Riwayat Seminar skripsi"><i class="fas fa-history"></i></a>

                            @if ($skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI')
                                <a href="/penyerahan-buku-skripsi/create/{{ $skripsi->id }}" class="badge  mb-1"
                                    data-bs-toggle="tooltip" title="Unggah STI/TE-17/Bukti Penyerahan Buku Skripsi"><img
                                        height="25" width="25" src="/assets/img/add.png" alt="..."
                                        class="zoom-image"></a>
                            @endif

                            @if ($skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK')
                                <a type="button" data-toggle="modal" title="Permohonan Perpanjangan 2 Waktu Skripsi"
                                    data-target="#GFG">
                                    <img height="25" width="25" src="/assets/img/clockb.png" alt="..."
                                        class=""> </a>


                                <div class="modal fade" id="GFG">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content ">
                                            <div class="modal-header bg-secondary justify-content-center">
                                                <h5 class="modal-title">
                                                    Perpanjangan Revisi Skripsi
                                                </h5>

                                            </div>
                                            <div class="modal-body ">
                                                <form action="/perpanjangan-revisi/create/{{ $skripsi->id }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @method('put')
                                                    @csrf
                                                    <div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="mb-3">
                                                                    <label for="formFile"
                                                                        class="form-label float-start">STI/TE-23/Surat
                                                                        Perpanjangan Waktu Revisi Skripsi<span
                                                                            class="text-danger">*</span> <small
                                                                            class="text-secondary">( Format .pdf | Maks.
                                                                            200 KB ) </small></label>
                                                                    <input name="sti_23"
                                                                        class="form-control @error('sti_23') is-invalid @enderror"
                                                                        value="{{ old('sti_23') }}" type="file"
                                                                        id="formFile" required autofocus>

                                                                    @error('sti_23')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>


                                                                <!-- <button type="submit" class="btn btn-success px-3 py-2 mt-4 float-end" data-bs-toggle="modal" data-bs-target="#ModalApprove">Kirim</button> -->

                                                                <a href="#ModalApprove" data-toggle="modal"
                                                                    class="btn mt-4 btn-lg btn-success float-right">Kirim</a>
                                                                <div class="modal fade"id="ModalApprove">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content shadow-sm">
                                                                            <div class="modal-body">
                                                                                <div class="container px-5 pt-5 pb-2">
                                                                                    <h3 class="text-center">Apakah Anda
                                                                                        Yakin?</h3>
                                                                                    <p class="text-center">Jika belum,
                                                                                        silahkan cek kembali Data yang akan
                                                                                        Anda Kirim.</p>
                                                                                    <div class="row text-center">
                                                                                        <div class="col-3">
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <button type="button"
                                                                                                class="btn p-2 px-3 btn-secondary"
                                                                                                data-dismiss="modal">Tidak</button>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <button type="submit"
                                                                                                class="btn btn-success py-2 px-3">Kirim</button>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer ">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="/penyerahan-buku-skripsi/create/{{ $skripsi->id }}" class=" mb-1"
                                    data-bs-toggle="tooltip" title="Unggah STI/TE-17/Bukti Penyerahan Buku Skripsi"><img
                                        height="25" width="25" src="/assets/img/add.png" alt="..."
                                        class="zoom-image"></a>
                            @endif
                        </td>
                    @endif

                    @if (
                        $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI' ||
                            $skripsi->status_skripsi == 'SKRIPSI SELESAI' ||
                            $skripsi->status_skripsi == 'LULUS')
                        <td class="text-center">
                            <a href="/bukti-buku-skripsi/detail/{{ $skripsi->id }}"
                                class="badge btn btn-info p-1 mb-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                    class="fas fa-info-circle"></i></a>
                            <a href="/seminar" class="badge btn btn-dark p-1 mb-1" data-bs-toggle="tooltip"
                                title="Lihat Riwayat Seminar"><i class="fas fa-history"></i></a>
                        </td>
                    @endif
                    @if ($skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK')
                        <td class="text-center">
                            <a href="/bukti-buku-skripsi/detail/{{ $skripsi->id }}"
                                class="badge btn btn-info p-1 mb-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                    class="fas fa-info-circle"></i></a>
                            <a href="/seminar" class="badge btn btn-dark p-1 mb-1" data-bs-toggle="tooltip"
                                title="Lihat Riwayat Seminar"><i class="fas fa-history"></i></a>

                            <a href="/penyerahan-buku-skripsi/create/{{ $skripsi->id }}" class="badge  mb-1"
                                data-bs-toggle="tooltip" title="Unggah STI/TE-17/Bukti Penyerahan Buku Skripsi"><img
                                    height="25" width="25" src="/assets/img/add.png" alt="..."
                                    class="zoom-image"></a>
                        </td>
                    @endif

                    </tr>
                    @endforeach

                </tbody>


            </table>


        </div>
    </div>
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

<!-- Script untuk mengurangi jumlah hari setiap hari -->
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endpush()
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>
@endpush()

@push('scripts')
    <script>
        // Fungsi untuk menghitung waktu tersisa
        moment.locale('id');

        function hitungWaktuBatasDaftarSempro(targetDate) {
            // Mengubah tanggal disetujui KP menjadi objek moment
            var tanggalDisetujuiUsulJudul = moment(targetDate);

            var tanggalTerakhirDaftar = tanggalDisetujuiUsulJudul.add(6, 'months');

            var formatTanggalTerakhirDaftar = tanggalTerakhirDaftar.format('dddd, D MMMM YYYY');

            document.getElementById("timer-batas-daftar-sempro").textContent = formatTanggalTerakhirDaftar;
        }

        hitungWaktuBatasDaftarSempro("{{ $tanggalDisetujuiUsulJudul }}");
    </script>
@endpush()

@push('scripts')
    <script>
        // Fungsi untuk menghitung waktu tersisa
        moment.locale('id');

        // Fungsi untuk menghitung waktu tersisa
        function hitungWaktuTersisa(targetDate) {
            // Mengubah tanggal disetujui KP menjadi objek moment
            var tanggalGagalSempro = moment(targetDate);

            // Menambahkan satu bulan ke tanggal disetujui KP
            var tanggalSeminarKP = tanggalGagalSempro.add(1, 'months');

            // Mendapatkan format tanggal yang diinginkan (Hari, 1 Juli 2023)
            var formatTanggal = tanggalSeminarKP.format('dddd, D MMMM YYYY');

            // Menampilkan tanggal di dalam elemen dengan id "timer"
            document.getElementById("timer-gagal-sempro").textContent = formatTanggal;
        }

        hitungWaktuTersisa("{{ $tanggalGagalSempro }}");

        function hitungWaktuBatas(targetDate) {
            // Mengubah tanggal disetujui KP menjadi objek moment
            var tanggalGagalSempro = moment(targetDate);


            var tanggalTerakhirDaftarSeminarKP = tanggalGagalSempro.add(6, 'months');

            // Mendapatkan format tanggal yang diinginkan (Hari, 1 Juli 2023)

            var formatTanggalTerakhirDaftar = tanggalTerakhirDaftarSeminarKP.format('dddd, D MMMM YYYY');

            // Menampilkan tanggal di dalam elemen dengan id "timer"
            document.getElementById("timer-batas-daftar-gagal-sempro").textContent = formatTanggalTerakhirDaftar;
        }

        // Memanggil fungsi hitungWaktuBatas dengan tanggal disetujui KP

        hitungWaktuBatas("{{ $tanggalGagalSempro }}");
    </script>
@endpush()

@push('scripts')
    <script>
        moment.locale('id');

        function hitungWaktuBatas(targetDate) {
            // Mengubah tanggal disetujui KP menjadi objek moment
            var tanggalSelesaiSempro = moment(targetDate);


            var tanggalTerakhirDaftarSeminar = tanggalSelesaiSempro.add(6, 'months');

            // Mendapatkan format tanggal yang diinginkan (Hari, 1 Juli 2023)

            var formatTanggalTerakhirDaftar = tanggalTerakhirDaftarSeminar.format('dddd, D MMMM YYYY');

            // Menampilkan tanggal di dalam elemen dengan id "timer"
            document.getElementById("timer-batas-daftar-sidang").textContent = formatTanggalTerakhirDaftar;
        }

        // Memanggil fungsi hitungWaktuBatas dengan tanggal disetujui KP

        hitungWaktuBatas("{{ $tanggalSelesaiSempro }}");
    </script>
@endpush()

@push('scripts')
    <script>
        moment.locale('id');

        function hitungWaktuBatas(targetDate) {
            // Mengubah tanggal disetujui KP menjadi objek moment
            var tanggalSelesaiSempro = moment(targetDate);


            var tanggalTerakhirDaftarSeminar = tanggalSelesaiSempro.add(9, 'months');

            // Mendapatkan format tanggal yang diinginkan (Hari, 1 Juli 2023)

            var formatTanggalTerakhirDaftar = tanggalTerakhirDaftarSeminar.format('dddd, D MMMM YYYY');

            // Menampilkan tanggal di dalam elemen dengan id "timer"
            document.getElementById("timer-batas-daftar-sidang-p1").textContent = formatTanggalTerakhirDaftar;
        }

        // Memanggil fungsi hitungWaktuBatas dengan tanggal disetujui KP

        hitungWaktuBatas("{{ $tanggalSelesaiSempro }}");
    </script>
@endpush()

@push('scripts')
    <script>
        moment.locale('id');

        function hitungWaktuBatas(targetDate) {
            // Mengubah tanggal disetujui KP menjadi objek moment
            var tanggalSelesaiSempro = moment(targetDate);


            var tanggalTerakhirDaftarSeminar = tanggalSelesaiSempro.add(12, 'months');

            // Mendapatkan format tanggal yang diinginkan (Hari, 1 Juli 2023)

            var formatTanggalTerakhirDaftar = tanggalTerakhirDaftarSeminar.format('dddd, D MMMM YYYY');

            // Menampilkan tanggal di dalam elemen dengan id "timer"
            document.getElementById("timer-batas-daftar-sidang-p2").textContent = formatTanggalTerakhirDaftar;
        }

        // Memanggil fungsi hitungWaktuBatas dengan tanggal disetujui KP

        hitungWaktuBatas("{{ $tanggalSelesaiSempro }}");
    </script>
@endpush()

@push('scripts')
    <script>
        // Fungsi untuk menghitung waktu tersisa
        moment.locale('id');

        // Fungsi untuk menghitung waktu tersisa
        function hitungWaktuTersisa(targetDate) {
            // Mengubah tanggal disetujui KP menjadi objek moment
            var tanggalDitolakSidang = moment(targetDate);

            // Menambahkan satu bulan ke tanggal disetujui KP
            var tanggalSeminar = tanggalDitolakSidang.add(1, 'months');

            // Mendapatkan format tanggal yang diinginkan (Hari, 1 Juli 2023)
            var formatTanggal = tanggalSeminar.format('dddd, D MMMM YYYY');

            // Menampilkan tanggal di dalam elemen dengan id "timer"
            document.getElementById("timer-sidang-ulang").textContent = formatTanggal;
        }

        hitungWaktuTersisa("{{ $tanggalDitolakSidang }}");

        function hitungWaktuBatas(targetDate) {
            // Mengubah tanggal disetujui KP menjadi objek moment
            var tanggalDitolakSidang = moment(targetDate);


            var tanggalTerakhirDaftarSeminar = tanggalDitolakSidang.add(6, 'months');

            // Mendapatkan format tanggal yang diinginkan (Hari, 1 Juli 2023)

            var formatTanggalTerakhirDaftar = tanggalTerakhirDaftarSeminar.format('dddd, D MMMM YYYY');

            // Menampilkan tanggal di dalam elemen dengan id "timer"
            document.getElementById("timer-batas-daftar-sidang-ulang").textContent = formatTanggalTerakhirDaftar;
        }

        // Memanggil fungsi hitungWaktuBatas dengan tanggal disetujui KP

        hitungWaktuBatas("{{ $tanggalDitolakSidang }}");
    </script>
@endpush()

@push('scripts')
    <script>
        // Fungsi untuk menghitung waktu tersisa
        moment.locale('id');

        function hitungWaktuBatas(targetDate) {
            // Mengubah tanggal disetujui KP menjadi objek moment
            var tanggalDisetujuiSidang = moment(targetDate);


            var tanggalTerakhirDaftarSeminar = tanggalDisetujuiSidang.add(1, 'months');

            // Mendapatkan format tanggal yang diinginkan (Hari, 1 Juli 2023)

            var formatTanggalTerakhirDaftar = tanggalTerakhirDaftarSeminar.format('dddd, D MMMM YYYY');

            // Menampilkan tanggal di dalam elemen dengan id "timer"
            document.getElementById("timer-batas-buku-skripsi").textContent = formatTanggalTerakhirDaftar;
        }

        // Memanggil fungsi hitungWaktuBatas dengan tanggal disetujui KP

        hitungWaktuBatas("{{ $tanggalDisetujuiSidang }}");
    </script>
@endpush()
