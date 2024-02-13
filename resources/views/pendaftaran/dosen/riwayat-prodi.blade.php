@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Riwayat
@endsection

@section('sub-title')
    Riwayat
@endsection

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="container card p-4">

        <ol class="breadcrumb col-lg-12">

            @if (Str::length(Auth::guard('dosen')->user()) > 0)
                @if (Auth::guard('dosen')->user()->role_id == 5 ||
                        Auth::guard('dosen')->user()->role_id == 6 ||
                        Auth::guard('dosen')->user()->role_id == 6 ||
                        Auth::guard('dosen')->user()->role_id == 7 ||
                        Auth::guard('dosen')->user()->role_id == 8 ||
                        Auth::guard('dosen')->user()->role_id == 9 ||
                        Auth::guard('dosen')->user()->role_id == 10 ||
                        Auth::guard('dosen')->user()->role_id == 11)
                    <li><a href="/prodi/kp-skripsi/seminar" class="px-1">Seminar
                        (<span>{{ $jml_seminar_kp + $jml_sempro + $jml_sidang }}</span>)</a></li>
                    <span class="px-2">|</span>
                    <li><a href="/kerja-praktek" class="px-1">Data
                            KP (<span>{{ $jml_prodi_kp }}</span>)</a></li>
                    <span class="px-2">|</span>
                    <li><a href="/skripsi" class="px-1">Data Skripsi (<span>{{ $jml_prodi_skripsi }}</span>)</a>
                    </li>
                    <span class="px-2">|</span>
                    <li><a href="/prodi/riwayat" class="breadcrumb-item active fw-bold text-success px-1">Riwayat
                            (<span>{{ $jml_riwayat_prodi_kp + $jml_riwayat_prodi_skripsi + $jml_riwayat_seminar_kp + $jml_riwayat_sempro + $jml_riwayat_skripsi }}</span>)</a>
                    </li>
                @endif
            @endif

            @if (Str::length(Auth::guard('web')->user()) > 0)
                @if (Auth::guard('web')->user()->role_id == 1 ||
                        Auth::guard('web')->user()->role_id == 2 ||
                        Auth::guard('web')->user()->role_id == 3 ||
                        Auth::guard('web')->user()->role_id == 4)

                    @if (Auth::guard('web')->user()->role_id == 2 ||
                            Auth::guard('web')->user()->role_id == 3 ||
                            Auth::guard('web')->user()->role_id == 4)
                        <li><a href="/persetujuan/admin/index" class=" px-1">Persetujuan
                                (<span>{{ $jml_persetujuan_kp + $jml_persetujuan_skripsi }}</span>)</a></li>
                        <span class="px-2">|</span>
                    @endif
                    <li><a href="/form" class="px-1">Seminar
                            (<span>{{ $jml_seminar_kp + $jml_sempro + $jml_sidang }}</span>)</a></li>
                    <span class="px-2">|</span>
                    <li><a href="/kerja-praktek/admin/index" class="px-1">Data KP (<span>{{ $jml_prodi_kp }}</span>)</a>
                    </li>

                    <span class="px-2">|</span>
                    <li><a href="/sidang/admin/index" class="px-1">Data Skripsi
                            (<span>{{ $jml_prodi_skripsi }}</span>)</a></li>

                    <span class="px-2">|</span>
                    <li><a href="/prodi/riwayat" class="breadcrumb-item active fw-bold text-success px-1">Riwayat
                            (<span>{{ $jml_riwayat_prodi_kp + $jml_riwayat_prodi_skripsi + $jml_riwayat_seminar_kp + $jml_riwayat_sempro + $jml_riwayat_skripsi }}</span>)</a>
                    </li>

                @endif
            @endif

        </ol>

        <div class="container-fluid">

            <div class="mb-4 rounded bg-light">
                <div class="p-2 pt-3">
                    <h5 class="">Riwayat KP dan Skripsi</h5>
                    <hr>
                </div>
            </div>

            <table class="table table-responsive-lg table-bordered table-striped" width="100%" id="datatables">
                <thead class="table-dark">
                    <tr>
                        <!-- <th class="text-center" scope="col">No.</th> -->
                        <th class="text-center" scope="col">NIM</th>
                        <th class="text-center" scope="col">Nama</th>
                        <!-- <th class="text-center" scope="col">Konsentrasi</th>   -->
                        <!-- <th class="text-center" scope="col">Jenis Usulan</th> -->
                        <th class="text-center" scope="col">Status</th>
                        <th class="text-center" scope="col">Keterangan</th>
                        <th class="text-center" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($pendaftaran_kp as $kp)
                        <div></div>
                        <tr>
                            <!-- <td class="text-center">{{ $loop->iteration }}</td>                              -->
                            <td class="text-center">{{ $kp->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 fw-bold">{{ $kp->mahasiswa->nama }}</td>
                            <!-- <td class="text-center">{{ $kp->mahasiswa->konsentrasi->nama_konsentrasi }}</td>            -->
                            <!-- <td class="text-center">{{ $kp->jenis_usulan }}</td>                       -->
                            <td class="text-center bg-info">{{ $kp->status_kp }}</td>

                            <td class="text-center">{{ $kp->keterangan }}</td>

                            <td class="text-center">
                                <a href="/kpti10-kp/detail/riwayat/{{ $kp->id }}" class="badge btn btn-info p-1"
                                    data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                            </td>

                        </tr>
                    @endforeach

                    @foreach ($pendaftaran_skripsi as $skripsi)
                        <div></div>
                        <tr>
                            <!-- <td class="text-center">{{ $loop->iteration }}</td>-->
                            <td class="text-center">{{ $skripsi->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 fw-bold">{{ $skripsi->mahasiswa->nama }}</td>
                            <!-- <td class="text-center">{{ $skripsi->konsentrasi->nama_konsentrasi }}</td>-->

                            <!-- <td class="text-center">{{ $skripsi->jenis_usulan }}</td>    -->
                            <!-- USUL JUDUL  -->

                            @if ($skripsi->status_skripsi == 'LULUS')
                                <td class="text-center bg-info">{{ $skripsi->status_skripsi }}</td>
                            @endif
                            <!-- ___________batas____________ -->

                            <td class="text-center">{{ $skripsi->keterangan }}</td>
                            <!-- USUL JUDUL  -->
                            @if ($skripsi->status_skripsi == 'LULUS')
                                <td class="text-center">
                                    <a href="/bukti-buku-skripsi/riwayat/detail/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1 mb-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </td>
                            @endif
                        </tr>
                    @endforeach

                </tbody>


            </table>
        </div>
    </div>

    <div class="container card p-4 my-5">
        <div class="container-fluid">
            <!-- <hr class="pt-1 mt-2 bg-dark"> -->

            <div class="mb-4 rounded bg-light">
                <div class="p-2 pt-3">
                    <h5 class="">Riwayat Seminar</h5>
                    <hr>
                </div>
            </div>

            <table class="table table-responsive-lg table-bordered table-striped" style="width:100%" id="datatables5">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" scope="col">NIM</th>
                        <th class="text-center" scope="col">Nama</th>
                        <th class="text-center" scope="col">Seminar</th>
                        <th class="text-center" scope="col">Prodi</th>
                        <th class="text-center" scope="col">Tanggal</th>
                        <th class="text-center" scope="col">Waktu</th>
                        <th class="text-center" scope="col">Lokasi</th>
                        <th class="text-center" scope="col">Pembimbing</th>
                        <th class="text-center" scope="col">Penguji</th>
                        <!-- <th class="text-center" scope="col">Hasil</th> -->
                        <th class="text-center" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($penjadwalan_kps as $kp)
                        <tr>
                            <td class="text-center">{{ $kp->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 fw-bold">{{ $kp->mahasiswa->nama }}</td>
                            <td class="bg-primary text-center">{{ $kp->jenis_seminar }}</td>
                            <td class="text-center">{{ $kp->prodi->nama_prodi }}</td>
                            <td class="text-center">{{ Carbon::parse($kp->tanggal)->translatedFormat('l, d F Y') }}</td>
                            <td class="text-center">{{ $kp->waktu }}</td>
                            <td class="text-center">{{ $kp->lokasi }}</td>
                            <td class="text-center">
                                <p>{{ $kp->pembimbing->nama_singkat }}</p>
                            </td>
                            <td class="text-center">
                                <p>{{ $kp->penguji->nama_singkat }}</p>
                            </td>
                            <!-- @if ($kp->status_seminar == 1)
                                <td class="text-center">Lulus</td>
                            @else
                                <td class="text-center">Belum Lulus</td>
                            @endif -->

                            <td class="text-center">
                               <a formtarget="_blank" target="_blank"
                                    href="/perbaikan-pengujikp/{{ Crypt::encryptString($kp->id) }}/{{ $kp->penguji->nip }}"
                                    class="badge bg-info p-2"style="border-radius:20px;">Perbaikan Penguji</a>
                                <a formtarget="_blank" target="_blank" href="/nilai-kp/{{ Crypt::encryptString($kp->id) }}"
                                    class="badge bg-success mt-2 p-2"style="border-radius:20px;">Nilai Penguji</a>
                                <a formtarget="_blank" target="_blank"
                                    href="/beritaacara-kp/{{ Crypt::encryptString($kp->id) }}"
                                    class="badge bg-danger mt-2 p-2"style="border-radius:20px;">Berita Acara</a>
                            </td>
                        </tr>
                    @endforeach

                    @foreach ($penjadwalan_sempros as $sempro)
                        <tr>
                            <td class="text-center">{{ $sempro->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 fw-bold">{{ $sempro->mahasiswa->nama }}</td>
                            <td class="bg-success text-center">{{ $sempro->jenis_seminar }}</td>
                            <td class="text-center">{{ $sempro->prodi->nama_prodi }}</td>
                            <td class="text-center">{{ Carbon::parse($sempro->tanggal)->translatedFormat('l, d F Y') }}
                            </td>
                            <td class="text-center">{{ $sempro->waktu }}</td>
                            <td class="text-center">{{ $sempro->lokasi }}</td>
                            <td class="text-center">
                                @if ($sempro->pembimbingsatu == !null)
                                <p>1. {{ $sempro->pembimbingsatu->nama_singkat }}</p>
                                @endif
                                @if ($sempro->pembimbingdua == !null)
                                    <p>2. {{ $sempro->pembimbingdua->nama_singkat }}</p>
                                @endif
                            </td>
                            <td class="text-center">
                                <p>1. {{ $sempro->pengujisatu->nama_singkat }}</p>
                                <p>2. {{ $sempro->pengujidua->nama_singkat }}</p>
                                @if ($sempro->pengujitiga == !null)
                                    <p>3. {{ $sempro->pengujitiga->nama_singkat }}</p>
                                @endif
                            </td>
                            <!-- @if ($sempro->status_seminar == 1)
                                <td class="text-center">Lulus</td>
                            @else
                                <td class="text-center">Belum Lulus</td>
                            @endif -->

                            <td class="text-center">
                                @if ($sempro->pembimbingsatu == !null)
                               <a formtarget="_blank" target="_blank"
                                    href="/nilai-sempro-pembimbing/{{ Crypt::encryptString($sempro->id) }}/{{ $sempro->pembimbingsatu->nip }}"
                                    class="badge bg-primary p-2" style="border-radius:20px;">Nilai Pembimbing 1</a>
                                @endif
                                @if ($sempro->pembimbingdua == !null)
                                    <a formtarget="_blank" target="_blank"
                                        href="/nilai-sempro-pembimbing/{{ Crypt::encryptString($sempro->id) }}/{{ $sempro->pembimbingdua->nip }}"
                                        class="badge bg-info p-2 mt-1" style="border-radius:20px;">Nilai Pembimbing 2</a>
                                @endif
                                <a formtarget="_blank" target="_blank"
                                    href="/nilai-sempro-penguji/{{ Crypt::encryptString($sempro->id) }}/{{ $sempro->pengujisatu->nip }}"
                                    class="badge bg-danger p-2 mt-1" style="border-radius:20px;">Nilai Penguji 1</a>
                                <a formtarget="_blank" target="_blank"
                                    href="/nilai-sempro-penguji/{{ Crypt::encryptString($sempro->id) }}/{{ $sempro->pengujidua->nip }}"
                                    class="badge bg-warning p-2 mt-1" style="border-radius:20px;">Nilai Penguji 2</a>
                                @if ($sempro->pengujitiga == !null)
                                    <a formtarget="_blank" target="_blank"
                                        href="/nilai-sempro-penguji/{{ Crypt::encryptString($sempro->id) }}/{{ $sempro->pengujitiga->nip }}"
                                        class="badge bg-success p-2 mt-1" style="border-radius:20px;">Nilai Penguji 3</a>
                                @endif
                                <a formtarget="_blank" target="_blank"
                                    href="/perbaikan-pengujisempro/{{ Crypt::encryptString($sempro->id) }}/{{ $sempro->pengujisatu->nip }}"
                                    class="badge bg-danger p-2 mt-1" style="border-radius:20px;">Perbaikan Penguji 1</a>
                                <a formtarget="_blank" target="_blank"
                                    href="/perbaikan-pengujisempro/{{ Crypt::encryptString($sempro->id) }}/{{ $sempro->pengujidua->nip }}"
                                    class="badge bg-warning p-2 mt-1" style="border-radius:20px;">Perbaikan Penguji 2</a>
                                @if ($sempro->pengujitiga == !null)
                                    <a formtarget="_blank" target="_blank"
                                        href="/perbaikan-pengujisempro/{{ Crypt::encryptString($sempro->id) }}/{{ $sempro->pengujitiga->nip }}"
                                        class="badge bg-success p-2 mt-1" style="border-radius:20px;">Perbaikan Penguji
                                        3</a>
                                @endif
                                <a formtarget="_blank" target="_blank"
                                    href="/penilaian-sempro/beritaacara-sempro/{{ Crypt::encryptString($sempro->id) }}"
                                    class="badge bg-primary p-2 mt-1" style="border-radius:20px;">Berita Acara</a>
                                @if (Str::length(Auth::guard('web')->user()) > 0)
                                @if (Auth::guard('web')->user()->role_id == 2 ||
                                Auth::guard('web')->user()->role_id == 3 ||
                                Auth::guard('web')->user()->role_id == 4)

                                @if ($sempro->revisi_proposal == !null)
                                    <a formtarget="_blank" target="_blank"
                                        href="/penilaian-sempro/riwayat-judul/{{ Crypt::encryptString($sempro->id) }}"
                                        class="badge bg-warning p-2 mt-1" style="border-radius:20px;">Revisi Judul</a>
                                @endif

                                @endif
                                @endif

                            </td>
                        </tr>
                    @endforeach

                    @foreach ($penjadwalan_skripsis as $skripsi)
                        <tr>
                            <td class="text-center">{{ $skripsi->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 fw-bold">{{ $skripsi->mahasiswa->nama }}</td>
                            <td class="bg-warning text-center">{{ $skripsi->jenis_seminar }}</td>
                            <td class="text-center">{{ $skripsi->prodi->nama_prodi }}</td>
                            <td class="text-center">{{ Carbon::parse($skripsi->tanggal)->translatedFormat('l, d F Y') }}
                            </td>
                            <td class="text-center">{{ $skripsi->waktu }}</td>
                            <td class="text-center">{{ $skripsi->lokasi }}</td>
                            <td class="text-center">
                                @if ($skripsi->pembimbingsatu == !null)
                                <p>1. {{ $skripsi->pembimbingsatu->nama_singkat }}</p>
                                @endif
                                @if ($skripsi->pembimbingdua == !null)
                                    <p>2. {{ $skripsi->pembimbingdua->nama_singkat }}</p>
                                @endif
                            </td>
                            <td class="text-center">
                                <p>1. {{ $skripsi->pengujisatu->nama_singkat }}</p>
                                <p>2. {{ $skripsi->pengujidua->nama_singkat }}</p>
                                @if ($skripsi->pengujitiga == !null)
                                    <p>3. {{ $skripsi->pengujitiga->nama_singkat }}</p>
                                @endif
                            </td>

                            <!-- @if ($skripsi->status_seminar == 3)
                                <td class="text-center">Lulus</td>
                            @elseif ($skripsi->status_seminar == 2)
                                <td class="text-center">Menunggu persetujuan Koordinator Program Studi</td>
                            @elseif ($skripsi->status_seminar == 1)
                                <td class="text-center">Menunggu persetujuan Koordinator Skripsi</td>
                            @else
                                <td class="text-center">Belum Lulus</td>
                            @endif -->

                            <td class="text-center">
                                
                                @if ($skripsi->pembimbingsatu == !null)
                               <a formtarget="_blank" target="_blank"
                                    href="/nilai-skripsi-pembimbing/{{ Crypt::encryptString($skripsi->id) }}/{{ $skripsi->pembimbingsatu->nip }}"
                                    class="badge bg-primary p-2" style="border-radius:20px;">Nilai Pembimbing 1</a>
                                @endif
                                @if ($skripsi->pembimbingdua == !null)
                                    <a formtarget="_blank" target="_blank"
                                        href="/nilai-skripsi-pembimbing/{{ Crypt::encryptString($skripsi->id) }}/{{ $skripsi->pembimbingdua->nip }}"
                                        class="badge bg-info p-2 mt-1" style="border-radius:20px;">Nilai Pembimbing 2</a>
                                @endif
                                <a formtarget="_blank" target="_blank"
                                    href="/nilai-skripsi-penguji/{{ Crypt::encryptString($skripsi->id) }}/{{ $skripsi->pengujisatu->nip }}"
                                    class="badge bg-danger p-2 mt-1" style="border-radius:20px;">Nilai Penguji 1</a>
                                <a formtarget="_blank" target="_blank"
                                    href="/nilai-skripsi-penguji/{{ Crypt::encryptString($skripsi->id) }}/{{ $skripsi->pengujidua->nip }}"
                                    class="badge bg-warning p-2 mt-1" style="border-radius:20px;">Nilai Penguji 2</a>
                                @if ($skripsi->pengujitiga == !null)
                                    <a formtarget="_blank" target="_blank"
                                        href="/nilai-skripsi-penguji/{{ Crypt::encryptString($skripsi->id) }}/{{ $skripsi->pengujitiga->nip }}"
                                        class="badge bg-success p-2 mt-1" style="border-radius:20px;">Nilai Penguji 3</a>
                                @endif
                                <a formtarget="_blank" target="_blank"
                                    href="/perbaikan-pengujiskripsi/{{ Crypt::encryptString($skripsi->id) }}/{{ $skripsi->pengujisatu->nip }}"
                                    class="badge bg-danger p-2 mt-1" style="border-radius:20px;">Perbaikan Penguji 1</a>
                                <a formtarget="_blank" target="_blank"
                                    href="/perbaikan-pengujiskripsi/{{ Crypt::encryptString($skripsi->id) }}/{{ $skripsi->pengujidua->nip }}"
                                    class="badge bg-warning p-2 mt-1" style="border-radius:20px;">Perbaikan Penguji 2</a>
                                @if ($skripsi->pengujitiga == !null)
                                    <a formtarget="_blank" target="_blank"
                                        href="/perbaikan-pengujiskripsi/{{ Crypt::encryptString($skripsi->id) }}/{{ $skripsi->pengujitiga->nip }}"
                                        class="badge bg-success p-2 mt-1" style="border-radius:20px;">Perbaikan Penguji
                                        3</a>
                                @endif
                                <a formtarget="_blank" target="_blank"
                                    href="/penilaian-skripsi/beritaacara-skripsi/{{ Crypt::encryptString($skripsi->id) }}"
                                    class="badge bg-primary p-2 mt-1" style="border-radius:20px;">Berita Acara</a>
                                @if (Str::length(Auth::guard('web')->user()) > 0)
                                @if (Auth::guard('web')->user()->role_id == 2 ||
                                Auth::guard('web')->user()->role_id == 3 ||
                                Auth::guard('web')->user()->role_id == 4)
                                
                                @if ($skripsi->revisi_skripsi == !null)
                                    <a formtarget="_blank" target="_blank"
                                        href="/penilaian-skripsi/riwayat-judul/{{ $skripsi->id }}"
                                        class="badge bg-warning p-2 mt-1" style="border-radius:20px;">Revisi Judul</a>
                                @endif

                                @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>


        </div>
        </div>


@if (Str::length(Auth::guard('dosen')->user()) > 0)
                @if (Auth::guard('dosen')->user()->role_id == 6 ||
                        Auth::guard('dosen')->user()->role_id == 7 ||
                        Auth::guard('dosen')->user()->role_id == 8 ||
                        Auth::guard('dosen')->user()->role_id == 9 ||
                        Auth::guard('dosen')->user()->role_id == 10 ||
                        Auth::guard('dosen')->user()->role_id == 11)
        <!--<div class="container card p-4 mb-5">-->
            
        <!--    <div class="mb-4 rounded bg-light">-->
        <!--        <div class="p-2 pt-3">-->
        <!--            <h5 class="">Riwayat Persetujuan</h5>-->
        <!--            <hr>-->
        <!--        </div>-->
        <!--    </div>-->

        <!--    <table class="table table-responsive-lg table-bordered table-striped" style="width:100%" id="datatables4">-->
        <!--    <thead class="table-dark">-->
        <!--        <tr>-->
        <!--            <th class="text-center" scope="col">NIM</th>-->
        <!--            <th class="text-center" scope="col">Nama</th>-->
        <!--            <th class="text-center" scope="col">Seminar</th>-->
        <!--            <th class="text-center" scope="col">Prodi</th>-->
        <!--            <th class="text-center" scope="col">Tanggal</th>-->
        <!--            <th class="text-center" scope="col">Waktu</th>-->
        <!--            <th class="text-center" scope="col">Lokasi</th>-->
        <!--            <th class="text-center" scope="col">Pembimbing</th>-->
        <!--            <th class="text-center" scope="col">Penguji</th>-->
        <!--            <th class="text-center" scope="col">Aksi</th>-->
        <!--        </tr>-->
        <!--    </thead>-->
        <!--    <tbody>-->

        <!--        @foreach ($penjadwalan_skripsis as $skripsi)-->
        <!--            <tr>-->
        <!--                <td class="text-center">{{ $skripsi->mahasiswa->nim }}</td>-->
        <!--                <td class="text-left pl-3 pr-1 fw-bold">{{ $skripsi->mahasiswa->nama }}</td>-->
        <!--                <td class="bg-warning text-center">{{ $skripsi->jenis_seminar }}</td>-->
        <!--                <td class="text-center">{{ $skripsi->prodi->nama_prodi }}</td>-->
        <!--                <td class="text-center">{{ Carbon::parse($skripsi->tanggal)->translatedFormat('l, d F Y') }}</td>-->
        <!--                <td class="text-center">{{ $skripsi->waktu }}</td>-->
        <!--                <td class="text-center">{{ $skripsi->lokasi }}</td>-->
        <!--                <td class="text-center">-->
        <!--                    @if ($skripsi->pembimbingdua == !null)-->
        <!--                    <p>1. {{ $skripsi->pembimbingsatu->nama_singkat }}</p>-->
        <!--                    @endif-->
        <!--                    @if ($skripsi->pembimbingdua == !null)-->
        <!--                        <p>2. {{ $skripsi->pembimbingdua->nama_singkat }}</p>-->
        <!--                    @endif-->
        <!--                </td>-->
        <!--                <td class="text-center">-->
        <!--                    <p>1. {{ $skripsi->pengujisatu->nama_singkat }}</p>-->
        <!--                    <p>2. {{ $skripsi->pengujidua->nama_singkat }}</p>-->
        <!--                    @if ($skripsi->pengujitiga == !null)-->
        <!--                        <p>3. {{ $skripsi->pengujitiga->nama_singkat }}</p>-->
        <!--                    @endif-->
        <!--                </td>-->
        <!--                <td class="text-center">-->
        <!--                    <a href="/penilaian-skripsi/cek-nilai/{{ Crypt::encryptString($skripsi->id) }}"-->
        <!--                        class="badge bg-success p-2"style="border-radius:20px;">Berita Acara</a>-->
        <!--                </td>-->
        <!--            </tr>-->
        <!--        @endforeach-->

        <!--    </tbody>-->
        <!--</table>-->


        <!--</div>-->
        @endif
        @endif

        <div class="d-flex align-items-end flex-column fixed-bottom bd-highlight mb-5">
        <div class="mt-auto p-2 bd-highlight ">
            <span onclick="scrollToTop()" id="scrollToTopBtn" title="Kembali ke Atas" style="width: 40px; height: 40px; cursor: pointer;"><i class="fas fa-chevron-circle-up fa-lg fs-2"></i></span>
        </div>
        </div>

<br>
<br>
<br>
<br>
    @endsection

    @section('footer')
        <section class="bg-dark p-1">
            <div class="container">
                <p class="developer">Dikembangkan oleh Prodi Teknik Informatika UNRI <small> <span
                            class="text-success fw-bold">(</span><a class="text-success fw-bold" formtarget="_blank"
                            target="_blank" href="https://fahrilhadi.com">Fahril Hadi, </a>
                        <a class="text-success fw-bold" formtarget="_blank" target="_blank"
                            href="/developer/rahul-ilsa-tajri-mukhti">Rahul Ilsa Tajri Mukhti </a> <span
                            class="text-success fw-bold">&</span>
                        <a class="text-success fw-bold" formtarget="_blank" target="_blank"
                            href="/developer/m-seprinaldi"> M. Seprinaldi</a><span
                            class="text-success fw-bold">)</span></small></p>
            </div>
        </section>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {  
                const Semua = {!! json_encode(
                    $jml_riwayat_prodi_kp + $jml_riwayat_prodi_skripsi + $jml_riwayat_seminar_kp + $jml_riwayat_sempro + $jml_riwayat_skripsi,
                ) !!};
                
                if (Semua > 0) {
                    Swal.fire({
                        title: 'Ini adalah halaman Riwayat',
                        html: `Ada <strong class="text-info"> ${Semua} Mahasiswa</strong> yang telah selesai KP, Skripsi dan Seminar`,
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonColor: '#28a745',
                    });
                } 
                else {
                    Swal.fire({
                        title: 'Ini adalah halaman Riwayat',
                        html: `Belum ada mahasiswa yang selesai KP, Skripsi, dan Seminar.`,
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonColor: '#28a745',
                    });
                }
            });
        </script>
    @endpush()
    
    @push('scripts')
        <script>
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    window.onscroll = function() {
        scrollFunction();
    };

    function scrollFunction() {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            document.getElementById("scrollToTopBtn").style.display = "block";
        } else {
            document.getElementById("scrollToTopBtn").style.display = "none";
        }
    }
    </script>
    @endpush()
