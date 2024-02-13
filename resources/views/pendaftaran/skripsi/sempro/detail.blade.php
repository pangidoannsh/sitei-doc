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


    <div class="container">
        @if (Str::length(Auth::guard('dosen')->user()) > 0)
            <a href="/skripsi" class="btn btn-success py-1 px-2 mb-3 "><i class="fas fa-arrow-left fa-xs"></i> Kembali <a>
        @endif

        @if (Str::length(Auth::guard('web')->user()) > 0)
            <a href="/sempro/admin/index" class="btn btn-success py-1 px-2 mb-3 "><i class="fas fa-arrow-left fa-xs"></i>
                Kembali <a>
        @endif

        @if (Str::length(Auth::guard('mahasiswa')->user()) > 0)
            <a href="/usuljudul/index" class="btn btn-success py-1 px-2 mb-3 "><i class="fas fa-arrow-left fa-xs"></i>
                Kembali <a>
        @endif
    </div>

    @foreach ($pendaftaran_skripsi as $skripsi)
        <div class="container">
            <div class="row rounded shadow-sm">
                <div class="col-lg-6 col-md-12 bg-white px-4 py-3 mb-2 rounded-start">
                    <h5 class="text-bold">Mahasiswa</h5>
                    <hr>
                    <p class="card-title text-secondary text-sm ">Nama</p>
                    <p class="card-text  text-start">{{ $skripsi->mahasiswa->nama }}</p>
                    <p class="card-title text-secondary text-sm ">NIM</p>
                    <p class="card-text  text-start">{{ $skripsi->mahasiswa->nim }}</p>
                    <p class="card-title text-secondary text-sm ">Program Studi</p>
                    <p class="card-text  text-start">{{ $skripsi->mahasiswa->prodi->nama_prodi }}</p>
                    <p class="card-title text-secondary text-sm ">Konsentrasi</p>
                    <p class="card-text  text-start">{{ $skripsi->mahasiswa->konsentrasi->nama_konsentrasi }}</p>
                </div>
                <div class="col-lg-6 col-md-12 bg-white px-4 py-3 mb-2 rounded-end">
                    <h5 class="text-bold">Dosen Pembimbing</h5>
                    <hr>
                    @if ($skripsi->pembimbing_2_nip == null)
                        <p class="card-title text-secondary text-sm">Nama</p>
                        <p class="card-text  text-start">{{ $skripsi->dosen_pembimbing1->nama }}</p>
                        <!-- <p class="card-title text-secondary text-sm" >NIP</p>
            <p class="card-text  text-start" >{{ $skripsi->dosen_pembimbing1->nip }}</p> -->
                    @elseif($skripsi->pembimbing_2_nip !== null)
                        <p class="card-title text-secondary text-sm">Nama Pembimbing 1</p>
                        <p class="card-text  text-start">{{ $skripsi->dosen_pembimbing1->nama }}</p>
                        <!-- <p class="card-title text-secondary text-sm" >NIP</p>
            <p class="card-text  text-start" >{{ $skripsi->dosen_pembimbing1->nip }}</p> -->
                        <p class="card-title text-secondary text-sm">Nama Pembimbing 2</p>
                        <p class="card-text  text-start">{{ $skripsi->dosen_pembimbing2->nama }}</p>
                        <!-- <p class="card-title text-secondary text-sm" >NIP</p>
            <p class="card-text  text-start" >{{ $skripsi->dosen_pembimbing2->nip }}</p> -->
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



        <div class="container">
            <div class="row rounded shadow-sm ">
                <div class="col-lg-6 col-md-12 bg-white mb-2 px-4 py-3 rounded-start">
                    <h5 class="text-bold">Data Usulan</h5>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="card-title text-secondary text-sm ">KRS Semester Berjalan</p>
                            <p class="card-text  text-start"><span><a formtarget="_blank" target="_blank"
                                        href="{{ asset('storage/' . $skripsi->krs_berjalan) }}"
                                        class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p>
                            <p class="card-title text-secondary text-sm ">Kartu Hasil Studi</p>
                            <p class="card-text  text-start"><span><a formtarget="_blank" target="_blank"
                                        href="{{ asset('storage/' . $skripsi->khs) }}"
                                        class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p>
                            <p class="card-title text-secondary text-sm ">Log Book</p>
                            <p class="card-text  text-start"><span><a formtarget="_blank" target="_blank"
                                        href="{{ asset('storage/' . $skripsi->logbook) }}"
                                        class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p>
                        </div>
                        <div class="col-6">
                            <p class="card-title text-secondary text-sm ">Proposal</p>
                            <p class="card-text  text-start"><span><a formtarget="_blank" target="_blank"
                                        href="{{ asset('storage/' . $skripsi->naskah) }}"
                                        class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p>
                            <p class="card-title text-secondary text-sm ">STI/TE-30</p>
                            <p class="card-text  text-start"><span><a formtarget="_blank" target="_blank"
                                        href="{{ asset('storage/' . $skripsi->sti_30) }}"
                                        class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p>
                            <p class="card-title text-secondary text-sm ">STI/TE-31</p>
                            @if($skripsi->sti_31 != null)
                            <p class="card-text  text-start"><span><a formtarget="_blank" target="_blank"
                                        href="{{ asset('storage/' . $skripsi->sti_31) }}"
                                        class="badge bg-dark pr-3 p-2 pl-3">Buka</a></span></p>
                            @else
                            <p class="card-text  text-start"><span>-</span></p>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="col-lg-6 col-md-12 bg-white mb-2 px-4 py-3  rounded-end">
                    <h5 class="text-bold">Keterangan Pendaftaran</h5>
                    <hr>
                    <p class="card-title text-secondary text-sm">Jenis Usulan</p>
                    <p class="card-text  text-start"><span>{{ $skripsi->jenis_usulan }}</span></p>
                    @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO')
                        <p class="card-title text-secondary text-sm">Status Skripsi</p>
                        <p class="card-text  text-start"><span class="badge p-2 bg-secondary text-bold pr-3 pl-3"
                                style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                    @endif
                    @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO DITOLAK' || $skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG')
                        <p class="card-title text-secondary text-sm">Status Skripsi</p>
                        <p class="card-text  text-start"><span class="badge p-2 bg-danger text-bold pr-3 pl-3"
                                style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                    @endif
                    @if ($skripsi->status_skripsi == 'SEMPRO DIJADWALKAN')
                        <p class="card-title text-secondary text-sm ">Status Skripsi</p>
                        <p class="card-text  text-start"><span class="badge p-2 bg-success text-bold pr-3 pl-3"
                                style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                    @endif
                    @if ($skripsi->status_skripsi == 'SEMPRO SELESAI' || $skripsi->status_skripsi == 'DAFTAR SEMPRO DISETUJUI')
                        <p class="card-title text-secondary text-sm ">Status Skripsi</p>
                        <p class="card-text  text-start"><span class="badge p-2 bg-info text-bold pr-3 pl-3"
                                style="border-radius:20px;">{{ $skripsi->status_skripsi }}</span></p>
                    @endif
                    <p class="card-title text-secondary text-sm">Keterangan</p>
                    <p class="card-text  text-start"><span>{{ $skripsi->keterangan }}</span></p>
                </div>
            </div>
        </div>
    @endforeach



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
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 2000);
    </script>
@endpush()
