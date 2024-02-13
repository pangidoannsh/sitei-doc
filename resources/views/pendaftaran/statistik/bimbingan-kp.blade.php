@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Statistik Bimbingan Kerja Praktek
@endsection

@section('sub-title')
    Statistik Bimbingan Kerja Praktek
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
                <a href="/statistik" class="px-1">Statisktik</a>
            </li>

            <span class="px-2">|</span>
            <li><a href="/statistik/bimbingan-kp" class="breadcrumb-item active fw-bold text-success px-1">Bimbingan KP</a></li>
            <span class="px-2">|</span>
            <li><a href="/statistik/bimbingan-skripsi" class="px-1">Bimbingan Skripsi</a></li>

        </ol>

        <div class="container-fluid">

        <div class="mb-4 rounded bg-light">
                <div class="p-2 pt-3">
                    <h5 class="">Daftar Beban Bimbingan Kerja Praktek</h5>
                    <hr>
                </div>
            </div>
        <table class="table table-responsive-lg rounded table-bordered table-striped" width="100%" id="datatables">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" scope="col">No.</th>
                        <th class="text-center" scope="col">Kode Dosen</th>
                        <th class="text-center" scope="col">Nama Dosen</th>
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
                            <td class="text-center @if ($dosen->pendaftaran_k_p_count >= $kapasitas->kapasitas_kp) bg-danger @endif bg-info">
                                <b>{{ $dosen->pendaftaran_k_p_count }} </b></td>

                                <!-- DURASI -->
                            

                            <td class="text-center">
                                <a href="/detail/kuota-bimbingan/kp/{{ $dosen->nip }}" class="badge btn btn-info p-1 mb-1"
                                    data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                            </td>
                    @endforeach
                </tbody>
            </table>
           
        </div>
    </div>

    
        <div class="card mt-2">
            <div class="card-body">
                <h5 class="card-title fw-bold ">Keterangan :</h5> <br>
                <span class="card-text">Kuota maksimal bimbingan KP adalah <b> {{ $kapasitas->kapasitas_kp }} Orang. </b>
                </span>
            </div>
        </div>



        <div class="card p-4">

        <div class="mb-4 rounded bg-light">
                <div class="p-2 pt-3">
                    <h5 class="">Daftar Selesai Bimbingan Kerja Praktek</h5>
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
        $pendaftaranKP = $dosen->pendaftarankp()->where('status_kp', 'KP SELESAI')->get();
        $totalBulan = 0;
        $totalHari = 0;
    @endphp

    @if($pendaftaranKP->count() > 0)
        <div></div>
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center">{{ $dosen->nama_singkat }}</td>
            <td>{{ $dosen->nama }}</td>
            <td class="text-center bg-info">
                <b>{{ $pendaftaranKP->count() }}</b>
            </td>

            @foreach ($pendaftaranKP as $pendaftaran)
                @php
                    $tanggalMulaiKP = $pendaftaran->tgl_disetujui_balasan ? \Carbon\Carbon::parse($pendaftaran->tgl_disetujui_balasan) : null;
                    $tanggalSelesai = $pendaftaran->tgl_disetujui_kpti_10_koordinator ? \Carbon\Carbon::parse($pendaftaran->tgl_disetujui_kpti_10_koordinator) : null;
                    $totalMhs = $pendaftaranKP->count();

                    $durasiKP = $tanggalMulaiKP ? $tanggalMulaiKP->diffInMonths($tanggalSelesai) : null;
                    $bulan = $durasiKP ? floor($durasiKP) : null;
                    $hari = $tanggalMulaiKP ? $tanggalMulaiKP->addMonths($bulan)->diffInDays($tanggalSelesai) : null;
                    
                    if ($durasiKP !== null) {
                        $totalBulan += $bulan / $totalMhs;
                        $totalHari += $hari / $totalMhs;
                    }
                @endphp
            @endforeach
            <td class="text-center bg-info">
                   <b> {{ round($totalBulan) }}</b> <small>Bulan</small> <b>{{ round($totalHari) }}</b> <small>Hari</small>
            </td>

            <td class="text-center">
                <a href="/detail/lulus-bimbingan/kp/{{ $dosen->nip }}" class="badge btn btn-info p-1 mb-1"
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



