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


    <section class="mb-5">
        <div class="container">
            @if (Str::length(Auth::guard('dosen')->user()) > 0)
                <a href="/pembimbing/kerja-praktek" class="btn btn-success py-1 px-2 mb-3"><i
                        class="fas fa-arrow-left fa-xs"></i> Kembali <a>
            @endif

            @if (Str::length(Auth::guard('web')->user()) > 0)
                <a href="/kerja-praktek/admin/index" class="btn btn-success py-1 px-2 mb-3"><i
                        class="fas fa-arrow-left fa-xs"></i> Kembali <a>
            @endif
        </div>

        <div class="container">
            @foreach ($pendaftaran_kp as $kp)
                <div class="row rounded shadow-sm">
                    <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                        <h5 class="text-bold">Mahasiswa</h5>
                        <hr>
                        <p class="card-title text-secondary text-sm ">Nama</p>
                        <p class="card-text text-start">{{ $kp->mahasiswa->nama }}</p>
                        <p class="card-title text-secondary text-sm ">NIM</p>
                        <p class="card-text text-start">{{ $kp->mahasiswa->nim }}</p>
                        <p class="card-title text-secondary text-sm ">Program Studi</p>
                        <p class="card-text text-start">{{ $kp->mahasiswa->prodi->nama_prodi }}</p>
                        <p class="card-title text-secondary text-sm ">Konsentrasi</p>
                        <p class="card-text text-start">{{ $kp->mahasiswa->konsentrasi->nama_konsentrasi }}</p>
                    </div>
                    <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                        @if ($kp->status_kp == 'USULAN KP')
                            <h5 class="text-bold">Calon Dosen Pembimbing</h5>
                        @else
                            <h5 class="text-bold">Dosen Pembimbing</h5>
                        @endif
                        <hr>
                        <p class="card-title text-secondary text-sm">Nama</p>
                        <p class="card-text text-start">{{ $kp->dosen_pembimbingkp->nama }}</p>
                    </div>
                </div>
        </div>

        @if ($kp->status_kp == 'KP DISETUJUI')
         <div class="container">
            <div class="row rounded shadow-sm">
                <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                    <h5 class="text-bold">Laporan KP</h5>
                    <hr>
                <p class="card-title text-secondary text-sm">Judul Laporan</p>
                    <p class="card-text text-start"><span>{{ $kp->judul_laporan ?? '-' }}</span></p>
                
                    <p class="card-title text-secondary text-sm">Nama Perusahaan/Instansi</p>
                    <p class="card-text text-start"><span>
                        @if($kp->nama_perusahaan != null)
                        {{ $kp->nama_perusahaan}}
                        @else
                        -
                        @endif
                    </span></p>

                </div>
                <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                    <h5 class="text-bold">Surat Persetujuan Permohonan KP</h5>
                    <hr>
                
                    <p class="card-title text-secondary text-sm">KPTI/TE-1 - Surat Permohonan KP</p>
                        <p class="card-text  text-start"><button onclick="window.open('/surat-permohonan-kp/{{ $kp->id }}', '_blank')" class="badge bg-dark px-2 py-1">Buka</button>
                        </p>
                        <p class="card-title text-secondary text-sm">KPTI/TE-2 - Form Permohonan KP</p>
                        <p class="card-text text-start">
                            <button onclick="window.open('/form-permohonan-kp/{{ $kp->id }}', '_blank')" class="badge bg-dark px-2 py-1">Buka</button>
                            </p>
                </div>
            </div>
        </div>
         @endif

        <div class="container">
            <div class="row rounded shadow-sm">
                <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                    <h5 class="text-bold">Data Usulan</h5>
                    <hr>
                    <p class="card-title text-secondary text-sm">Surat Balasan Perusahaan</p>
                    <p class="card-text  text-start"><a formtarget="_blank" target="_blank"
                            href="{{ asset('storage/' . $kp->surat_balasan) }}" class="badge bg-dark px-3 py-2">Buka</a></p>

                    <p class="card-title text-secondary text-sm">Tanggal Mulai KP</p>
                    <p class="card-text text-start"> {{ Carbon::parse($kp->tanggal_mulai)->translatedFormat('l, d F Y') }}
                    </p>


                </div>
                <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                    <h5 class="text-bold">Keterangan Pendaftaran</h5>
                    <hr>
                    <p class="card-title text-secondary text-sm">Jenis Usulan</p>
                    <p class="card-text text-start"><span>{{ $kp->jenis_usulan }}</span></p>
                    @if ($kp->status_kp == 'SURAT PERUSAHAAN')
                        <p class="card-title text-secondary text-sm">Status KP</p>
                        <p class="card-text text-start"><span class="badge p-2 bg-secondary text-bold pr-3 pl-3"
                                style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                    @endif
                    @if ($kp->status_kp == 'SURAT PERUSAHAAN DITOLAK')
                        <p class="card-title text-secondary text-sm">Status KP</p>
                        <p class="card-text text-start"><span class="badge p-2 bg-danger text-bold pr-3 pl-3"
                                style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                    @endif
                    @if ($kp->status_kp == 'KP DISETUJUI')
                        <p class="card-title text-secondary text-sm ">Status KP</p>
                        <p class="card-text text-start"><span class="badge p-2 bg-info text-bold pr-3 pl-3"
                                style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                    @endif
                    <p class="card-title text-secondary text-sm">Keterangan</p>
                    <p class="card-text text-start"><span>{{ $kp->keterangan }}</span></p>
                </div>
            </div>
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
