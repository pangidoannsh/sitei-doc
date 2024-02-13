@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Detail Mahasiswa
@endsection

@section('sub-title')
    Detail Mahasiswa
@endsection

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif


    @foreach ($pendaftaran_skripsi as $skripsi)
        <section class="mb-5">
            <div class="container">
                @if (Str::length(Auth::guard('dosen')->user()) > 0)
                    <a href="/pembimbing/skripsi" class="btn btn-success py-1 px-2 mb-3"><i
                            class="fas fa-arrow-left fa-xs"></i> Kembali <a>
                @endif
            </div>

            <div class="container">
                <div class="row rounded shadow-sm">
                    <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                        <h5 class="text-bold">Mahasiswa</h5>
                        <hr>
                        <p class="card-title text-secondary text-sm ">Nama</p>
                        <p class="card-text text-start">{{ $skripsi->mahasiswa->nama }}</p>
                        <p class="card-title text-secondary text-sm ">NIM</p>
                        <p class="card-text text-start">{{ $skripsi->mahasiswa->nim }}</p>
                        <p class="card-title text-secondary text-sm ">Program Studi</p>
                        <p class="card-text text-start">{{ $skripsi->mahasiswa->prodi->nama_prodi }}</p>
                        <p class="card-title text-secondary text-sm ">Konsentrasi</p>
                        <p class="card-text text-start">{{ $skripsi->mahasiswa->konsentrasi->nama_konsentrasi }}</p>
                    </div>
                    <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                        <h5 class="text-bold">Dosen Pembimbing</h5>
                        <hr>
                        @if ($skripsi->pembimbing_2_nip == null)
                            <p class="card-title text-secondary text-sm">Nama</p>
                            <p class="card-text text-start">{{ $skripsi->dosen_pembimbing1->nama }}</p>
                        @elseif($skripsi->pembimbing_2_nip !== null)
                            <p class="card-title text-secondary text-sm">Nama Pembimbing 1</p>
                            <p class="card-text text-start">{{ $skripsi->dosen_pembimbing1->nama }}</p>
                            <p class="card-title text-secondary text-sm">Nama Pembimbing 2</p>
                            <p class="card-text text-start">{{ $skripsi->dosen_pembimbing2->nama }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="container">
            <div class="row rounded shadow-sm">
                <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                    <h5 class="text-bold">Laporan Skripsi</h5>
                    <hr>
                <p class="card-title text-secondary text-sm">Judul Skripsi</p>
                    <p class="card-text text-start"><span>{{ $skripsi->judul_skripsi ?? '-' }}</span></p>
                </div>
                <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                    <h5 class="text-bold">Persetujuan Pengajuan Skripsi</h5>
                    <hr>
                    <p class="card-title text-secondary text-sm ">STI/TE-1 - Surat Permohonan Pengajuan Topik Skripsi</p>
                        <p class="card-text  text-start"><button onclick="window.open('/surat-permohonan-pengajuan-topik-skripsi/{{ $skripsi->id }}', '_blank')" class="badge bg-dark px-2 py-1">Buka</button>
                        </p>
                        <p class="card-title text-secondary text-sm ">STI/TE-2 - Form Pengajuan Topik Skripsi</p>
                        <p class="card-text text-start">
                            <button onclick="window.open('/form-pengajuan-topik-skripsi/{{ $skripsi->id }}', '_blank')" class="badge bg-dark px-2 py-1">Buka</button>
                        </p>
                </div>
            </div>
        </div>

            @if ($skripsi->status_skripsi == 'SKRIPSI SELESAI' || $skripsi->status_skripsi == 'LULUS')
                <div class="container">
                    <div class="row rounded shadow-sm">
                        <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                            <h5 class="text-bold">Data Usulan</h5>
                            <hr>
                            <p class="card-title text-secondary text-sm ">Buku Skripsi</p>
                            <p class="card-text text-start"><span><a formtarget="_blank" target="_blank"
                                        href="{{ asset('storage/' . $skripsi->naskah) }}"
                                        class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p>
                            <p class="card-title text-secondary text-sm ">STI/TE-17/Bukti Penyerahan Buku Skripsi</p>
                            <p class="card-text text-start"><span><a formtarget="_blank" target="_blank"
                                        href="{{ asset('storage/' . $skripsi->sti_17) }}"
                                        class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p>
                            <!-- <p class="card-title text-secondary text-sm " >STI/TE-29/ Bukti Sudah Daftar Wisuda di Fakultas</p>
            <p class="card-text text-start" ><span><a formtarget="_blank" target="_blank" href="{{ asset('storage/' . $skripsi->sti_29) }}" class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p> -->
                        </div>
                        <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                            <h5 class="text-bold">Nilai Skripsi</h5>
                            <hr>

                            <p class="card-title  text-secondary text-sm">Nilai Angka</p>
                            <p class="card-text text-start"> <span class=" fs-5 fw-bold">
                                    @if (
                                        $nilaipenguji1 == '' &&
                                            $nilaipenguji2 == '' &&
                                            $nilaipenguji3 == '' &&
                                            $nilaipembimbing1 == '' &&
                                            $nilaipembimbing2 == '')
                                        -
                                    @else
                                        <?php
                                        $nilai_masuk = 0;
                                        if (!empty($nilaipenguji1)) {
                                            $nilai_masuk = $nilai_masuk + 1;
                                            $penguji1 = $nilaipenguji1->total_nilai_angka;
                                        } else {
                                            $penguji1 = 0;
                                        }
                                        if (!empty($nilaipenguji2)) {
                                            $nilai_masuk = $nilai_masuk + 1;
                                            $penguji2 = $nilaipenguji2->total_nilai_angka;
                                        } else {
                                            $penguji2 = 0;
                                        }
                                        if (!empty($nilaipenguji3)) {
                                            $nilai_masuk = $nilai_masuk + 1;
                                            $penguji3 = $nilaipenguji3->total_nilai_angka;
                                        } else {
                                            $penguji3 = 0;
                                        }
                                        $nilaitotalpenguji = round(($penguji1 + $penguji2 + $penguji3) / $nilai_masuk);
                                        $nilai_masuk = 0;
                                        
                                        if (!empty($nilaipembimbing1)) {
                                            $nilai_masuk = $nilai_masuk + 1;
                                            $pembimbing1 = $nilaipembimbing1->total_nilai_angka;
                                        } else {
                                            $pembimbing1 = 0;
                                        }
                                        if (!empty($nilaipembimbing2)) {
                                            $nilai_masuk = $nilai_masuk + 1;
                                            $pembimbing2 = $nilaipembimbing2->total_nilai_angka;
                                        } else {
                                            $pembimbing2 = 0;
                                        }
                                        if ($nilai_masuk == 0) {
                                            $nilai_masuk = 1;
                                        }
                                        $nilaitotalpembimbing = round(($pembimbing1 + $pembimbing2) / $nilai_masuk);
                                        $nilai_masuk_akhir = 0;
                                        if ($nilaitotalpenguji != 0) {
                                            $nilai_masuk_akhir = $nilai_masuk_akhir + 1;
                                            $penguji = $nilaitotalpenguji;
                                        } else {
                                            $penguji = 0;
                                        }
                                        if ($nilaitotalpembimbing != 0) {
                                            $nilai_masuk_akhir = $nilai_masuk_akhir + 1;
                                            $pembimbing = $nilaitotalpembimbing;
                                        } else {
                                            $pembimbing = 0;
                                        }
                                        $total_nilai = $penguji + $pembimbing;
                                        ?>
                                        {{ $total_nilai }}
                                    @endif
                                </span>
                            </p>

                            <p class="card-title  text-secondary text-sm">Nilai Huruf</p>
                            <p class="card-text text-start"><span class=" fs-5 fw-bold">
                                    @if ($nilaitotalpenguji == '' && $nilaitotalpembimbing == '')
                                        -
                                    @else
                                        @if ($total_nilai >= 85)
                                            A
                                        @elseif ($total_nilai >= 80)
                                            A-
                                        @elseif ($total_nilai >= 75)
                                            B+
                                        @elseif ($total_nilai >= 70)
                                            B
                                        @elseif ($total_nilai >= 65)
                                            B-
                                        @elseif ($total_nilai >= 60)
                                            C+
                                        @elseif ($total_nilai >= 55)
                                            C
                                        @elseif ($total_nilai >= 40)
                                            D
                                        @else
                                            E
                                        @endif
                                    @endif
                                </span>
                            </p>

                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row shadow-sm rounded">
                        <div class="col bg-white px-4 py-3 mb-2 rounded">
                            <h5 class="text-bold">Keterangan Pendaftaran</h5>
                            <hr>
                            <p class="card-title text-secondary text-sm">Jenis Usulan</p>
                            <p class="card-text text-start"><span>{{ $skripsi->jenis_usulan }}</span></p>
                            @if ($skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK')
                                <p class="card-title text-secondary text-sm">Status Skripsi</p>
                                <p class="card-text text-start"><span class="badge p-2 bg-danger text-bold pr-3 pl-3"
                                        style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                            @endif
                            @if ($skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI')
                                <p class="card-title text-secondary text-sm">Status Skripsi</p>
                                <p class="card-text text-start"><span class="badge p-2 bg-secondary text-bold pr-3 pl-3"
                                        style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                            @endif
                            @if ($skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DISETUJUI')
                                <p class="card-title text-secondary text-sm ">Status KP</p>
                                <p class="card-text text-start"><span class="badge p-2 bg-info text-bold pr-3 pl-3"
                                        style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                            @endif
                            @if ($skripsi->status_skripsi == 'SKRIPSI SELESAI' || $skripsi->status_skripsi == 'LULUS')
                                <p class="card-title text-secondary text-sm ">Status KP</p>
                                <p class="card-text text-start"><span class="badge p-2 bg-info text-bold pr-3 pl-3"
                                        style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                            @endif
                            <p class="card-title text-secondary text-sm">Keterangan</p>
                            <p class="card-text text-start"><span>{{ $skripsi->keterangan }}</span></p>
                        </div>
                    </div>
                </div>
            @else
                <div class="container">
                    <div class="row rounded shadow-sm">
                        <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                            <h5 class="text-bold">Data Usulan</h5>
                            <hr>
                            <p class="card-title text-secondary text-sm ">Buku Skripsi</p>
                            <p class="card-text text-start"><span><a formtarget="_blank" target="_blank"
                                        href="{{ asset('storage/' . $skripsi->naskah) }}"
                                        class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p>
                            <p class="card-title text-secondary text-sm ">STI/TE-17/Bukti Penyerahan Buku Skripsi</p>
                            <p class="card-text text-start"><span><a formtarget="_blank" target="_blank"
                                        href="{{ asset('storage/' . $skripsi->sti_17) }}"
                                        class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p>
                            <!-- <p class="card-title text-secondary text-sm " >STI/TE-29/ Bukti Sudah Daftar Wisuda di Fakultas</p>
            <p class="card-text text-start" ><span><a formtarget="_blank" target="_blank" href="{{ asset('storage/' . $skripsi->sti_29) }}" class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p> -->
                        </div>
                        <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                            <h5 class="text-bold">Keterangan Pendaftaran</h5>
                            <hr>
                            <p class="card-title text-secondary text-sm">Jenis Usulan</p>
                            <p class="card-text text-start"><span>{{ $skripsi->jenis_usulan }}</span></p>
                            @if ($skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK')
                                <p class="card-title text-secondary text-sm">Status Skripsi</p>
                                <p class="card-text text-start"><span class="badge p-2 bg-danger text-bold pr-3 pl-3"
                                        style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                            @endif
                            @if ($skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI')
                                <p class="card-title text-secondary text-sm">Status Skripsi</p>
                                <p class="card-text text-start"><span class="badge p-2 bg-secondary text-bold pr-3 pl-3"
                                        style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                            @endif
                            @if ($skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DISETUJUI')
                                <p class="card-title text-secondary text-sm ">Status KP</p>
                                <p class="card-text text-start"><span class="badge p-2 bg-info text-bold pr-3 pl-3"
                                        style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                            @endif
                            @if ($skripsi->status_skripsi == 'SKRIPSI SELESAI' || $skripsi->status_skripsi == 'LULUS')
                                <p class="card-title text-secondary text-sm ">Status KP</p>
                                <p class="card-text text-start"><span class="badge p-2 bg-info text-bold pr-3 pl-3"
                                        style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                            @endif
                            <p class="card-title text-secondary text-sm">Keterangan</p>
                            <p class="card-text text-start"><span>{{ $skripsi->keterangan }}</span></p>

                        </div>
                    </div>
                </div>
            @endif
            </div>
    @endforeach
    </section>
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
