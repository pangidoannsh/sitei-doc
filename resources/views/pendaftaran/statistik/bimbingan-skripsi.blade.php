@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Statistik Bimbingan Skripsi
@endsection

@section('sub-title')
    Statistik Bimbingan Skripsi
@endsection

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="container card p-4">

        <ol class="breadcrumb col-lg-12">

            <li>
                <a href="/statistik" class="px-1">Statistik</a>
            </li>

            <span class="px-2">|</span>
            <li><a href="/statistik/bimbingan-kp" class="px-1">Bimbingan KP</a></li>
            <span class="px-2">|</span>
            <li><a href="/statistik/bimbingan-skripsi" class="breadcrumb-item active fw-bold text-success px-1">Bimbingan Skripsi</a></li>

        </ol>

        <div class="container-fluid">

        <div class="mb-4 rounded bg-light">
                <div class="p-2 pt-3">
                    <h5 class="">Daftar Beban Bimbingan Skripsi</h5>
                    <hr>
                </div>
            </div>

        <table class="table table-responsive-lg table-bordered table-striped" width="100%" id="datatables3">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" scope="col">No.</th>
                        <th class="text-center" scope="col">Kode Dosen</th>
                        <th class="text-center" scope="col">Nama Dosen</th>

                        <th class="text-center" scope="col">Pembimbing 1</th>
                        <th class="text-center" scope="col">Pembimbing 2</th>
                        <th class="text-center" scope="col">Total Bimbingan</th>
                        <th class="text-center" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($dosen as $dosen)
                        <div></div>
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $dosen->nama_singkat }}</td>
                            <td>{{ $dosen->nama }}</td>

                            <td class="text-center">{{ $dosen->pendaftaran_skripsi1_count }}</td>
                            <td class="text-center">{{ $dosen->pendaftaran_skripsi2_count }}</td>

                            <td class="text-center @if ($dosen->pendaftaran_skripsi1_count + $dosen->pendaftaran_skripsi2_count >= $kapasitas->kapasitas_skripsi) bg-danger @endif bg-info">
                                <b>{{ $dosen->pendaftaran_skripsi1_count + $dosen->pendaftaran_skripsi2_count }}</b>
                            </td>
                            <td class="text-center">
                                <a href="/detail/kuota-bimbingan/skripsi/{{ $dosen->nip }}"
                                    class="badge btn btn-info p-1 mb-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                        class="fas fa-info-circle"></i></a>
                            </td>
                    @endforeach
                </tbody>
            </table>
           
        </div>   
    </div>
    <div class="card mt-2">
            <div class="card-body">
                <h5 class="card-title fw-bold ">Keterangan :</h5> <br>
                <span class="card-text">Kuota maksimal bimbingan Skripsi adalah <b> {{ $kapasitas->kapasitas_skripsi }} </b>
                        Orang.</span>
            </div>
        </div>


        <div class="card p-4">

        <div class="mb-4 rounded bg-light">
                <div class="p-2 pt-3">
                    <h5 class="">Daftar Lulus Bimbingan Skripsi</h5>
                    <hr>
                </div>
            </div>

        <table class="table table-responsive-lg rounded table-bordered table-striped" width="100%" id="datatables2">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" scope="col">No.</th>
                        <th class="text-center" scope="col">Kode Dosen</th>
                        <th class="text-center" scope="col">Nama Dosen</th>
                        <th class="text-center" scope="col">Total Lulus</th>
                        <th class="text-center" scope="col">Rerata Membimbing</th>
                        <th class="text-center" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>

    @foreach ($dosen_lulus as $dosen)
    @php
        $pendaftaranSkripsi1 = $dosen->pendaftaranSkripsi1()->where('status_skripsi', 'LULUS')->get();

        $pendaftaranSkripsi2 = $dosen->pendaftaranSkripsi2()->where('status_skripsi', 'LULUS')->get();

        $pendaftaranSkripsi = $pendaftaranSkripsi1->merge($pendaftaranSkripsi2);

        $totalBulan = 0;
        $totalHari = 0;
    @endphp

    @if($pendaftaranSkripsi->count() > 0)
        <div></div>
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center">{{ $dosen->nama_singkat }}</td>
            <td>{{ $dosen->nama }}</td>
            <td class="text-center bg-info">
                <b>{{ $pendaftaranSkripsi->count() }} </b>
            </td>

            @foreach ($pendaftaranSkripsi as $pendaftaran)
                @php
                    $tanggalMulaiSkripsi = $pendaftaran->tgl_disetujui_usuljudul_kaprodi ? \Carbon\Carbon::parse($pendaftaran->tgl_disetujui_usuljudul_kaprodi) : null;
                    $tanggalSelesai = $pendaftaran->tgl_disetujui_sti_17_koordinator ? \Carbon\Carbon::parse($pendaftaran->tgl_disetujui_sti_17_koordinator) : null;

                    $totalMhs = $pendaftaranSkripsi->count();

                    $durasiSkripsi = $tanggalMulaiSkripsi ? $tanggalMulaiSkripsi->diffInMonths($tanggalSelesai) : null;
                    $bulan = $durasiSkripsi ? floor($durasiSkripsi) : null;
                    $hari = $tanggalMulaiSkripsi ? $tanggalMulaiSkripsi->addMonths($bulan)->diffInDays($tanggalSelesai) : null;
                    
                    if ($durasiSkripsi !== null) {
                        $totalBulan += $bulan / $totalMhs;
                        $totalHari += $hari / $totalMhs;
                    }
                @endphp
            @endforeach
            <td class="text-center bg-info">
                   <b> {{ round($totalBulan) }} </b> <small>Bulan</small> <b> {{ round($totalHari) }} </b> <small>Hari</small>
            </td>

            <td class="text-center">
                <a href="/detail/lulus-bimbingan/skripsi/{{ $dosen->nip }}" class="badge btn btn-info p-1 mb-1"
                    data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
            </td>
        </tr>
    @endif
@endforeach
                </tbody>
            </table>
           
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



