@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Daftar Beban Bimbingan Skripsi
@endsection

@section('sub-title')
    Daftar Beban Bimbingan Skripsi
@endsection

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="container">
                    <a href="/statistik/bimbingan-skripsi" class="btn btn-success py-1 px-2 mb-3"><i
                            class="fas fa-arrow-left fa-xs"></i> Kembali <a>
            </div>

    <div class="container card p-4">

        <ol class="breadcrumb col-lg-12">

            <h5 class="">Data Bimbingan <span class="fw-bold fs-5">{{ $dosen->nama }} </span></h5>

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

                    @foreach ($pendaftaran_skripsi as $skripsi)
                        <div></div>
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $skripsi->mahasiswa->nim }}</td>
                            <td class="text-center">{{ $skripsi->mahasiswa->nama }}</td>
                            <td class="text-center ">{{ $skripsi->mahasiswa->prodi->nama_prodi }}</td>
                            <td class="text-center">{{ $skripsi->mahasiswa->konsentrasi->nama_konsentrasi }}</td>
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
                                $skripsi->status_skripsi == 'JUDUL DISETUJUI' ||
                                    $skripsi->status_skripsi == 'SEMPRO SELESAI' ||
                                    $skripsi->status_skripsi == 'SIDANG SELESAI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI' ||
                                    $skripsi->status_skripsi == 'SKRIPSI SELESAI' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO DISETUJUI' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI')
                                <td class="text-center px-1 py-2 bg-info">{{ $skripsi->status_skripsi }}</td>
                            @endif
                            @if ($skripsi->status_skripsi == 'SEMPRO DIJADWALKAN' || $skripsi->status_skripsi == 'SIDANG DIJADWALKAN')
                                <td class="text-center px-1 py-2 bg-success">{{ $skripsi->status_skripsi }}</td>
                            @endif
                            @if (
                                $skripsi->status_skripsi == 'USULAN JUDUL DITOLAK' ||
                                    $skripsi->status_skripsi == 'USULKAN JUDUL ULANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO DITOLAK' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG ULANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK' ||
                                    $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK')
                                <td class="text-center px-1 py-2 bg-danger">{{ $skripsi->status_skripsi }}</td>
                            @endif
                            <!-- DURASI -->

                                @php
                                $tanggalMulaiSkripsi = Carbon::parse($skripsi->tgl_disetujui_usuljudul_kaprodi);
                                $tanggalSelesai= Carbon::now();

                                $durasiSkripsi = $tanggalMulaiSkripsi ? $tanggalMulaiSkripsi->diffInMonths($tanggalSelesai) : null;
                                $bulan = $durasiSkripsi ? floor($durasiSkripsi) : null;
                                $hari = $tanggalMulaiSkripsi ? $tanggalMulaiSkripsi->addMonths($bulan)->diffInDays($tanggalSelesai) : null;
                                        @endphp

                            <td class="text-center px-1 py-2">
                                       <b> {{ $bulan ?? 0}} </b> <small>Bulan</small> <b> {{ $hari }} </b> <small>Hari</small>
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
