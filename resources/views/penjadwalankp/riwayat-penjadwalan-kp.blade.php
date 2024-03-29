@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Riwayat Jadwal Seminar KP
@endsection

@section('sub-title')
    Riwayat Jadwal Seminar KP
@endsection

@section('content')
    <ol class="breadcrumb col-lg-12">
        <li class="breadcrumb-item"><a href="/form-kp">Penjadwalan</a></li>
        <li class="breadcrumb-item"><a class="breadcrumb-item active" href="/riwayat-penjadwalan-kp">Riwayat Penjadwalan</a>
        </li>
    </ol>

    <table class="table table-responsive-lg table-bordered table-striped" style="width:100%" id="datatables">
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
                <th class="text-center" scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjadwalan_kps as $kp)
                <tr>
                    <td>{{ $kp->nim }}</td>
                    <td>{{ $kp->nama }}</td>
                    <td class="bg-primary">{{ $kp->jenis_seminar }}</td>
                    <td>{{ $kp->prodi->nama_prodi }}</td>
                    <td>{{ Carbon::parse($kp->tanggal)->translatedFormat('l, d F Y') }}</td>
                    <td>{{ $kp->waktu }}</td>
                    <td>{{ $kp->lokasi }}</td>
                    <td>
                        <p>{{ $kp->pembimbing->nama }}</p>
                    </td>
                    <td>
                        <p>{{ $kp->penguji->nama }}</p>
                    </td>
                    <td>
                        <a href="/nilai-kp/{{ $kp->id }}" class="badge bg-success">Berita Acara</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
                    <a class="text-success fw-bold" formtarget="_blank" target="_blank" href="/developer/m-seprinaldi"> M.
                        Seprinaldi</a><span class="text-success fw-bold">)</span></small></p>
        </div>
    </section>
@endsection
