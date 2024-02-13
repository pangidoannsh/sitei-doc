@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Persetujuan Kerja Praktek dan Skripsi
@endsection

@section('sub-title')
    Persetujuan Kerja Praktek dan Skripsi
@endsection

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="container card  p-4">

        <ol class="breadcrumb col-lg-12">
            <li>
                <a href="/persetujuan-kp-skripsi" class="breadcrumb-item active fw-bold text-success px-1">Persetujuan
                    @if (Auth::guard('dosen')->user()->role_id == 6 ||
                        Auth::guard('dosen')->user()->role_id == 7 ||
                        Auth::guard('dosen')->user()->role_id == 8 ||
                        Auth::guard('dosen')->user()->role_id == 9 ||
                        Auth::guard('dosen')->user()->role_id == 10 ||
                        Auth::guard('dosen')->user()->role_id == 11 )
                       (<span>  {{ $jml_persetujuan_kp + $jml_persetujuan_skripsi + $jml_persetujuan_seminar }} </span>)
                      @endif
                    @if(Auth::guard('dosen')->user()->role_id == 5 || Auth::guard('dosen')->user()->role_id == null)
                        (<span> {{ $jml_persetujuan_kp + $jml_persetujuan_skripsi }} </span>)
                    @endif
                </a>
            </li>

            <span class="px-2">|</span>
            <li>
                <a href="/kp-skripsi/seminar-pembimbing-penguji" class="px-1">Seminar (<span>{{ $jml_seminar_kp + $jml_sempro + $jml_sidang }}</span>) </a>
            </li>

            <span class="px-2">|</span>
            <li><a href="/pembimbing/kerja-praktek" class="px-1">Bimbingan KP (<span>{{ $jml_bimbingankp }}</span>)</a></li>
            <span class="px-2">|</span>
            <li><a href="/pembimbing/skripsi" class="px-1">Bimbingan Skripsi (<span>{{ $jml_bimbingan_skripsi }}</span>) </a></li>
            <span class="px-2">|</span>
            <li><a href="/pembimbing-penguji/riwayat-bimbingan" class="px-1">Riwayat
                        (<span>{{ $jml_riwayat_kp + $jml_riwayat_skripsi + $jml_riwayat_seminar_kp + $jml_riwayat_sempro + $jml_riwayat_sidang }}</span>)
                </a></li>

        </ol>

        <div class="container-fluid">

            <table class="table table-responsive-lg table-bordered table-striped" width="100%" id="datatables">
                <thead class="table-dark">
                    <tr>
                        <!-- <th class="text-center p-2 p-2" scope="col">No.</th> -->
                        <th class="text-center" scope="col">NIM</th>
                        <th class="text-center" scope="col">Nama</th>
                        <th class="text-center" scope="col">Status</th>
                        <th class="text-center" scope="col">Tanggal Usulan</th>
                        <!-- <th class="text-center" scope="col">Batas</th> -->
                        <th class="text-center" scope="col">Keterangan</th>
                        <th class="text-center " scope="col" style="padding-left: 50px; padding-right:50px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    <div></div>
                    @foreach ($pendaftaran_kp as $kp)
                        <!-- TIMER USULAN KP -->

                        <!-- PEMBIMBING -->
                        @php
                            $countDownDateUsulanKPPembimbing = strtotime($kp->tgl_disetujui_usulankp_admin) + 4 * 24 * 60 * 60;
                            $nowUsulanKPPembimbing = time();
                            $distanceUsulanKPPembimbing = $countDownDateUsulanKPPembimbing - $nowUsulanKPPembimbing;
                            $daysUsulanKPPembimbing = floor($distanceUsulanKPPembimbing / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <!-- KOORDINATOR -->
                        @php
                            $countDownDateUsulanKPKoordinator = strtotime($kp->tgl_disetujui_usulankp_pembimbing) + 4 * 24 * 60 * 60;
                            $nowUsulanKPKoordinator = time();
                            $distanceUsulanKPKoordinator = $countDownDateUsulanKPKoordinator - $nowUsulanKPKoordinator;
                            $daysUsulanKPKoordinator = floor($distanceUsulanKPKoordinator / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->


                        <!-- KAPRODI -->
                        @php
                            $countDownDateUsulanKPKaprodi = strtotime($kp->tgl_disetujui_usulankp_koordinator) + 4 * 24 * 60 * 60;
                            $nowUsulanKPKaprodi = time();
                            $distanceUsulanKPKaprodi = $countDownDateUsulanKPKaprodi - $nowUsulanKPKaprodi;
                            $daysUsulanKPKaprodi = floor($distanceUsulanKPKaprodi / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <!-- KOORDINATOR SURAT BALASAN -->
                        @php
                            $countDownDateBalasanKoordinator = strtotime($kp->tgl_created_balasan) + 4 * 24 * 60 * 60;
                            $nowBalasanKoordinator = time();
                            $distanceBalasanKoordinator = $countDownDateBalasanKoordinator - $nowBalasanKoordinator;
                            $daysBalasanKoordinator = floor($distanceBalasanKoordinator / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->
                        <!-- SEMINAR KP PEMB -->
                        @php
                            $countDownDateSeminarKPPemb = strtotime($kp->tgl_disetujui_semkp_admin) + 4 * 24 * 60 * 60;
                            $nowSeminarKPPemb = time();
                            $distanceSeminarKPPemb = $countDownDateSeminarKPPemb - $nowSeminarKPPemb;
                            $daysSeminarKPPemb = floor($distanceSeminarKPPemb / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <!-- SEMINAR KP KOORDINATOR -->
                        @php
                            $countDownDateSeminarKPKoordinator = strtotime($kp->tgl_disetujui_semkp_pembimbing) + 4 * 24 * 60 * 60;
                            $nowSeminarKPKoordinator = time();
                            $distanceSeminarKPKoordinator = $countDownDateSeminarKPKoordinator - $nowSeminarKPKoordinator;
                            $daysSeminarKPKoordinator = floor($distanceSeminarKPKoordinator / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <!-- SEMINAR KP KAPRODI -->
                        @php
                            $countDownDateSeminarKPKaprodi = strtotime($kp->tgl_disetujui_semkp_pembimbing) + 4 * 24 * 60 * 60;
                            $nowSeminarKPKaprodi = time();
                            $distanceSeminarKPKaprodi = $countDownDateSeminarKPKaprodi - $nowSeminarKPKaprodi;
                            $daysSeminarKPKaprodi = floor($distanceSeminarKPKaprodi / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <!-- PENYERAHAN LAPORAN KP KOORDINATOR -->
                        @php
                            $countDownDateKPTI10Koordinator = strtotime($kp->tgl_created_kpti10) + 4 * 24 * 60 * 60;
                            $nowKPTI10Koordinator = time();
                            $distanceKPTI10Koordinator = $countDownDateKPTI10Koordinator - $nowKPTI10Koordinator;
                            $daysKPTI10Koordinator = floor($distanceKPTI10Koordinator / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <tr>
                            <!-- <td class="text-center">{{ $loop->iteration }}</td>-->
                            <td class="text-center px-1 py-2">{{ $kp->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 fw-bold">{{ $kp->mahasiswa->nama }}</td>
                            @if (
                                $kp->status_kp == 'USULAN KP' ||
                                    $kp->status_kp == 'SURAT PERUSAHAAN' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP' ||
                                    $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN')
                                <td class="text-center px-1 py-2 bg-secondary">{{ $kp->status_kp }}</td>
                            @endif
                            @if (
                                $kp->status_kp == 'USULAN KP DITERIMA' ||
                                    $kp->status_kp == 'KP DISETUJUI' ||
                                    $kp->status_kp == 'SEMINAR KP SELESAI' ||
                                    $kp->status_kp == 'KP SELESAI')
                                <td class="text-center px-1 py-2 bg-info">{{ $kp->status_kp }}</td>
                            @endif
                            @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                                <td class="text-center px-1 py-2 bg-success">{{ $kp->status_kp }}</td>
                            @endif

                            @if ($kp->status_kp == 'USULAN KP')
                                <td class="text-center px-1 py-2">
                                    {{ Carbon::parse($kp->tgl_created_usulan)->translatedFormat(' d F Y') }}</td>
                            @endif
                            @if ($kp->status_kp == 'SURAT PERUSAHAAN')
                                <td class="text-center px-1 py-2">
                                    {{ Carbon::parse($kp->tgl_created_balasan)->translatedFormat(' d F Y') }}</td>
                            @endif
                            @if ($kp->status_kp == 'DAFTAR SEMINAR KP')
                                <td class="text-center px-1 py-2">
                                    {{ Carbon::parse($kp->tgl_created_semkp)->translatedFormat(' d F Y') }}</td>
                            @endif
                            @if ($kp->status_kp == 'BUKTI PENYERAHAN LAPORAN')
                                <td class="text-center px-1 py-2">
                                    {{ Carbon::parse($kp->tgl_created_semkp)->translatedFormat(' d F Y') }}</td>
                            @endif

                            <!-- MULAI -->
                            <!-- PEMBIMBING -->
                            <!-- @if ($kp->status_kp == 'USULAN KP')
                                @if ($kp->dosen_pembimbing_nip == Auth::user()->nip)
                                    @if ($kp->keterangan == 'Menunggu persetujuan Pembimbing' && $kp->status_kp == 'USULAN KP')
                                        <td class="text-center px-1 py-2">
                                            @if ($daysUsulanKPPembimbing > 0)
                                                <span class="text-danger"> {{ $daysUsulanKPPembimbing }} hari lagi</span>
                                            @elseif($daysUsulanKPPembimbing <= 0)
                                                Batas Waktu Unggah Surat Balasan telah habis
                                            @endif
                                        </td>
                                    @endif
                                @endif -->

                                <!-- KOORDINATOR -->

                                <!-- @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                    @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                            Auth::guard('dosen')->user()->role_id == 10 ||
                                            Auth::guard('dosen')->user()->role_id == 11)
                                        @if ($kp->keterangan == 'Menunggu persetujuan Koordinator KP' && $kp->status_kp == 'USULAN KP')
                                            <td class="text-center px-1 py-2">
                                                @if ($daysUsulanKPKoordinator >= 0)
                                                    <span class="text-danger"> {{ $daysUsulanKPKoordinator }} hari
                                                        lagi</span>
                                                @elseif($daysUsulanKPKoordinator <= 0)
                                                    Batas waktu telah habis
                                                @endif
                                            </td>
                                        @endif
                                    @endif
                                @endif -->

                                <!-- KAPRODI -->

                                <!-- @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                    @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                            Auth::guard('dosen')->user()->role_id == 7 ||
                                            Auth::guard('dosen')->user()->role_id == 8)
                                        @if ($kp->keterangan == 'Menunggu persetujuan Koordinator Program Studi' && $kp->status_kp == 'USULAN KP')
                                            <td class="text-center px-1 py-2">
                                                @if ($daysUsulanKPKaprodi >= 0)
                                                    <span class="text-danger"> {{ $daysUsulanKPKaprodi }} hari lagi</span>
                                                @elseif($daysUsulanKPKaprodi <= 0)
                                                    Batas waktu telah habis
                                                @endif
                                            </td>
                                        @endif
                                    @endif
                                @endif
                            @endif -->

                            <!-- BATAS -->


                            <!-- BALASAN KP KOORDINATOR -->

                            <!-- @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                        Auth::guard('dosen')->user()->role_id == 10 ||
                                        Auth::guard('dosen')->user()->role_id == 11)
                                    @if ($kp->keterangan == 'Menunggu persetujuan Koordinator KP' && $kp->status_kp == 'SURAT PERUSAHAAN')
                                        <td class="text-center px-1 py-2">
                                            @if ($daysBalasanKoordinator >= 0)
                                                <span class="text-danger"> {{ $daysBalasanKoordinator }} hari lagi</span>
                                            @elseif($daysBalasanKoordinator <= 0)
                                                Batas waktu telah habis
                                            @endif
                                        </td>
                                    @endif
                                @endif
                            @endif -->

                            <!-- PEMBIMBING -->
                            <!-- @if ($kp->status_kp == 'DAFTAR SEMINAR KP')
                                @if ($kp->dosen_pembimbing_nip == Auth::user()->nip)
                                    @if ($kp->keterangan == 'Menunggu persetujuan Pembimbing' && $kp->status_kp == 'DAFTAR SEMINAR KP')
                                        <td class="text-center px-1 py-2">
                                            @if ($daysSeminarKPPemb > 0)
                                                <span class="text-danger"> {{ $daysSeminarKPPemb }} hari lagi</span>
                                            @elseif($daysSeminarKPPemb <= 0)
                                                Batas Waktu Persetujuan telah habis
                                            @endif
                                        </td>
                                    @endif
                                @endif -->

                                <!-- KOORDINATOR -->
                                <!-- @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                    @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                            Auth::guard('dosen')->user()->role_id == 10 ||
                                            Auth::guard('dosen')->user()->role_id == 11)
                                        @if ($kp->keterangan == 'Menunggu persetujuan Koordinator KP' && $kp->status_kp == 'DAFTAR SEMINAR KP')
                                            <td class="text-center px-1 py-2">
                                                @if ($daysSeminarKPKoordinator > 0)
                                                    <span class="text-danger"> {{ $daysSeminarKPKoordinator }} hari
                                                        lagi</span>
                                                @elseif($daysSeminarKPKoordinator <= 0)
                                                    Batas Waktu Persetujuan telah habis
                                                @endif
                                            </td>
                                        @endif
                                    @endif
                                @endif -->

                                <!-- KAPRODI -->
                                <!-- @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                    @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                            Auth::guard('dosen')->user()->role_id == 7 ||
                                            Auth::guard('dosen')->user()->role_id == 8)
                                        @if ($kp->keterangan == 'Menunggu persetujuan Koordinator Program Studi' && $kp->status_kp == 'DAFTAR SEMINAR KP')
                                            <td class="text-center px-1 py-2">
                                                @if ($daysSeminarKPKaprodi > 0)
                                                    <span class="text-danger"> {{ $daysSeminarKPKaprodi }} hari lagi</span>
                                                @elseif($daysSeminarKPKaprodi <= 0)
                                                    Batas Waktu Persetujuan telah habis
                                                @endif
                                            </td>
                                        @endif
                                    @endif
                                @endif
                            @endif -->

                            <!-- PENYERAHAN LAPORAN KOORDINATOR -->
                            <!-- @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                        Auth::guard('dosen')->user()->role_id == 10 ||
                                        Auth::guard('dosen')->user()->role_id == 11)
                                    @if ($kp->keterangan == 'Menunggu persetujuan Koordinator KP' && $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN')
                                        <td class="text-center px-1 py-2">
                                            @if ($daysKPTI10Koordinator > 0)
                                                <span class="text-danger"> {{ $daysKPTI10Koordinator }} hari lagi</span>
                                            @elseif($daysKPTI10Koordinator <= 0)
                                                Batas Waktu Persetujuan telah habis
                                            @endif
                                        </td>
                                    @endif
                                @endif
                            @endif -->

                            <td class="text-center px-1 py-2"> {{ $kp->keterangan }}</td>


                            @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                @if ($kp->status_kp == 'USULAN KP' || $kp->status_kp == 'USULAN KP DITERIMA')
                                    <td class="text-center px-1 py-2">
                                        @if ($kp->dosen_pembimbing_nip == Auth::user()->nip)
                                            @if ($kp->keterangan == 'Menunggu persetujuan Pembimbing' && $kp->status_kp == 'USULAN KP')
                                                <div class="row ml-0 ml-md-4">
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">

                                                        <button onclick="tolakUsulanKPPembimbing({{ $kp->id }})"
                                                            class=" btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                            title="Tolak"><i class="fas fa-times-circle"></i></button>
                                                    </div>
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <a href="/kp-skripsi/persetujuan/usulankp/{{ $kp->id }}"
                                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <form action="/usulankp/pembimbing/approve/{{ $kp->id }}"
                                                            class="setujui-usulankp-pembimbing" method="POST">
                                                            @method('put')
                                                            @csrf
                                                            <button class="btn btn-success badge p-1 "
                                                                data-bs-toggle="tooltip" title="Setujui"><i
                                                                    class="fas fa-check-circle"></i></button>
                                                        </form>

                                                    </div>

                                                </div>
                                            @endif
                                        @endif

                                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                            @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                                    Auth::guard('dosen')->user()->role_id == 10 ||
                                                    Auth::guard('dosen')->user()->role_id == 11)
                                                @if ($kp->keterangan == 'Menunggu persetujuan Koordinator KP' && $kp->status_kp == 'USULAN KP')
                                                    <div class="row ml-0 ml-md-4">
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <button
                                                                onclick="tolakUsulanKPKoordinator({{ $kp->id }})"
                                                                class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                                title="Tolak"><i
                                                                    class="fas fa-times-circle"></i></button>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <a href="/kp-skripsi/persetujuan/usulankp/{{ $kp->id }}"
                                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                                title="Lihat Detail"><i
                                                                    class="fas fa-info-circle"></i></a>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <form
                                                                action="/usulankp/koordinator/approve/{{ $kp->id }}"
                                                                class="setujui-usulankp-koordinator" method="POST">
                                                                @method('put')
                                                                @csrf
                                                                <button class="btn btn-success badge p-1 "
                                                                    data-bs-toggle="tooltip" title="Setujui"><i
                                                                        class="fas fa-check-circle"></i></button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif

                                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                            @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                                    Auth::guard('dosen')->user()->role_id == 7 ||
                                                    Auth::guard('dosen')->user()->role_id == 8)
                                                @if ($kp->keterangan == 'Menunggu persetujuan Koordinator Program Studi' && $kp->status_kp == 'USULAN KP')
                                                    <div class="row ml-0 ml-md-4">
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <button onclick="tolakUsulanKPKaprodi({{ $kp->id }})"
                                                                class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                                title="Tolak"><i
                                                                    class="fas fa-times-circle"></i></button>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <a href="/kp-skripsi/persetujuan/usulankp/{{ $kp->id }}"
                                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                                title="Lihat Detail"><i
                                                                    class="fas fa-info-circle"></i></a>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <form action="/usulankp/kaprodi/approve/{{ $kp->id }}"
                                                                class="setujui-usulankp-kaprodi" method="POST">
                                                                @method('put')
                                                                @csrf
                                                                <button class="btn btn-success badge p-1 "
                                                                    data-bs-toggle="tooltip" title="Setujui"><i
                                                                        class="fas fa-check-circle"></i></button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif


                                    </td>
                                @endif

                                @if ($kp->status_kp == 'SURAT PERUSAHAAN' || $kp->status_kp == 'KP DISETUJUI')
                                    <td class="text-center px-1 py-2">
                                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                            @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                                    Auth::guard('dosen')->user()->role_id == 10 ||
                                                    Auth::guard('dosen')->user()->role_id == 11)
                                                @if ($kp->keterangan == 'Menunggu persetujuan Koordinator KP' && $kp->status_kp == 'SURAT PERUSAHAAN')
                                                    <div class="row ml-0 ml-md-4">
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <button
                                                                onclick="tolakBalasanKPKoordinator({{ $kp->id }})"
                                                                class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                                title="Tolak"><i
                                                                    class="fas fa-times-circle"></i></button>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <a href="/kp-skripsi/persetujuan/suratperusahaan/{{ $kp->id }}"
                                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                                title="Lihat Detail"><i
                                                                    class="fas fa-info-circle"></i></a>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <form
                                                                action="/balasankp/koordinator/approve/{{ $kp->id }}"
                                                                class="setujui-balasankp-koordinator" method="POST">
                                                                @method('put')
                                                                @csrf
                                                                <button class="btn btn-success badge p-1 "
                                                                    data-bs-toggle="tooltip" title="Setujui"><i
                                                                        class="fas fa-check-circle"></i></button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                @endif
                                @if (
                                    $kp->status_kp == 'DAFTAR SEMINAR KP' ||
                                        $kp->status_kp == 'SEMINAR KP DIJADWALKAN' ||
                                        $kp->status_kp == 'SEMINAR KP SELESAI')
                                    <td class="text-center px-1 py-2">
                                        @if ($kp->dosen_pembimbing_nip == Auth::user()->nip)
                                            @if ($kp->keterangan == 'Menunggu persetujuan Pembimbing' && $kp->status_kp == 'DAFTAR SEMINAR KP')
                                                <div class="row ml-0 ml-md-4">
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <button onclick="tolakSemKPPemb({{ $kp->id }})"
                                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                            title="Tolak"><i class="fas fa-times-circle"></i></button>
                                                    </div>
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <a href="/kp-skripsi/persetujuan/semkp/{{ $kp->id }}"
                                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <form
                                                            action="/usulan-semkp/pembimbing/approve/{{ $kp->id }}"
                                                            class="setujui-semkp-pembimbing" method="POST">
                                                            @method('put')
                                                            @csrf
                                                            <button class="btn btn-success badge p-1 "
                                                                data-bs-toggle="tooltip" title="Setujui"><i
                                                                    class="fas fa-check-circle"></i></button>
                                                        </form>

                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                            @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                                    Auth::guard('dosen')->user()->role_id == 10 ||
                                                    Auth::guard('dosen')->user()->role_id == 11)
                                                @if ($kp->status_kp == 'DAFTAR SEMINAR KP' && $kp->keterangan == 'Menunggu persetujuan Koordinator KP')
                                                    <div class="row ml-0 ml-md-4">
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <button onclick="tolakSemKPKoordinator({{ $kp->id }})"
                                                                class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                                title="Tolak"><i
                                                                    class="fas fa-times-circle"></i></button>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <a href="/kp-skripsi/persetujuan/semkp/{{ $kp->id }}"
                                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                                title="Lihat Detail"><i
                                                                    class="fas fa-info-circle"></i></a>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <form
                                                                action="/usulan-semkp/koordinator/approve/{{ $kp->id }}"
                                                                class="setujui-semkp-koordinator" method="POST">
                                                                @method('put')
                                                                @csrf
                                                                <button class="btn btn-success badge p-1 "
                                                                    data-bs-toggle="tooltip" title="Setujui"><i
                                                                        class="fas fa-check-circle"></i></button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif

                                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                            @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                                    Auth::guard('dosen')->user()->role_id == 7 ||
                                                    Auth::guard('dosen')->user()->role_id == 8)
                                                @if ($kp->status_kp == 'DAFTAR SEMINAR KP' && $kp->keterangan == 'Menunggu persetujuan Koordinator Program Studi')
                                                    <div class="row ml-0 ml-md-4">
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <button onclick="tolakSemKPKaprodi({{ $kp->id }})"
                                                                class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                                title="Tolak"><i
                                                                    class="fas fa-times-circle"></i></button>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <a href="/kp-skripsi/persetujuan/semkp/{{ $kp->id }}"
                                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                                title="Lihat Detail"><i
                                                                    class="fas fa-info-circle"></i></a>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <form
                                                                action="/usulan-semkp/kaprodi/approve/{{ $kp->id }}"
                                                                class="setujui-semkp-kaprodi" method="POST">
                                                                @method('put')
                                                                @csrf
                                                                <button class="btn btn-success badge p-1 "
                                                                    data-bs-toggle="tooltip" title="Setujui"><i
                                                                        class="fas fa-check-circle"></i></button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif

                                        @if ($kp->dosen_pembimbing_nip == Auth::user()->nip)
                                            @if ($kp->keterangan == 'Seminar KP Dijadwalkan' && $kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                                                <div class="row ml-0 ml-md-4">
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <button onclick="tolakGagalSemKPPemb({{ $kp->id }})"
                                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                            title="Gagal Seminar KP"><i
                                                                class="fas fa-times-circle"></i></button>
                                                    </div>
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <a href="/kp-skripsi/persetujuan/semkp/{{ $kp->id }}"
                                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <form
                                                            action="/selesaiseminar-kp/pembimbing/approve/{{ $kp->id }}"
                                                            class="setujui-selesai-semkp-pembimbing" method="POST">
                                                            @method('put')
                                                            @csrf
                                                            <button class="btn btn-success badge p-1 "
                                                                data-bs-toggle="tooltip" title="Selesai Seminar KP"><i
                                                                    class="fas fa-check-circle"></i></button>
                                                        </form>

                                                    </div>
                                                </div>
                                            @endif
                                        @endif


                                    </td>
                                @endif

                                @if ($kp->status_kp == 'BUKTI PENYERAHAN LAPORAN' || $kp->status_kp == 'KP SELESAI')
                                    <td class="text-center px-1 py-2">

                                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                            @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                                    Auth::guard('dosen')->user()->role_id == 10 ||
                                                    Auth::guard('dosen')->user()->role_id == 11)
                                                @if ($kp->status_kp == 'BUKTI PENYERAHAN LAPORAN' && $kp->keterangan == 'Menunggu persetujuan Koordinator KP')
                                                    <div class="row ml-0 ml-md-4">
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <button onclick="tolakKPTI10Koordinator({{ $kp->id }})"
                                                                class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                                title="Tolak"><i
                                                                    class="fas fa-times-circle"></i></button>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <a href="/kp-skripsi/persetujuan/kpti10/{{ $kp->id }}"
                                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                                title="Lihat Detail"><i
                                                                    class="fas fa-info-circle"></i></a>
                                                        </div>
                                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                                            <form action="/kpti10/koordinator/approve/{{ $kp->id }}"
                                                                class="setujui-kpti10-koordinator" method="POST">
                                                                @method('put')
                                                                @csrf
                                                                <button class="btn btn-success badge p-1 "
                                                                    data-bs-toggle="tooltip" title="Setujui"><i
                                                                        class="fas fa-check-circle"></i></button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                            @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                                    Auth::guard('dosen')->user()->role_id == 10 ||
                                                    Auth::guard('dosen')->user()->role_id == 11)
                                                @if ($kp->status_kp == 'KP SELESAI' && $kp->keterangan == 'Proses Kerja Praktek Selesai')
                                                    <div class="row ml-0 ml-md-4">

                                                        <div class="col-4 py-2 py-md-0 col-lg-6">
                                                            <a href="/kp-skripsi/persetujuan/kpti10/{{ $kp->id }}"
                                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                                title="Lihat Detail"><i
                                                                    class="fas fa-info-circle"></i></a>
                                                        </div>
                                                        <div class="col-4 py-2 py-md-0 col-lg-6">
                                                            <form
                                                                action="/nilaikpkeluar/koordinator/approve/{{ $kp->id }}"
                                                                class="setujui-nilai-kp-keluar-koordinator"
                                                                method="POST">
                                                                @method('put')
                                                                @csrf
                                                                <button class="btn btn-success badge p-1 "
                                                                    data-bs-toggle="tooltip" title="Setujui"><i
                                                                        class="fas fa-check-circle"></i></button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
        </div>
        </td>
        @endif
        @endif

        </tr>
        @endforeach


        @foreach ($pendaftaran_skripsi as $skripsi)
            <!-- USULAN JUDUL PEMBIMBING 1 -->
            @php
                $countDownDateUsulJudulPemb1 = strtotime($skripsi->tgl_disetujui_usuljudul_admin) + 4 * 24 * 60 * 60;
                $nowUsulJudulPemb1 = time();
                $distanceUsulJudulPemb1 = $countDownDateUsulJudulPemb1 - $nowUsulJudulPemb1;
                $daysUsulJudulPemb1 = floor($distanceUsulJudulPemb1 / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->
            <!-- USULAN JUDUL PEMBIMBING 2 -->
            @php
                $countDownDateUsulJudulPemb2 = strtotime($skripsi->tgl_disetujui_usuljudul_pemb1) + 4 * 24 * 60 * 60;
                $nowUsulJudulPemb2 = time();
                $distanceUsulJudulPemb2 = $countDownDateUsulJudulPemb2 - $nowUsulJudulPemb2;
                $daysUsulJudulPemb2 = floor($distanceUsulJudulPemb2 / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->
            <!-- USULAN JUDUL KOORDINATOR -->
            @php
                $countDownDateUsulJudulKoordinator = strtotime($skripsi->pembimbing_2_nip != null ? $skripsi->tgl_disetujui_usuljudul_pemb2 : $skripsi->tgl_disetujui_usuljudul_pemb1) + 4 * 24 * 60 * 60;
                $nowUsulJudulKoordinator = time();
                $distanceUsulJudulKoordinator = $countDownDateUsulJudulKoordinator - $nowUsulJudulKoordinator;
                $daysUsulJudulKoordinator = floor($distanceUsulJudulKoordinator / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->
            <!-- USULAN JUDUL KAPRODI -->
            @php
                $countDownDateUsulJudulKaprodi = strtotime($skripsi->tgl_disetujui_usuljudul_koordinator) + 4 * 24 * 60 * 60;
                $nowUsulJudulKaprodi = time();
                $distanceUsulJudulKaprodi = $countDownDateUsulJudulKaprodi - $nowUsulJudulKaprodi;
                $daysUsulJudulKaprodi = floor($distanceUsulJudulKaprodi / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->


            <!-- DAFTAR SEMPRO PEMB 1 -->
            @php
                $countDownDateDaftarSemproPemb1 = strtotime($skripsi->tgl_created_sempro) + 4 * 24 * 60 * 60;
                $nowDaftarSemproPemb1 = time();
                $distanceDaftarSemproPemb1 = $countDownDateDaftarSemproPemb1 - $nowDaftarSemproPemb1;
                $daysDaftarSemproPemb1 = floor($distanceDaftarSemproPemb1 / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->
            <!-- DAFTAR SEMPRO PEMB 2 -->
            @php
                $countDownDateDaftarSemproPemb2 = strtotime($skripsi->tgl_disetujui_sempro_pemb1) + 4 * 24 * 60 * 60;
                $nowDaftarSemproPemb2 = time();
                $distanceDaftarSemproPemb2 = $countDownDateDaftarSemproPemb2 - $nowDaftarSemproPemb2;
                $daysDaftarSemproPemb2 = floor($distanceDaftarSemproPemb2 / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->

            <!-- DAFTAR PERPANJANGAN 1 WAKTU SKRIPSI PEMB 1 -->
            @php
                $countDownDatePerpanjangan1Pemb1 = strtotime($skripsi->tgl_created_perpanjangan1) + 4 * 24 * 60 * 60;
                $nowPerpanjangan1Pemb1 = time();
                $distancePerpanjangan1Pemb1 = $countDownDatePerpanjangan1Pemb1 - $nowPerpanjangan1Pemb1;
                $daysPerpanjangan1Pemb1 = floor($distancePerpanjangan1Pemb1 / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->
            <!-- DAFTAR PERPANJANGAN 1 WAKTU SKRIPSI KAPRODI -->
            @php
                $countDownDatePerpanjangan1Kaprodi = strtotime($skripsi->tgl_disetujui_perpanjangan1_pemb1) + 4 * 24 * 60 * 60;
                $nowPerpanjangan1Kaprodi = time();
                $distancePerpanjangan1Kaprodi = $countDownDatePerpanjangan1Kaprodi - $nowPerpanjangan1Kaprodi;
                $daysPerpanjangan1Kaprodi = floor($distancePerpanjangan1Kaprodi / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->

            <!-- DAFTAR PERPANJANGAN 2 WAKTU SKRIPSI PEMB 1 -->
            @php
                $countDownDatePerpanjangan2Pemb1 = strtotime($skripsi->tgl_created_perpanjangan2) + 4 * 24 * 60 * 60;
                $nowPerpanjangan2Pemb1 = time();
                $distancePerpanjangan2Pemb1 = $countDownDatePerpanjangan2Pemb1 - $nowPerpanjangan2Pemb1;
                $daysPerpanjangan2Pemb1 = floor($distancePerpanjangan2Pemb1 / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->
            <!-- DAFTAR PERPANJANGAN 2 WAKTU SKRIPSI KAPRODI -->
            @php
                $countDownDatePerpanjangan2Kaprodi = strtotime($skripsi->tgl_disetujui_perpanjangan2_pemb1) + 4 * 24 * 60 * 60;
                $nowPerpanjangan2Kaprodi = time();
                $distancePerpanjangan2Kaprodi = $countDownDatePerpanjangan2Kaprodi - $nowPerpanjangan2Kaprodi;
                $daysPerpanjangan2Kaprodi = floor($distancePerpanjangan2Kaprodi / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->

            <!-- DAFTAR SIDANG PEMB 1 -->
            @php
                $countDownDateDaftarSidangPemb1 = strtotime($skripsi->tgl_created_sidang) + 4 * 24 * 60 * 60;
                $nowDaftarSidangPemb1 = time();
                $distanceDaftarSidangPemb1 = $countDownDateDaftarSidangPemb1 - $nowDaftarSidangPemb1;
                $daysDaftarSidangPemb1 = floor($distanceDaftarSidangPemb1 / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->
            <!-- DAFTAR SIDANG PEMB 2 -->
            @php
                $countDownDateDaftarSidangPemb2 = strtotime($skripsi->tgl_disetujui_sidang_pemb1) + 4 * 24 * 60 * 60;
                $nowDaftarSidangPemb2 = time();
                $distanceDaftarSidangPemb2 = $countDownDateDaftarSidangPemb2 - $nowDaftarSidangPemb2;
                $daysDaftarSidangPemb2 = floor($distanceDaftarSidangPemb2 / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->

            <!-- DAFTAR SIDANG KOORDINATOR -->
            @php
                $countDownDateDaftarSidangKoordinator = strtotime($skripsi->tgl_disetujui_sidang_admin) + 4 * 24 * 60 * 60;
                $nowDaftarSidangKoordinator = time();
                $distanceDaftarSidangKoordinator = $countDownDateDaftarSidangKoordinator - $nowDaftarSidangKoordinator;
                $daysDaftarSidangKoordinator = floor($distanceDaftarSidangKoordinator / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->
            <!-- DAFTAR SIDANG KAPRODI -->
            @php
                $countDownDateDaftarSidangKaprodi = strtotime($skripsi->tgl_disetujui_sidang_koordinator) + 4 * 24 * 60 * 60;
                $nowDaftarSidangKaprodi = time();
                $distanceDaftarSidangKaprodi = $countDownDateDaftarSidangKaprodi - $nowDaftarSidangKaprodi;
                $daysDaftarSidangKaprodi = floor($distanceDaftarSidangKaprodi / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->

            <!-- PERPANJANGAN REVISI PEMBIMBING -->
            @php
                $countDownDateRevisiPemb1 = strtotime($skripsi->tgl_created_revisi) + 4 * 24 * 60 * 60;
                $nowRevisiPemb1 = time();
                $distanceRevisiPemb1 = $countDownDateRevisiPemb1 - $nowRevisiPemb1;
                $daysRevisiPemb1 = floor($distanceRevisiPemb1 / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->

            <!-- PERPANJANGAN REVISI KAPRODI -->
            @php
                $countDownDateRevisiKaprodi = strtotime($skripsi->tgl_disetujui_revisi_pemb1) + 4 * 24 * 60 * 60;
                $nowRevisiKaprodi = time();
                $distanceRevisiKaprodi = $countDownDateRevisiKaprodi - $nowRevisiKaprodi;
                $daysRevisiKaprodi = floor($distanceRevisiKaprodi / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->

            <!-- BUKTI PENYERAHAN BUKU SKRIPSI KOORDINATOR -->
            @php
                $countDownDateBukuSkripsiKoordinator = strtotime($skripsi->tgl_created_sti_17) + 4 * 24 * 60 * 60;
                $nowBukuSkripsiKoordinator = time();
                $distanceBukuSkripsiKoordinator = $countDownDateBukuSkripsiKoordinator - $nowBukuSkripsiKoordinator;
                $daysBukuSkripsiKoordinator = floor($distanceBukuSkripsiKoordinator / (60 * 60 * 24));
            @endphp
            <!-- BATAS -->

            <div></div>
            <tr>
                <!-- <td class="text-center px-1 py-2">{{ $loop->iteration }}</td>                              -->
                <td class="text-center px-1 py-2">{{ $skripsi->mahasiswa->nim }}</td>
                <td class="text-left pl-3 pr-1 fw-bold">{{ $skripsi->mahasiswa->nama }}</td>
                <!-- <td class="text-center px-1 py-2">{{ $skripsi->jenis_usulan }}</td>          -->

                @if (
                    $skripsi->status_skripsi == 'USULAN JUDUL' ||
                        $skripsi->status_skripsi == 'DAFTAR SEMPRO' ||
                        $skripsi->status_skripsi == 'DAFTAR SIDANG' ||
                        $skripsi->status_skripsi == 'PERPANJANGAN REVISI' ||
                        $skripsi->status_skripsi == 'PERPANJANGAN 1' ||
                        $skripsi->status_skripsi == 'PERPANJANGAN 2' ||
                        $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI')
                    <td class="text-center px-1 py-2 bg-secondary">{{ $skripsi->status_skripsi }}</td>
                @endif
                @if (
                    $skripsi->status_skripsi == 'USULAN JUDUL DISETUJUI' ||
                        $skripsi->status_skripsi == 'SEMPRO SELESAI' ||
                        $skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI' ||
                        $skripsi->status_skripsi == 'SIDANG SELESAI' ||
                        $skripsi->status_skripsi == 'SKRIPSI SELESAI')
                    <td class="text-center px-1 py-2 bg-info">{{ $skripsi->status_skripsi }}</td>
                @endif

                @if ($skripsi->status_skripsi == 'SEMPRO DIJADWALKAN' || $skripsi->status_skripsi == 'SIDANG DIJADWALKAN')
                    <td class="text-center px-1 py-2 bg-success">{{ $skripsi->status_skripsi }}</td>
                @endif

                <!-- ___________batas____________ -->

                @if ($skripsi->status_skripsi == 'USULAN JUDUL')
                    <td class="text-center px-1 py-2">
                        {{ Carbon::parse($skripsi->tgl_created_usuljudul)->translatedFormat(' d F Y') }}</td>
                @endif

                @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO')
                    <td class="text-center px-1 py-2">
                        {{ Carbon::parse($skripsi->tgl_created_sempro)->translatedFormat(' d F Y') }}</td>
                @endif
                @if ($skripsi->status_skripsi == 'PERPANJANGAN 1')
                    <td class="text-center px-1 py-2">
                        {{ Carbon::parse($skripsi->tgl_created_perpanjangan1)->translatedFormat(' d F Y') }}</td>
                @endif
                @if ($skripsi->status_skripsi == 'PERPANJANGAN 2')
                    <td class="text-center px-1 py-2">
                        {{ Carbon::parse($skripsi->tgl_created_perpanjangan2)->translatedFormat(' d F Y') }}</td>
                @endif
                @if ($skripsi->status_skripsi == 'DAFTAR SIDANG')
                    <td class="text-center px-1 py-2">
                        {{ Carbon::parse($skripsi->tgl_created_sidang)->translatedFormat(' d F Y') }}</td>
                @endif

                @if ($skripsi->status_skripsi == 'PERPANJANGAN REVISI')
                    <td class="text-center px-1 py-2">
                        {{ Carbon::parse($skripsi->tgl_created_revisi)->translatedFormat(' d F Y') }}</td>
                @endif
                @if ($skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI')
                    <td class="text-center px-1 py-2">
                        {{ Carbon::parse($skripsi->tgl_created_sidang)->translatedFormat(' d F Y') }}</td>
                @endif

                <!-- BATAS PERSETUJUAN -->
                <!-- @if ($skripsi->status_skripsi == 'USULAN JUDUL')
                    @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                        @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1' && $skripsi->status_skripsi == 'USULAN JUDUL')
                            <td class="text-center px-1 py-2">
                                @if ($daysUsulJudulPemb1 > 0)
                                    <span class="text-danger"> {{ $daysUsulJudulPemb1 }} hari lagi</span>
                                @elseif($daysUsulJudulPemb1 <= 0)
                                    Batas Waktu Persetujuan telah habis
                                @endif
                            </td>
                        @endif
                    @endif

                    @if ($skripsi->pembimbing_2_nip == Auth::user()->nip)
                        @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 2' && $skripsi->status_skripsi == 'USULAN JUDUL')
                            <td class="text-center px-1 py-2">
                                @if ($daysUsulJudulPemb2 > 0)
                                    <span class="text-danger"> {{ $daysUsulJudulPemb2 }} hari lagi</span>
                                @elseif($daysUsulJudulPemb2 <= 0)
                                    Batas Waktu Persetujuan telah habis
                                @endif
                            </td>
                        @endif
                    @endif

                    @if (Str::length(Auth::guard('dosen')->user()) > 0)
                        @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                Auth::guard('dosen')->user()->role_id == 10 ||
                                Auth::guard('dosen')->user()->role_id == 11)
                            @if ($skripsi->keterangan == 'Menunggu persetujuan Koordinator Skripsi' && $skripsi->status_skripsi == 'USULAN JUDUL')
                                <td class="text-center px-1 py-2">
                                    @if ($daysUsulJudulKoordinator > 0)
                                        <span class="text-danger"> {{ $daysUsulJudulKoordinator }} hari lagi</span>
                                    @elseif($daysUsulJudulKoordinator <= 0)
                                        Batas Waktu Persetujuan telah habis
                                    @endif
                                </td>
                            @endif
                        @endif
                    @endif

                    @if (Str::length(Auth::guard('dosen')->user()) > 0)
                        @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                Auth::guard('dosen')->user()->role_id == 7 ||
                                Auth::guard('dosen')->user()->role_id == 8)
                            @if (
                                $skripsi->keterangan == 'Menunggu persetujuan Koordinator Program Studi' &&
                                    $skripsi->status_skripsi == 'USULAN JUDUL')
                                <td class="text-center px-1 py-2">
                                    @if ($daysUsulJudulKaprodi > 0)
                                        <span class="text-danger"> {{ $daysUsulJudulKaprodi }} hari lagi</span>
                                    @elseif($daysUsulJudulKaprodi <= 0)
                                        Batas Waktu Persetujuan telah habis
                                    @endif
                                </td>
                            @endif
                        @endif
                    @endif
                @endif -->

                <!-- DAFTAR SEMPRO -->
                <!-- @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO')
                    @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                        @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1' && $skripsi->status_skripsi == 'DAFTAR SEMPRO')
                            <td class="text-center px-1 py-2">
                                @if ($daysDaftarSemproPemb1 > 0)
                                    <span class="text-danger"> {{ $daysDaftarSemproPemb1 }} hari lagi</span>
                                @elseif($daysDaftarSemproPemb1 <= 0)
                                    Batas Waktu Persetujuan telah habis
                                @endif
                            </td>
                        @endif
                    @endif

                    @if ($skripsi->pembimbing_2_nip == Auth::user()->nip)
                        @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 2' && $skripsi->status_skripsi == 'DAFTAR SEMPRO')
                            <td class="text-center px-1 py-2">
                                @if ($daysDaftarSemproPemb2 > 0)
                                    <span class="text-danger"> {{ $daysDaftarSemproPemb2 }} hari lagi</span>
                                @elseif($daysDaftarSemproPemb2 <= 0)
                                    Batas Waktu Persetujuan telah habis
                                @endif
                            </td>
                        @endif
                    @endif
                @endif -->

                <!-- PERPANJANGAN 1 -->
                <!-- @if ($skripsi->status_skripsi == 'PERPANJANGAN 1')
                    @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                        @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1' && $skripsi->status_skripsi == 'PERPANJANGAN 1')
                            <td class="text-center px-1 py-2">
                                @if ($daysPerpanjangan1Pemb1 > 0)
                                    <span class="text-danger"> {{ $daysPerpanjangan1Pemb1 }} hari lagi</span>
                                @elseif($daysPerpanjangan1Pemb1 <= 0)
                                    Batas Waktu Persetujuan telah habis
                                @endif
                            </td>
                        @endif
                    @endif

                    @if (Str::length(Auth::guard('dosen')->user()) > 0)
                        @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                Auth::guard('dosen')->user()->role_id == 7 ||
                                Auth::guard('dosen')->user()->role_id == 8)
                            @if (
                                $skripsi->keterangan == 'Menunggu persetujuan Koordinator Program Studi' &&
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1')
                                <td class="text-center px-1 py-2">
                                    @if ($daysPerpanjangan1Kaprodi > 0)
                                        <span class="text-danger"> {{ $daysPerpanjangan1Kaprodi }} hari lagi</span>
                                    @elseif($daysPerpanjangan1Kaprodi <= 0)
                                        Batas Waktu Persetujuan telah habis
                                    @endif
                                </td>
                            @endif
                        @endif
                    @endif
                @endif -->

                <!-- PERPANJANGAN 2 -->
                <!-- @if ($skripsi->status_skripsi == 'PERPANJANGAN 2')
                    @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                        @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1' && $skripsi->status_skripsi == 'PERPANJANGAN 2')
                            <td class="text-center px-1 py-2">
                                @if ($daysPerpanjangan2Pemb1 > 0)
                                    <span class="text-danger"> {{ $daysPerpanjangan2Pemb1 }} hari lagi</span>
                                @elseif($daysPerpanjangan2Pemb1 <= 0)
                                    Batas Waktu Persetujuan telah habis
                                @endif
                            </td>
                        @endif
                    @endif

                    @if (Str::length(Auth::guard('dosen')->user()) > 0)
                        @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                Auth::guard('dosen')->user()->role_id == 7 ||
                                Auth::guard('dosen')->user()->role_id == 8)
                            @if (
                                $skripsi->keterangan == 'Menunggu persetujuan Koordinator Program Studi' &&
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2')
                                <td class="text-center px-1 py-2">
                                    @if ($daysPerpanjangan2Kaprodi > 0)
                                        <span class="text-danger"> {{ $daysPerpanjangan2Kaprodi }} hari lagi</span>
                                    @elseif($daysPerpanjangan2Kaprodi <= 0)
                                        Batas Waktu Persetujuan telah habis
                                    @endif
                                </td>
                            @endif
                        @endif
                    @endif
                @endif -->

                <!-- DAFTAR SIDANG -->

                <!-- @if ($skripsi->status_skripsi == 'DAFTAR SIDANG')
                    @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                        @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1' && $skripsi->status_skripsi == 'DAFTAR SIDANG')
                            <td class="text-center px-1 py-2">
                                @if ($daysDaftarSidangPemb1 > 0)
                                    <span class="text-danger"> {{ $daysDaftarSidangPemb1 }} hari lagi</span>
                                @elseif($daysDaftarSidangPemb1 <= 0)
                                    Batas Waktu Persetujuan telah habis
                                @endif
                            </td>
                        @endif
                    @endif

                    @if ($skripsi->pembimbing_2_nip == Auth::user()->nip)
                        @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 2' && $skripsi->status_skripsi == 'DAFTAR SIDANG')
                            <td class="text-center px-1 py-2">
                                @if ($daysDaftarSidangPemb2 > 0)
                                    <span class="text-danger"> {{ $daysDaftarSidangPemb2 }} hari lagi</span>
                                @elseif($daysDaftarSidangPemb2 <= 0)
                                    Batas Waktu Persetujuan telah habis
                                @endif
                            </td>
                        @endif
                    @endif

                    @if (Str::length(Auth::guard('dosen')->user()) > 0)
                        @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                Auth::guard('dosen')->user()->role_id == 10 ||
                                Auth::guard('dosen')->user()->role_id == 11)
                            @if ($skripsi->keterangan == 'Menunggu persetujuan Koordinator Skripsi' && $skripsi->status_skripsi == 'DAFTAR SIDANG')
                                <td class="text-center px-1 py-2">
                                    @if ($daysDaftarSidangKoordinator > 0)
                                        <span class="text-danger"> {{ $daysDaftarSidangKoordinator }} hari lagi</span>
                                    @elseif($daysDaftarSidangKoordinator <= 0)
                                        Batas Waktu Persetujuan telah habis
                                    @endif
                                </td>
                            @endif
                        @endif
                    @endif


                    @if (Str::length(Auth::guard('dosen')->user()) > 0)
                        @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                Auth::guard('dosen')->user()->role_id == 7 ||
                                Auth::guard('dosen')->user()->role_id == 8)
                            @if (
                                $skripsi->keterangan == 'Menunggu persetujuan Koordinator Program Studi' &&
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG')
                                <td class="text-center px-1 py-2">
                                    @if ($daysDaftarSidangKaprodi > 0)
                                        <span class="text-danger"> {{ $daysDaftarSidangKaprodi }} hari lagi</span>
                                    @elseif($daysDaftarSidangKaprodi <= 0)
                                        Batas Waktu Persetujuan telah habis
                                    @endif
                                </td>
                            @endif
                        @endif
                    @endif
                @endif -->

                <!-- PERPANJANGAN REVISI -->

                <!-- @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                    @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1' && $skripsi->status_skripsi == 'PERPANJANGAN REVISI')
                        <td class="text-center px-1 py-2">
                            @if ($daysRevisiPemb1 > 0)
                                <span class="text-danger"> {{ $daysRevisiPemb1 }} hari lagi</span>
                            @elseif($daysRevisiPemb1 <= 0)
                                Batas Waktu Persetujuan telah habis
                            @endif
                        </td>
                    @endif
                @endif

                @if (Auth::guard('dosen')->user()->role_id == 6 ||
                        Auth::guard('dosen')->user()->role_id == 7 ||
                        Auth::guard('dosen')->user()->role_id == 8)
                    @if (
                        $skripsi->keterangan == 'Menunggu persetujuan Koordinator Program Studi' &&
                            $skripsi->status_skripsi == 'PERPANJANGAN REVISI')
                        <td class="text-center px-1 py-2">
                            @if ($daysRevisiKaprodi > 0)
                                <span class="text-danger"> {{ $daysRevisiKaprodi }} hari lagi</span>
                            @elseif($daysRevisiKaprodi <= 0)
                                Batas Waktu Persetujuan telah habis
                            @endif
                        </td>
                    @endif
                @endif -->

                <!-- PENYERAHAN BUKU SKRIPSI -->

                <!-- @if (Str::length(Auth::guard('dosen')->user()) > 0)
                    @if (Auth::guard('dosen')->user()->role_id == 9 ||
                            Auth::guard('dosen')->user()->role_id == 10 ||
                            Auth::guard('dosen')->user()->role_id == 11)
                        @if (
                            $skripsi->keterangan == 'Menunggu persetujuan Koordinator Skripsi' &&
                                $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI')
                            <td class="text-center px-1 py-2">
                                @if ($daysBukuSkripsiKoordinator > 0)
                                    <span class="text-danger"> {{ $daysBukuSkripsiKoordinator }} hari lagi</span>
                                @elseif($daysBukuSkripsiKoordinator <= 0)
                                    Batas Waktu Persetujuan telah habis
                                @endif
                            </td>
                        @endif
                    @endif
                @endif -->

                <td class="text-center px-1 py-2"> {{ $skripsi->keterangan }}</td>


                <!-- USUL JUDUL  -->
                @if ($skripsi->status_skripsi == 'USULAN JUDUL' || $skripsi->status_skripsi == 'USULAN JUDUL DISETUJUI')
                    <td class="text-center px-1 py-2">

                        @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                            @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1' && $skripsi->status_skripsi == 'USULAN JUDUL')
                                <div class="row ml-0 ml-md-4">
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <button onclick="tolakUsulJudulPemb1({{ $skripsi->id }})"
                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                                class="fas fa-times-circle"></i></button>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <a href="/kp-skripsi/persetujuan/usulanjudul/{{ $skripsi->id }}"
                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <form action="/usuljudul/pembimbing1/approve/{{ $skripsi->id }}"
                                            class="setujui-usuljudul-pemb1" method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                                title="Setujui"><i class="fas fa-check-circle"></i></button>
                                        </form>
                                    </div>
                            @endif
                        @endif
                        @if ($skripsi->pembimbing_2_nip == Auth::user()->nip)
                            @if ($skripsi->keterangan == 'Menunggu persetujuan Pembimbing 2' && $skripsi->status_skripsi == 'USULAN JUDUL')
                                <div class="row ml-0 ml-md-4">
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <button onclick="tolakUsulJudulPemb2({{ $skripsi->id }})"
                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                                class="fas fa-times-circle"></i></button>

                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <a href="/kp-skripsi/persetujuan/usulanjudul/{{ $skripsi->id }}"
                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <form action="/usuljudul/pembimbing2/approve/{{ $skripsi->id }}"
                                            class="setujui-usuljudul-pemb2" method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                                title="Setujui"><i class="fas fa-check-circle"></i></button>
                                        </form>

                                    </div>
                            @endif
                        @endif

                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                            @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                    Auth::guard('dosen')->user()->role_id == 10 ||
                                    Auth::guard('dosen')->user()->role_id == 11)
                                @if ($skripsi->keterangan == 'Menunggu persetujuan Koordinator Skripsi' && $skripsi->status_skripsi == 'USULAN JUDUL')
                                    <div class="row ml-0 ml-md-4">
                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                            <button onclick="tolakUsulJudulKoordinator({{ $skripsi->id }})"
                                                class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                title="Tolak"><i class="fas fa-times-circle"></i></button>
                                        </div>
                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                            <a href="/kp-skripsi/persetujuan/usulanjudul/{{ $skripsi->id }}"
                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                        </div>
                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                            <form action="/usuljudul/koordinator/approve/{{ $skripsi->id }}"
                                                class="setujui-usuljudul-koordinator" method="POST">
                                                @method('put')
                                                @csrf
                                                <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                                    title="Setujui"><i class="fas fa-check-circle"></i></button>
                                            </form>

                                        </div>
                                @endif
                            @endif
                        @endif



                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                            @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                    Auth::guard('dosen')->user()->role_id == 7 ||
                                    Auth::guard('dosen')->user()->role_id == 8)
                                @if (
                                    $skripsi->keterangan == 'Menunggu persetujuan Koordinator Program Studi' &&
                                        $skripsi->status_skripsi == 'USULAN JUDUL')
                                    <div class="row ml-0 ml-md-4">
                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                            <button onclick="tolakUsulJudulKaprodi({{ $skripsi->id }})"
                                                class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                title="Tolak"><i class="fas fa-times-circle"></i></button>
                                        </div>
                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                            <a href="/kp-skripsi/persetujuan/usulanjudul/{{ $skripsi->id }}"
                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                        </div>
                                        <div class="col-lg-3 col-12 py-2 py-md-0">
                                            <form action="/usuljudul/kaprodi/approve/{{ $skripsi->id }}"
                                                class="setujui-usuljudul-kaprodi" method="POST">
                                                @method('put')
                                                @csrf
                                                <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                                    title="Setujui"><i class="fas fa-check-circle"></i></button>
                                            </form>

                                        </div>
                                @endif
                            @endif
                        @endif

                    </td>
                @endif

                <!-- DAFTAR SEMPRO -->

                @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                    @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO' && $skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1')
                        <td class="text-center px-1 py-2">
                            <div class="row ml-0 ml-md-4">
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <button onclick="tolakSemproPemb1({{ $skripsi->id }})"
                                        class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                            class="fas fa-times-circle"></i></button>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <a href="/kp-skripsi/persetujuan/sempro/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <form action="/daftarsempro/pembimbing1/approve/{{ $skripsi->id }}"
                                        class="setujui-sempro-pemb1" method="POST">
                                        @method('put')
                                        @csrf
                                        <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                            title="Setujui"><i class="fas fa-check-circle"></i></button>
                                    </form>

                                </div>
                    @endif
                @endif

                @if (Str::length(Auth::guard('dosen')->user()) > 0)
                    @if (Auth::guard('dosen')->user()->role_id == 9 ||
                            Auth::guard('dosen')->user()->role_id == 10 ||
                            Auth::guard('dosen')->user()->role_id == 11)
                        @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO' && $skripsi->keterangan == 'Menunggu Jadwal Seminar Proposal')
                            <td class="text-center px-1 py-2">
                                <div class="row ml-0 ml-md-4">
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <button onclick="tolakSemproKoordinator({{ $skripsi->id }})"
                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                                class="fas fa-times-circle"></i></button>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <a href="/kp-skripsi/persetujuan/sempro/{{ $skripsi->id }}"
                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <form action="/daftar-sempro/koordinator/approve/{{ $skripsi->id }}"
                                            class="setujui-sempro-koordinator" method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                                title="Setujui"><i class="fas fa-check-circle"></i></button>
                                        </form>

                                    </div>
                        @endif
                    @endif
                @endif

                @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                    @if ($skripsi->status_skripsi == 'SEMPRO DIJADWALKAN' && $skripsi->keterangan == 'Seminar Proposal Dijadwalkan')
                        <td class="text-center px-1 py-2">
                            <div class="row ml-0 ml-md-4">
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <button onclick="tolakSelesaiSempro({{ $skripsi->id }})"
                                        class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                        title="Gagal Sempro"><i class="fas fa-times-circle"></i></button>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <a href="/kp-skripsi/persetujuan/sempro/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <form action="/selesaisempro/pembimbing/approve/{{ $skripsi->id }}"
                                        class="setujui-selesai-sempro-pemb1" method="POST">
                                        @method('put')
                                        @csrf
                                        <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                            title="Selesai Sempro"><i class="fas fa-check-circle"></i></button>
                                    </form>

                                </div>
                    @endif
                @endif


                @if ($skripsi->pembimbing_2_nip == Auth::user()->nip)
                    @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO' && $skripsi->keterangan == 'Menunggu persetujuan Pembimbing 2')
                        <td class="text-center px-1 py-2">
                            <div class="row ml-0 ml-md-4">
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <button onclick="tolakSemproPemb2({{ $skripsi->id }})"
                                        class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                            class="fas fa-times-circle"></i></button>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <a href="/kp-skripsi/persetujuan/sempro/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <form action="/daftarsempro/pembimbing2/approve/{{ $skripsi->id }}"
                                        class="setujui-sempro-pemb2" method="POST">
                                        @method('put')
                                        @csrf
                                        <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                            title="Setujui"><i class="fas fa-check-circle"></i></button>
                                    </form>

                                </div>
                    @endif
                @endif


                @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                    @if ($skripsi->status_skripsi == 'PERPANJANGAN 1' && $skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1')
                        <td class="text-center px-1 py-2">
                            <div class="row ml-0 ml-md-4">
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <button onclick="tolakPerpanjangan1Pembimbing({{ $skripsi->id }})"
                                        class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                        title="Gagal Sempro"><i class="fas fa-times-circle"></i></button>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <a href="/kp-skripsi/persetujuan/perpanjangan-1/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <form action="/perpanjangan1/pembimbing/approve/{{ $skripsi->id }}"
                                        class="setujui-perpanjangan1-pembimbing" method="POST">
                                        @method('put')
                                        @csrf
                                        <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                            title="Setujui"><i class="fas fa-check-circle"></i></button>
                                    </form>

                                </div>
                    @endif
                @endif

                @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                    @if ($skripsi->status_skripsi == 'PERPANJANGAN 2' && $skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1')
                        <td class="text-center px-1 py-2">
                            <div class="row ml-0 ml-md-4">
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <button onclick="tolakPerpanjangan2Pembimbing({{ $skripsi->id }})"
                                        class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                        title="Gagal Sempro"><i class="fas fa-times-circle"></i></button>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <a href="/kp-skripsi/persetujuan/perpanjangan-2/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <form action="/perpanjangan2/pembimbing/approve/{{ $skripsi->id }}"
                                        class="setujui-perpanjangan2-pembimbing" method="POST">
                                        @method('put')
                                        @csrf
                                        <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                            title="Setujui"><i class="fas fa-check-circle"></i></button>
                                    </form>

                                </div>
                    @endif
                @endif

                @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                    @if ($skripsi->status_skripsi == 'PERPANJANGAN REVISI' && $skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1')
                        <td class="text-center px-1 py-2">
                            <div class="row ml-0 ml-md-4">
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <button onclick="tolakPerpanjanganRevisiPembimbing({{ $skripsi->id }})"
                                        class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                        title="Gagal Sempro"><i class="fas fa-times-circle"></i></button>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <a href="/kp-skripsi/persetujuan/perpanjangan-revisi/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <form action="/perpanjangan-revisi/pembimbing/approve/{{ $skripsi->id }}"
                                        class="setujui-perpanjangan-revisi-pembimbing" method="POST">
                                        @method('put')
                                        @csrf
                                        <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                            title="Setujui"><i class="fas fa-check-circle"></i></button>
                                    </form>

                                </div>
                    @endif
                @endif


                @if (Str::length(Auth::guard('dosen')->user()) > 0)
                    @if (Auth::guard('dosen')->user()->role_id == 6 ||
                            Auth::guard('dosen')->user()->role_id == 7 ||
                            Auth::guard('dosen')->user()->role_id == 8)
                        @if (
                            $skripsi->keterangan == 'Menunggu persetujuan Koordinator Program Studi' &&
                                $skripsi->status_skripsi == 'PERPANJANGAN 1')
                            <td class="text-center px-1 py-2">
                                <div class="row ml-0 ml-md-4">
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <button onclick="tolakPerpanjangan1Kaprodi({{ $skripsi->id }})"
                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                                class="fas fa-times-circle"></i></button>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <a href="/kp-skripsi/persetujuan/perpanjangan-1/{{ $skripsi->id }}"
                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <form action="/perpanjangan1/kaprodi/approve/{{ $skripsi->id }}"
                                            class="setujui-perpanjangan1-kaprodi" method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                                title="Setujui"><i class="fas fa-check-circle"></i></button>
                                        </form>

                                    </div>
                        @endif
                        @if (
                            $skripsi->keterangan == 'Menunggu persetujuan Koordinator Program Studi' &&
                                $skripsi->status_skripsi == 'PERPANJANGAN 2')
                            <td class="text-center px-1 py-2">
                                <div class="row ml-0 ml-md-4">
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <button onclick="tolakPerpanjangan2Kaprodi({{ $skripsi->id }})"
                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                                class="fas fa-times-circle"></i></button>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <a href="/kp-skripsi/persetujuan/perpanjangan-2/{{ $skripsi->id }}"
                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <form action="/perpanjangan2/kaprodi/approve/{{ $skripsi->id }}"
                                            class="setujui-perpanjangan2-kaprodi" method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                                title="Setujui"><i class="fas fa-check-circle"></i></button>
                                        </form>

                                    </div>
                        @endif

                        @if (
                            $skripsi->keterangan == 'Menunggu persetujuan Koordinator Program Studi' &&
                                $skripsi->status_skripsi == 'PERPANJANGAN REVISI')
                            <td class="text-center px-1 py-2">
                                <div class="row ml-0 ml-md-4">
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <button onclick="tolakPerpanjanganRevisiKaprodi({{ $skripsi->id }})"
                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                                class="fas fa-times-circle"></i></button>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <a href="/kp-skripsi/persetujuan/perpanjangan-revisi/{{ $skripsi->id }}"
                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <form action="/perpanjangan-revisi/kaprodi/approve/{{ $skripsi->id }}"
                                            class="setujui-perpanjangan-revisi-kaprodi" method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                                title="Setujui"><i class="fas fa-check-circle"></i></button>
                                        </form>

                                    </div>
                        @endif
                    @endif
                @endif

                @if (Str::length(Auth::guard('dosen')->user()) > 0)
                    @if (Auth::guard('dosen')->user()->role_id == 9 ||
                            Auth::guard('dosen')->user()->role_id == 10 ||
                            Auth::guard('dosen')->user()->role_id == 11)
                        @if (
                            $skripsi->keterangan == 'Menunggu persetujuan Koordinator Skripsi' &&
                                $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI')
                            <td class="text-center px-1 py-2">
                                <div class="row ml-0 ml-md-4">
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <button onclick="tolakBukuSkripsiKoordinator({{ $skripsi->id }})"
                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                                class="fas fa-times-circle"></i></button>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <a href="/kp-skripsi/persetujuan/bukti-buku-skripsi/{{ $skripsi->id }}"
                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                    </div>
                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                        <form action="/buku-skripsi/koordinator/approve/{{ $skripsi->id }}"
                                            class="setujui-buku-skripsi-koordinator" method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                                title="Setujui"><i class="fas fa-check-circle"></i></button>
                                        </form>

                                    </div>
                        @endif

                        @if ($skripsi->keterangan == 'Proses Skripsi Selesai!' && $skripsi->status_skripsi == 'SKRIPSI SELESAI')
                            <td class="text-center px-1 py-2">
                                <div class="row ml-0 ml-md-4">
                                    <div class="col-4 py-2 py-md-0 col-lg-6">
                                        <a href="/kp-skripsi/persetujuan/bukti-buku-skripsi/{{ $skripsi->id }}"
                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                    </div>
                                    <div class="col-4 py-2 py-md-0 col-lg-6">
                                        <form action="/nilaiskripsikeluar/koordinator/approve/{{ $skripsi->id }}"
                                            class="setujui-lulus-koordinator" method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                                title="Lulus"><i class="fas fa-check-circle"></i></button>
                                        </form>

                                    </div>
                        @endif
                    @endif
                @endif


                <!-- DAFTAR SIDANG -->


                @if ($skripsi->pembimbing_1_nip == Auth::user()->nip)
                    @if ($skripsi->status_skripsi == 'DAFTAR SIDANG' && $skripsi->keterangan == 'Menunggu persetujuan Pembimbing 1')
                        <td class="text-center px-1 py-2">
                            <div class="row ml-0 ml-md-4">
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <button onclick="tolakSidangPemb1({{ $skripsi->id }})"
                                        class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                            class="fas fa-times-circle"></i></button>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <a href="/kp-skripsi/persetujuan/sidang/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </div>
                                <div class="col-lg-3 col-12 py-2 py-md-0">
                                    <form action="/daftarsidang/pembimbing1/approve/{{ $skripsi->id }}"
                                        class="setujui-sidang-pemb1" method="POST">
                                        @method('put')
                                        @csrf
                                        <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                            title="Setujui"><i class="fas fa-check-circle"></i></button>
                                    </form>

                                </div>
            </tr>
        @endif
        @if ($skripsi->status_skripsi == 'SIDANG DIJADWALKAN' && $skripsi->keterangan == 'Sidang Skripsi Dijadwalkan')
            <td class="text-center px-1 py-2">
                <div class="row ml-0 ml-md-4">
                    <div class="col-lg-3 col-12 py-2 py-md-0">
                        <button onclick="tolakSelesaiSidang({{ $skripsi->id }})" class="btn btn-danger badge p-1 "
                            data-bs-toggle="tooltip" title="Gagal Sidang"><i class="fas fa-times-circle"></i></button>
                    </div>
                    <div class="col-lg-3 col-12 py-2 py-md-0">
                        <a href="/kp-skripsi/persetujuan/sidang/{{ $skripsi->id }}" class="badge btn btn-info p-1"
                            data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                    </div>
                    <div class="col-lg-3 col-12 py-2 py-md-0">
                        <form action="/selesaisidang/pembimbing/approve/{{ $skripsi->id }}"
                            class="setujui-selesai-sidang-pemb1" method="POST">
                            @method('put')
                            @csrf
                            <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip" title="Selesai Sidang"><i
                                    class="fas fa-check-circle"></i></button>
                        </form>

                    </div>
                    </tr>
        @endif
        @endif
        @if ($skripsi->pembimbing_2_nip == Auth::user()->nip)
            @if ($skripsi->status_skripsi == 'DAFTAR SIDANG' && $skripsi->keterangan == 'Menunggu persetujuan Pembimbing 2')
                <td class="text-center px-1 py-2">
                    <div class="row ml-0 ml-md-4">
                        <div class="col-lg-3 col-12 py-2 py-md-0">
                            <button onclick="tolakSidangPemb2({{ $skripsi->id }})" class="btn btn-danger badge p-1 "
                                data-bs-toggle="tooltip" title="Tolak"><i class="fas fa-times-circle"></i></button>
                        </div>
                        <div class="col-lg-3 col-12 py-2 py-md-0">
                            <a href="/kp-skripsi/persetujuan/sidang/{{ $skripsi->id }}" class="badge btn btn-info p-1"
                                data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                        </div>
                        <div class="col-lg-3 col-12 py-2 py-md-0">
                            <form action="/daftarsidang/pembimbing2/approve/{{ $skripsi->id }}"
                                class="setujui-sidang-pemb2" method="POST">
                                @method('put')
                                @csrf
                                <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip" title="Setujui"><i
                                        class="fas fa-check-circle"></i></button>
                            </form>

                        </div>
                        </tr>
            @endif
        @endif


        @if (Str::length(Auth::guard('dosen')->user()) > 0)
            @if (Auth::guard('dosen')->user()->role_id == 9 ||
                    Auth::guard('dosen')->user()->role_id == 10 ||
                    Auth::guard('dosen')->user()->role_id == 11)
                @if ($skripsi->status_skripsi == 'DAFTAR SIDANG' && $skripsi->keterangan == 'Menunggu persetujuan Koordinator Skripsi')
                    <td class="text-center px-1 py-2">
                        <div class="row ml-0 ml-md-4">
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <button onclick="tolakSidangKoordinator({{ $skripsi->id }})"
                                    class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                        class="fas fa-times-circle"></i></button>
                            </div>
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <a href="/kp-skripsi/persetujuan/sidang/{{ $skripsi->id }}"
                                    class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                        class="fas fa-info-circle"></i></a>
                            </div>
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <form action="/daftar-sidang/koordinator/approve/{{ $skripsi->id }}"
                                    class="setujui-sidang-koordinator" method="POST">
                                    @method('put')
                                    @csrf
                                    <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                        title="Setujui"><i class="fas fa-check-circle"></i></button>
                                </form>

                            </div>
                            </tr>
                @endif
            @endif
        @endif


        @if (Str::length(Auth::guard('dosen')->user()) > 0)
            @if (Auth::guard('dosen')->user()->role_id == 6 ||
                    Auth::guard('dosen')->user()->role_id == 7 ||
                    Auth::guard('dosen')->user()->role_id == 8)
                @if (
                    $skripsi->status_skripsi == 'DAFTAR SIDANG' &&
                        $skripsi->keterangan == 'Menunggu persetujuan Koordinator Program Studi')
                    <td class="text-center px-1 py-2">
                        <div class="row ml-0 ml-md-4">
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <button onclick="tolakSidangKaprodi({{ $skripsi->id }})"
                                    class="btn btn-danger badge p-1 " data-bs-toggle="tooltip" title="Tolak"><i
                                        class="fas fa-times-circle"></i></button>
                            </div>
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <a href="/kp-skripsi/persetujuan/sidang/{{ $skripsi->id }}"
                                    class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                        class="fas fa-info-circle"></i></a>
                            </div>
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <form action="/daftar-sidang/kaprodi/approve/{{ $skripsi->id }}"
                                    class="setujui-sidang-kaprodi" method="POST">
                                    @method('put')
                                    @csrf
                                    <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                        title="Setujui"><i class="fas fa-check-circle"></i></button>
                                </form>

                            </div>
                            </tr>
                @endif
            @endif
        @endif
        @endforeach


        @if (Str::length(Auth::guard('dosen')->user()) > 0)
            @if (Auth::guard('dosen')->user()->role_id == 6 ||
                    Auth::guard('dosen')->user()->role_id == 7 ||
                    Auth::guard('dosen')->user()->role_id == 8 ||
                    Auth::guard('dosen')->user()->role_id == 9 ||
                    Auth::guard('dosen')->user()->role_id == 10 ||
                    Auth::guard('dosen')->user()->role_id == 11)
                @foreach ($penjadwalan_skripsis as $skripsi)
                    <tr>
                        <td class="text-center px-1 py-2">{{ $skripsi->mahasiswa->nim }}</td>
                        <td class="text-left pl-3 pr-1 fw-bold">{{ $skripsi->mahasiswa->nama }}</td>
                        <td class="bg-warning text-center px-1 py-2">Seminar {{ $skripsi->jenis_seminar }}</td>
                        <!-- <td class="text-center px-1 py-2">{{ $skripsi->prodi->nama_prodi }}</td>           -->
                        <td class="text-center px-1 py-2">
                            {{ Carbon::parse($skripsi->tanggal)->translatedFormat(' d F Y') }}</td>
                        <!--<td class="text-center px-1 py-2">-</td>-->
                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                            @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                    Auth::guard('dosen')->user()->role_id == 10 ||
                                    Auth::guard('dosen')->user()->role_id == 11)
                                <td class="text-center px-1 py-2">Menunggu Persetujuan Seminar Koordinator Skripsi</td>
                            @endif
                        @endif
                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                            @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                    Auth::guard('dosen')->user()->role_id == 7 ||
                                    Auth::guard('dosen')->user()->role_id == 8)
                                <td class="text-center px-1 py-2">Menunggu Persetujuan Seminar Koordinator Program Studi
                                </td>
                            @endif
                        @endif

                        <!-- <td class="text-center px-1 py-2">{{ $skripsi->lokasi }}</td>-->
                        <!-- <td class="text-center px-1 py-2">
                <p>1. {{ $skripsi->pembimbingsatu->nama_singkat }}</p>
                @if ($skripsi->pembimbingdua == !null)
    <p>2. {{ $skripsi->pembimbingdua->nama_singkat }}</p>
    @endif
              </td>
              <td class="text-center px-1 py-2">
                <p>1. {{ $skripsi->pengujisatu->nama_singkat }}</p>
                <p>2. {{ $skripsi->pengujidua->nama_singkat }}</p>
                @if ($skripsi->pengujitiga == !null)
    <p>3. {{ $skripsi->pengujitiga->nama_singkat }}</p>
    @endif
              </td>           -->
                        <!-- <td class="text-center px-1 py-2">
                <a href="/penilaian-skripsi/cek-nilai/{{ Crypt::encryptString($skripsi->id) }}" class="badge bg-success px-1 py-2"style="border-radius:20px;">Berita Acara</a>
              </td> -->

                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                            @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                    Auth::guard('dosen')->user()->role_id == 10 ||
                                    Auth::guard('dosen')->user()->role_id == 11)
                                <td class="text-center px-1 py-2">
                                    <div class="row ml-0 ml-md-4">
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <form action="/persetujuanskripsi-koordinator/tolak/{{ $skripsi->id }}"
                                    class="tolak-persetujuan-sidang-koordinator" method="POST">
                                    @method('put')
                                    @csrf
                                    <button class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                        title="Tolak"><i class="fas fa-times-circle"></i></button>
                                </form>
                            </div>
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <a href="/penilaian-skripsi/cek-nilai/{{ Crypt::encryptString($skripsi->id) }}"
                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                            </div>
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <form action="/persetujuanskripsi-koordinator/approve/{{ $skripsi->id }}"
                                    class="setujui-persetujuan-sidang-koordinator" method="POST">
                                    @method('put')
                                    @csrf
                                    <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                        title="Setujui"><i class="fas fa-check-circle"></i></button>
                                </form>
                            </div>
                                </td>
                            @endif
                        @endif

                        @if (Str::length(Auth::guard('dosen')->user()) > 0)
                            @if (Auth::guard('dosen')->user()->role_id == 6 ||
                                    Auth::guard('dosen')->user()->role_id == 7 ||
                                    Auth::guard('dosen')->user()->role_id == 8)
                                <td class="text-center px-1 py-2">
                                    <div class="row ml-0 ml-md-4">
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <form action="/persetujuanskripsi-kaprodi/tolak/{{ $skripsi->id }}"
                                    class="tolak-persetujuan-sidang-kaprodi" method="POST">
                                    @method('put')
                                    @csrf
                                    <button class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                        title="Tolak"><i class="fas fa-times-circle"></i></button>
                                </form>
                            </div>
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                 <a href="/penilaian-skripsi/cek-nilai/{{ Crypt::encryptString($skripsi->id) }}"
                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                            </div>
                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                <form action="/persetujuanskripsi-kaprodi/approve/{{ $skripsi->id }}"
                                    class="setujui-persetujuan-sidang-kaprodi" method="POST">
                                    @method('put')
                                    @csrf
                                    <button class="btn btn-success badge p-1 " data-bs-toggle="tooltip"
                                        title="Setujui"><i class="fas fa-check-circle"></i></button>
                                </form>
                            </div>
                                </td>
                            @endif
                        @endif

                    </tr>
                @endforeach
            @endif
        @endif



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


@push('scripts')
    @foreach ($pendaftaran_kp as $kp)
        <script>
            //APPROVAL KERJA PRAKTEK
            $('.setujui-usulankp-pembimbing').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan KP!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju',
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakUsulanKPPembimbing(id) {
                Swal.fire({
                    title: 'Tolak Usulan KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({

                            title: 'Tolak Usulan KP',
                            html: `
                        <form  action="/usulankp/pembimbing/tolak/${id}" method="POST">
                        @method('put')
                           @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control @error('alasan') is-invalid @enderror" value="{{ old('alasan') }}" name="alasan" rows="4" cols="50" autofocus required></textarea>
                            @error('alasan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            <br>
                            <button type="submit"  class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                      
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-usulankp-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan KP!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });


            function tolakUsulanKPKoordinator(id) {
                Swal.fire({
                    title: 'Tolak Usulan KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan KP',
                            html: `
                        <form id="reasonForm" action="/usulankp/koordinator/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-usulankp-kaprodi').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan KP!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakUsulanKPKaprodi(id) {
                Swal.fire({
                    title: 'Tolak Usulan KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan KP',
                            html: `
                        <form id="reasonForm" action="/usulankp/kaprodi/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }


            $('.setujui-balasankp-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Surat Balasan Peusahaan!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakBalasanKPKoordinator(id) {
                Swal.fire({
                    title: 'Tolak Surat Basalan Perusahaan KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Surat Basalan Perusahaan KP',
                            html: `
                        <form id="reasonForm" action="/balasankp/koordinator/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-semkp-pembimbing').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Seminar KP!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSemKPPemb(id) {
                Swal.fire({
                    title: 'Tolak Usulan Seminar KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Seminar KP',
                            html: `
                        <form id="reasonForm" action="/usulan-semkp/pembimbing/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }
            $('.setujui-semkp-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Seminar KP!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSemKPKoordinator(id) {
                Swal.fire({
                    title: 'Tolak Usulan Seminar KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Seminar KP',
                            html: `
                        <form id="reasonForm" action="/usulan-semkp/koordinator/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }
            $('.setujui-semkp-kaprodi').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Seminar KP!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSemKPKaprodi(id) {
                Swal.fire({
                    title: 'Tolak Usulan Seminar KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Seminar KP',
                            html: `
                        <form id="reasonForm" action="/usulan-semkp/kaprodi/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-selesai-semkp-pembimbing').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Selesai Seminar KP!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Selesai'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakGagalSemKPPemb(id) {
                Swal.fire({
                    title: 'Gagal Seminar KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Gagal',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Gagal Seminar KP',
                            html: `
                        <form id="reasonForm" action="/selesaiseminar-kp/pembimbing/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-kpti10-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Bukti Penyerahan Laporan KP!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakKPTI10Koordinator(id) {
                Swal.fire({
                    title: 'Tolak KPTI/TE-10/Bukti Penyerahan Laporan KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak KPTI/TE-10/Bukti Penyerahan Laporan KP',
                            html: `
                        <form id="reasonForm" action="/kpti10/koordinator/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-nilai-kp-keluar-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Nilai KP Keluar!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });
        </script>
    @endforeach
@endpush()


<!-- PENDAFTARAN SKRIPSI -->
@push('scripts')
    @foreach ($pendaftaran_skripsi as $skripsi)
        <script>
            //APROVAL SKRIPSI
            $('.setujui-usuljudul-pemb1').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan Judul Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakUsulJudulPemb1(id) {
                Swal.fire({
                    title: 'Tolak Usulan Judul Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Judul Skripsi',
                            html: `
                        <form id="reasonForm" action="/usuljudul/pembimbing1/tolak/${id}" method="POST">
                        @method('put')
                          @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-usuljudul-pemb2').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan Judul Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakUsulJudulPemb2(id) {
                Swal.fire({
                    title: 'Tolak Usulan Judul Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Judul Skripsi',
                            html: `
                        <form id="reasonForm" action="/usuljudul/pembimbing2/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-usuljudul-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan Judul Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakUsulJudulKoordinator(id) {
                Swal.fire({
                    title: 'Tolak Usulan Judul Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Judul Skripsi',
                            html: `
                        <form id="reasonForm" action="/usuljudul/koordinator/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-usuljudul-kaprodi').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan Judul Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakUsulJudulKaprodi(id) {
                Swal.fire({
                    title: 'Tolak Usulan Judul Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Judul Skripsi',
                            html: `
                        <form id="reasonForm" action="/usuljudul/kaprodi/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }


            //SEMPRO
            $('.setujui-sempro-pemb1').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Daftar Sempro!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSemproPemb1(id) {
                Swal.fire({
                    title: 'Tolak Usulan Seminar Proposal',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Seminar Proposal',
                            html: `
                        <form id="reasonForm" action="/daftarsempro/pembimbing1/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-sempro-pemb2').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Daftar Sempro!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSemproPemb2(id) {
                Swal.fire({
                    title: 'Tolak Usulan Seminar Proposal',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Seminar Proposal',
                            html: `
                        <form id="reasonForm" action="/daftarsempro/pembimbing2/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-sempro-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Daftar Sempro!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSemproKoordinator() {
                Swal.fire({
                    title: 'Tolak Usulan Seminar Proposal',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Seminar Proposal',
                            html: `
                        <form id="reasonForm" action="/daftar-sempro/koordinator/tolak/{{ $skripsi->id }}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }


            $('.setujui-selesai-sempro-pemb1').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Selesai Seminar Proposal!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Selesai'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSelesaiSempro(id) {
                Swal.fire({
                    title: 'Gagal Seminar Proposal',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Gagal',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Gagal Seminar Proposal',
                            html: `
                        <form id="reasonForm" action="/selesaisempro/pembimbing/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }
            $('.setujui-perpanjangan1-pembimbing').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Perpanjangan 1 Waktu Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakPerpanjangan1Pembimbing(id) {
                Swal.fire({
                    title: 'Tolak Perpanjangan 1 Waktu Skripsi!',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Perpanjangan 1 Waktu Skripsi',
                            html: `
                        <form id="reasonForm" action="/perpanjangan1/pembimbing/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }
            $('.setujui-perpanjangan1-kaprodi').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Perpanjangan 1 Waktu Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakPerpanjangan1Kaprodi(id) {
                Swal.fire({
                    title: 'Tolak Perpanjangan 1 Waktu Skripsi!',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Perpanjangan 1 Waktu Skripsi',
                            html: `
                        <form id="reasonForm" action="/perpanjangan1/kaprodi/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }


            $('.setujui-perpanjangan2-pembimbing').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Perpanjangan 2 Waktu Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakPerpanjangan2Pembimbing(id) {
                Swal.fire({
                    title: 'Tolak Perpanjangan 2 Waktu Skripsi!',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Perpanjangan 2 Waktu Skripsi',
                            html: `
                        <form id="reasonForm" action="/perpanjangan2/pembimbing/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }
            $('.setujui-perpanjangan2-kaprodi').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Perpanjangan 2 Waktu Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakPerpanjangan2Kaprodi(id) {
                Swal.fire({
                    title: 'Tolak Perpanjangan 2 Waktu Skripsi!',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Perpanjangan 2 Waktu Skripsi',
                            html: `
                        <form id="reasonForm" action="/perpanjangan2/kaprodi/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }



            // DAFTAR SIDANG PEMBIMBING 1
            $('.setujui-sidang-pemb1').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Daftar Sidang!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSidangPemb1(id) {
                Swal.fire({
                    title: 'Tolak Usulan Sidang Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Sidang Skripsi',
                            html: `
                        <form id="reasonForm" action="/daftarsidang/pembimbing1/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            //DAFTAR SIDANG PEMBIMBING 2
            $('.setujui-sidang-pemb2').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Daftar Sidang!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSidangPemb2(id) {
                Swal.fire({
                    title: 'Tolak Usulan Sidang Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Sidang Skripsi',
                            html: `
                        <form id="reasonForm" action="/daftarsidang/pembimbing2/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }
            $('.setujui-sidang-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Daftar Sidang!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSidangKoordinator(id) {
                Swal.fire({
                    title: 'Tolak Usulan Sidang Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Sidang Skripsi',
                            html: `
                        <form id="reasonForm" action="/daftar-sidang/koordinator/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }


            $('.setujui-sidang-kaprodi').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Daftar Sidang!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSidangKaprodi(id) {
                Swal.fire({
                    title: 'Tolak Usulan Sidang Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Sidang Skripsi',
                            html: `
                        <form id="reasonForm" action="/daftar-sidang/kaprodi/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }


            $('.setujui-selesai-sidang-pemb1').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Selesai Sidang Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Selesai'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSelesaiSidang(id) {
                Swal.fire({
                    title: 'Gagal Sidang Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Gagal',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Gagal Sidang Skripsi',
                            html: `
                        <form id="reasonForm" action="/selesaisidang/pembimbing/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            //PERPANJANGAN REVISI

            $('.setujui-perpanjangan-revisi-pembimbing').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Perpanjangan Revisi Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakPerpanjanganRevisiPembimbing(id) {
                Swal.fire({
                    title: 'Tolak Perpanjangan Revisi Skripsi!',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Perpanjangan Revisi Skripsi',
                            html: `
                        <form id="reasonForm" action="/perpanjangan-revisi/pembimbing/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }
            $('.setujui-perpanjangan-revisi-kaprodi').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Perpanjangan Revisi Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakPerpanjanganRevisiKaprodi(id) {
                Swal.fire({
                    title: 'Tolak Perpanjangan Revisi Skripsi!',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Perpanjangan Revisi Skripsi',
                            html: `
                        <form id="reasonForm" action="/perpanjangan-revisi/kaprodi/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }



            $('.setujui-buku-skripsi-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Bukti Penyerahan Buku Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakBukuSkripsiKoordinator(id) {
                Swal.fire({
                    title: 'Tolak Bukti Penyerahan Buku Skripsi!',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Bukti Penyerahan Buku Skripsi',
                            html: `
                        <form id="reasonForm" action="/buku-skripsi/koordinator/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }
            $('.setujui-lulus-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Lulus Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });
            
            // PERSETUJUAN SIDANG KOORDINATOR DAN KAPRODI
            $('.tolak-persetujuan-sidang-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Tolak Seminar Sidang Skripsi?',
                    text: "Apakah Anda Yakin? Data Akan dikembalikan ke Ketua Penguji",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Tolak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            $('.setujui-persetujuan-sidang-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Seminar Sidang Skripsi?',
                    text: "Apakah Anda Yakin? Data tidak bisa dikembalikan",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });
            
            $('.tolak-persetujuan-sidang-kaprodi').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Tolak Seminar Sidang Skripsi?',
                    text: "Apakah Anda Yakin? Data Akan dikembalikan ke Ketua Penguji",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Tolak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            $('.setujui-persetujuan-sidang-kaprodi').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Seminar Sidang Skripsi?',
                    text: "Apakah Anda Yakin? Data tidak bisa dikembalikan",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });




            //  $('.setujui-perpanjangan-revisi-pemb1').submit(function(event) {
            //     event.preventDefault();
            //     Swal.fire({
            //         title: 'Setujui Perpanjangan Revisi Skripsi!',
            //         text: "Apakah Anda Yakin?",
            //         icon: 'question',
            //         showCancelButton: true,
            //         cancelButtonText: 'Batal',
            //         confirmButtonColor: '#28a745',
            //         cancelButtonColor: 'grey',
            //         confirmButtonText: 'Setuju'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             event.currentTarget.submit();
            //         }
            //     })
            // });

            // $('.tolak-perpanjangan-revisi-pemb1').submit(function(event) {
            //     event.preventDefault();
            //     Swal.fire({
            //         title: 'Tolak Perpanjangan Revisi Skripsi!',
            //         text: "Apakah Anda Yakin?",
            //         icon: 'question',
            //         showCancelButton: true,
            //         cancelButtonText: 'Batal',
            //         confirmButtonColor: '#dc3545',
            //         cancelButtonColor: 'grey',
            //         confirmButtonText: 'Tolak'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             event.currentTarget.submit();
            //         }
            //     })
            // });
            //   //PEMBIMBING 2
            // $('.setujui-perpanjangan-revisi-pemb2').submit(function(event) {
            //     event.preventDefault();
            //     Swal.fire({
            //         title: 'Setujui Perpanjangan Revisi Skripsi!',
            //         text: "Apakah Anda Yakin?",
            //         icon: 'question',
            //         showCancelButton: true,
            //         cancelButtonText: 'Batal',
            //         confirmButtonColor: '#28a745',
            //         cancelButtonColor: 'grey',
            //         confirmButtonText: 'Setuju'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             event.currentTarget.submit();
            //         }
            //     })
            // });

            // $('.tolak-perpanjangan-revisi-pemb2').submit(function(event) {
            //     event.preventDefault();
            //     Swal.fire({
            //         title: 'Tolak Perpanjangan Revisi Skripsi!',
            //         text: "Apakah Anda Yakin?",
            //         icon: 'question',
            //         showCancelButton: true,
            //         cancelButtonText: 'Batal',
            //         confirmButtonColor: '#dc3545',
            //         cancelButtonColor: 'grey',
            //         confirmButtonText: 'Tolak'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             event.currentTarget.submit();
            //         }
            //     })
            // });
        </script>
    @endforeach
@endpush()
