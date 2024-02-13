@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Daftar Beban Bimbingan Kerja Praktek
@endsection

@section('sub-title')
    Daftar Beban Bimbingan Kerja Praktek
@endsection

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="container">
                    <a href="/statistik/bimbingan-kp" class="btn btn-success py-1 px-2 mb-3"><i
                            class="fas fa-arrow-left fa-xs"></i> Kembali <a>
            </div>

    <div class="container card p-4">

        <ol class="breadcrumb col-lg-12">

            <h5 class="pt-2">Data Bimbingan <span class="fw-bold fs-5">{{ $dosen->nama }} </span></h5>

        </ol>

        <div class="container-fluid">

            <table class="table table-responsive-lg table-bordered table-striped" width="100%" id="datatables">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center px-0" scope="col">No.</th>
                        <th class="text-center" scope="col">NIM</th>
                        <th class="text-center" scope="col">Nama</th>
                        <th class="text-center" scope="col">Program Studi</th>
                        <th class="text-center" scope="col">Konsentrasi</th>
                        <th class="text-center" scope="col">Status</th>
                        <th class="text-center" scope="col">Durasi</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($pendaftaran_kp as $kp)
                        <div></div>
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $kp->mahasiswa->nim }}</td>
                            <td class="text-center">{{ $kp->mahasiswa->nama }}</td>

                            <td class="text-center ">{{ $kp->mahasiswa->prodi->nama_prodi }}</td>

                            <td class="text-center">{{ $kp->mahasiswa->konsentrasi->nama_konsentrasi }}</td>
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
                                    $kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI' ||
                                    $kp->status_kp == 'SEMINAR KP SELESAI' ||
                                    $kp->status_kp == 'KP SELESAI')
                                <td class="text-center px-1 py-2 bg-info">{{ $kp->status_kp }}</td>
                            @endif
                            @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                                <td class="text-center px-1 py-2 bg-success">{{ $kp->status_kp }}</td>
                            @endif
                            @if (
                                $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP ULANG' ||
                                    $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK')
                                <td class="text-center px-1 py-2 bg-danger">{{ $kp->status_kp }}</td>
                            @endif

                            <!-- DURASI -->
                            @php
                                $tanggalMulaiKP = Carbon::parse($kp->tgl_disetujui_balasan);

                                $tanggalSelesai = Carbon::now();

                                $durasiKP = $tanggalMulaiKP ? $tanggalMulaiKP->diffInMonths($tanggalSelesai) : null;
                                $bulan = $durasiKP ? floor($durasiKP) : null;
                                $hari = $tanggalMulaiKP ? $tanggalMulaiKP->addMonths($bulan)->diffInDays($tanggalSelesai) : null;
                            @endphp

                            <td class="text-center px-1 py-2">
                                         {{ $bulan ?? 0}} <small>Bulan</small> {{ $hari }} <small>Hari</small>
                                </td>
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
