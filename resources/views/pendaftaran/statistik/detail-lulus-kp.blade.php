@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Daftar Selesai Bimbingan Kerja Praktek
@endsection

@section('sub-title')
    Daftar Selesai Bimbingan Kerja Praktek
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

            <h5 class="pt-2">Data Lulus Bimbingan <span class="fw-bold fs-5">{{ $dosen->nama }} </span></h5>

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
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $kp->mahasiswa->nim }}</td>
                            <td class="text-center">{{ $kp->mahasiswa->nama }}</td>

                            <td class="text-center ">{{ $kp->mahasiswa->prodi->nama_prodi }}</td>

                            <td class="text-center">{{ $kp->mahasiswa->konsentrasi->nama_konsentrasi }}</td>
                            <td class="text-center px-1 py-2 bg-info">{{ $kp->status_kp }}</td>

                            <!-- DURASI -->
                            @php
                                $tanggalMulaiKP = Carbon::parse($kp->tgl_disetujui_balasan);

                                $tanggalSelesai = Carbon::parse($kp->tgl_disetujui_kpti_10_koordinator);

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
