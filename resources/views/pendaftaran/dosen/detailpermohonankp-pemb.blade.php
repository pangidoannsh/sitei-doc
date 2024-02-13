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


    <div class="container-fluid">

        <div>

            <a href="/pendaftaran/pembimbing/kerja-praktek" class="badge bg-success p-2 mb-3 fa fa-arrow-left"> Kembali <a>

                    @foreach ($pendaftaran_kp as $kp)
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-bold">Mahasiswa</h5>
                                        <hr>
                                        <p class="card-title text-secondary text-sm ">Nama</p>
                                        <p class="card-text text-start">{{ $kp->mahasiswa->nama }}</p>
                                        <p class="card-title text-secondary text-sm ">NIM</p>
                                        <p class="card-text text-start">{{ $kp->mahasiswa->nim }}</p>
                                        <p class="card-title text-secondary text-sm ">Program Studi</p>
                                        <p class="card-text text-start">{{ $kp->mahasiswa->prodi->nama_prodi }}</p>
                                        <p class="card-title text-secondary text-sm ">Konsentrasi</p>
                                        <p class="card-text text-start">{{ $kp->mahasiswa->konsentrasi->nama_konsentrasi }}
                                        </p>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-bold">Dosen Pembimbing</h5>
                                        <hr>
                                        <p class="card-title text-secondary text-sm">Nama</p>
                                        <p class="card-text text-start">{{ $kp->dosen_pembimbingkp->nama }}</p>
                                        <p class="card-title text-secondary text-sm">NIP</p>
                                        <p class="card-text text-start">{{ $kp->dosen_pembimbingkp->nip }}</p>

                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-bold">Data Usulan</h5>
                                <hr>
                                <p class="card-title text-secondary text-sm">Nama Perusahaan</p>
                                <p class="card-text text-start"><span>{{ $kp->nama_perusahaan }}</span></p>

                                <p class="card-title text-secondary text-sm">Alamat Perusahaan</p>
                                <p class="card-text text-start"> {{ $kp->alamat_perusahaan }}</p>

                                <p class="card-title text-secondary text-sm">Bidang Usaha/Kegiatan</p>
                                <p class="card-text text-start"><span>{{ $kp->bidang_usaha }}</span></p>

                                <p class="card-title text-secondary text-sm">Rencana Mulai KP</p>
                                <p class="card-text text-start">
                                    {{ Carbon::parse($kp->tanggal_rencana)->translatedFormat('l, d F Y') }}</p>

                            </div>
                        </div>

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


                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-bold">Keterangan Pendaftaran</h5>
                                <hr>
                                <p class="card-title text-secondary text-sm">Jenis Usulan</p>
                                <p class="card-text text-start"><span>{{ $kp->jenis_usulan }}</span></p>
                                @if ($kp->status_kp == 'USUL PERMOHONAN KP')
                                    <p class="card-title text-secondary text-sm">Status KP</p>
                                    <p class="card-text text-start"><span class="badge p-2 bg-secondary text-bold pr-3 pl-3"
                                            style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                                @endif
                                @if ($kp->status_kp == 'PERMOHONAN KP DISETUJUI PEMBIMBING')
                                    <p class="card-title text-secondary text-sm ">Status KP</p>
                                    <p class="card-text text-start"><span class="badge p-2 bg-secondary text-bold pr-3 pl-3"
                                            style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                                @endif
                                @if ($kp->status_kp == 'PERMOHONAN KP DISETUJUI KOORDINATOR KP')
                                    <p class="card-title text-secondary text-sm">Status KP</p>
                                    <p class="card-text text-start"><span class="badge p-2 bg-secondary text-bold pr-3 pl-3"
                                            style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                                @endif
                                @if ($kp->status_kp == 'PERMOHONAN KP DISETUJUI')
                                    <p class="card-title text-secondary text-sm ">Status KP</p>
                                    <p class="card-text text-start"><span class="badge p-2 bg-info text-bold pr-3 pl-3"
                                            style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                                @endif
                                <p class="card-title text-secondary text-sm">Keterangan</p>
                                <p class="card-text text-start"><span>{{ $kp->keterangan }}</span></p>

                            </div>
                        </div>

                        @if ($kp->status_kp == 'USUL PERMOHONAN KP')
                            <div class="mb-5 mt-4">
                                <div class="mb-5 float-right">
                                    <!-- <h1 class="text-success">
                GeeksforGeeks
            </h1>
            <h2>Bootstrap 5 Modal Vertically centered</h2> -->
                                    <button type="button" class="btn btn-danger badge p-2 fas fa-times" data-toggle="modal"
                                        data-target="#GFG2">
                                        Tolak
                                    </button>
                                    <button type="button" class="btn btn-success badge p-2  fas fa-check"
                                        data-toggle="modal" data-target="#GFG">
                                        Setujui
                                    </button>

                                    <div class="modal fade" id="GFG2">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content ">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title  fas fa-times">
                                                        Tolak
                                                    </h5>

                                                </div>
                                                <div class="modal-body ">
                                                    Apakah Anda yakin?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn " style="border-radius:5px;"
                                                        data-dismiss="modal">
                                                        Batal
                                                    </button>
                                                    <form action="/permohonankp/pembimbing/tolak/{{ $kp->id }}"
                                                        method="POST">
                                                        @method('put')
                                                        @csrf
                                                        <button type="submit" class="btn " style="border-radius:5px;">
                                                            Ya</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="GFG">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success ">
                                                    <h5 class="modal-title fas fa-check">
                                                        Setujui
                                                    </h5>

                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn " style="border-radius:5px;"
                                                        data-dismiss="modal">
                                                        Batal
                                                    </button>
                                                    <form action="/permohonankp/pembimbing/approve/{{ $kp->id }}"
                                                        method="POST">
                                                        @method('put')
                                                        @csrf
                                                        <button type="submit" class="btn " style="border-radius:5px;">
                                                            Ya</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif



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
