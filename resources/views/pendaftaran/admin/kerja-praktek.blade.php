@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Data Kerja Praktek Mahasiswa
@endsection

@section('sub-title')
    Data Kerja Praktek Mahasiswa
@endsection

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="container card p-4">

        <ol class="breadcrumb col-lg-12">
            @if (Str::length(Auth::guard('web')->user()) > 0)
                @if (Auth::guard('web')->user()->role_id == 2 ||
                        Auth::guard('web')->user()->role_id == 3 ||
                        Auth::guard('web')->user()->role_id == 4)
                    <li><a href="/persetujuan/admin/index" class="px-1">Persetujuan
                            (<span>{{ $jml_persetujuan_kp + $jml_persetujuan_skripsi }}</span>)</a></li>
                    <span class="px-2">|</span>
                @endif
                <li><a href="/form" class="px-1">Seminar
                        (<span>{{ $jml_seminar_kp + $jml_sempro + $jml_sidang }}</span>)</a></li>
                <span class="px-2">|</span>

                @if (Auth::guard('web')->user()->role_id == 1)
                    <li><a href="/kerja-praktek/admin/index" class="breadcrumb-item active fw-bold text-success px-1">Data
                            KP
                            (<span>{{ $jml_prodi_kp }}</span>)</a></li>
                    <span class="px-2">|</span>
                    <li><a href="/sidang/admin/index" class="px-1">Data Skripsi
                            (<span>{{ $jml_prodi_skripsi }}</span>)</a>
                    </li>
                    <span class="px-2">|</span>
                    <li><a href="/prodi/riwayat" class="px-1">Riwayat
                            (<span>{{ $jml_riwayat_prodi_kp + $jml_riwayat_prodi_skripsi + $jml_riwayat_seminar_kp + $jml_riwayat_sempro + $jml_riwayat_skripsi }}</span>)</a>
                    </li>
                    
                    <span class="px-2">|</span>
                    <li><a href="/statistik" class="px-1">Statistik (All)</a></li>
                @endif

                @if (Auth::guard('web')->user()->role_id == 2 ||
                        Auth::guard('web')->user()->role_id == 3 ||
                        Auth::guard('web')->user()->role_id == 4)
                    <li><a href="/kerja-praktek/admin/index" class="breadcrumb-item active fw-bold text-success px-1">Data
                            KP (<span>{{ $jml_prodikp }}</span>)</a></li>
                    <span class="px-2">|</span>
                    <li><a href="/sidang/admin/index" class="px-1">Data Skripsi (<span>{{ $jml_prodiskripsi }}</span>)</a>
                    </li>
                    <span class="px-2">|</span>
                    <li><a href="/prodi/riwayat" class="px-1">Riwayat
                            (<span>{{ $jml_riwayatkp + $jml_riwayatskripsi + $jml_jadwal_kps + $jml_jadwal_sempros + $jml_jadwal_skripsis }}</span>)</a>
                    </li>
                    <span class="px-2">|</span>
                    <li><a href="/statistik" class="px-1">Statistik (All)</a></li>
                @endif

            @endif

        </ol>

        <div class="container-fluid">

            <table class="table table-responsive-lg table-bordered table-striped" width="100%" id="datatables">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center p-0 pb-2" scope="col">No.</th>
                        <th class="text-center" scope="col">NIM</th>
                        <th class="text-center" scope="col">Nama</th>
                        <!-- <th class="text-center" scope="col">Konsentrasi</th>-->
                        <!-- <th class="text-center" scope="col">Jenis Usulan</th> -->
                        <th class="text-center" scope="col">Status</th>
                        <th class="text-center" scope="col">Tanggal Penting</th>
                        <th class="text-center" scope="col">Keterangan</th>
                        <th class="text-center" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($pendaftaran_kp as $kp)
                        <div></div>
                        <tr>
                            <td class="text-center p-2">{{ $loop->iteration }}</td>
                            <td class="text-center p-2">{{ $kp->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 py-2 fw-bold">{{ $kp->mahasiswa->nama }}</td>
                            <!-- <td class="text-center p-2">{{ $kp->konsentrasi->nama_konsentrasi }}</td>                    -->

                            <!-- <td class="text-center p-2">{{ $kp->jenis_usulan }}</td> -->

                            @if (
                                $kp->status_kp == 'USULAN KP' ||
                                    $kp->status_kp == 'SURAT PERUSAHAAN' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP' ||
                                    $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN')
                                <td class="text-center p-2 bg-secondary">{{ $kp->status_kp }}</td>
                            @endif
                            @if (
                                $kp->status_kp == 'USULAN KP DITERIMA' ||
                                    $kp->status_kp == 'KP DISETUJUI' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI' ||
                                    $kp->status_kp == 'SEMINAR KP SELESAI' ||
                                    $kp->status_kp == 'KP SELESAI')
                                <td class="text-center p-2 bg-info">{{ $kp->status_kp }}</td>
                            @endif


                            @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                                <td class="text-center p-2 bg-success">{{ $kp->status_kp }}</td>
                            @endif

                            @if (
                                $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP ULANG' ||
                                    $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK')
                                <td class="text-center p-2 bg-danger">{{ $kp->status_kp }}</td>
                            @endif

                            @if ($kp->status_kp == 'USULAN KP')
                                <td class="text-center p-2"> <small> Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($kp->tgl_created_usulan)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($kp->status_kp == 'USULAN KP DITERIMA')
                                <td class="text-center p-2"> <small> Tanggal Diterima:
                                        <br></small>{{ Carbon::parse($kp->tgl_disetujui_usulankp_kaprodi)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($kp->status_kp == 'SURAT PERUSAHAAN' || $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK')
                                <td class="text-center p-2"> <small>Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($kp->tgl_created_balasan)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($kp->status_kp == 'KP DISETUJUI')
                                <td class="text-center p-2"> <small>Tanggal Disetujui:
                                        <br></small>{{ Carbon::parse($kp->tgl_disetujui_balasan)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if (
                                $kp->status_kp == 'DAFTAR SEMINAR KP' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP ULANG')
                                <td class="text-center p-2"> <small>Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($kp->tgl_created_semkp)->translatedFormat(' d F Y') }}
                                </td>
                            @endif
                            @if ($kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI')
                                <td class="text-center p-2"> <small>Tanggal Disetujui:
                                        <br></small>{{ Carbon::parse($kp->tgl_created_semkp_kaprodi)->translatedFormat(' d F Y') }}
                                </td>
                            @endif
                            @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                                <td class="text-center p-2"> <small>Tanggal Dijadwalkan:
                                        <br></small>{{ Carbon::parse($kp->tgl_dijadwalkan)->translatedFormat(' d F Y') }}
                                </td>
                            @endif
                            @if ($kp->status_kp == 'SEMINAR KP SELESAI')
                                <td class="text-center p-2"> <small>Tanggal Selesai:
                                        <br></small>{{ Carbon::parse($kp->tgl_selesai_semkp)->translatedFormat(' d F Y') }}
                                </td>
                            @endif
                            @if ($kp->status_kp == 'BUKTI PENYERAHAN LAPORAN' || $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK')
                                <td class="text-center p-2"> <small>Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($kp->tgl_created_kpti10)->translatedFormat(' d F Y') }}
                                </td>
                            @endif
                            @if ($kp->status_kp == 'KP SELESAI')
                                <td class="text-center p-2"> <small>Tanggal Selesai:
                                        <br></small>{{ Carbon::parse($kp->tgl_selesai_semkp)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if (
                                $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' ||
                                    $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP ULANG')
                                <td class="text-center p-2 text-danger">{{ $kp->keterangan }}</td>
                            @elseif(
                                ($kp->keterangan == 'Menunggu persetujuan Admin Prodi' && Auth::guard('web')->user()->role_id == 2) ||
                                    ($kp->keterangan == 'Menunggu persetujuan Admin Prodi' && Auth::guard('web')->user()->role_id == 3) ||
                                    ($kp->keterangan == 'Menunggu persetujuan Admin Prodi' && Auth::guard('web')->user()->role_id == 4))
                                <td class="text-center p-2 text-success">
                                    <i class="fas fa-circle small-icon"></i> {{ $kp->keterangan }}
                                </td>
                            @else
                                <td class="text-center p-2">{{ $kp->keterangan }}</td>
                            @endif

                            @if ($kp->status_kp == 'USULAN KP' || $kp->status_kp == 'USULAN KP DITERIMA')
                                <td class="text-center p-2">
                                    <a href="/usulan/detail/pembimbingprodi/{{ $kp->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </td>
                            @endif
                            @if (
                                $kp->status_kp == 'SURAT PERUSAHAAN' ||
                                    $kp->status_kp == 'KP DISETUJUI' ||
                                    $kp->status_kp == 'SURAT PERUSAHAAN DITOLAK')
                                <td class="text-center p-2">
                                    <a href="/suratperusahaan/detail/pembimbingprodi/{{ $kp->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </td>
                            @endif

                            @if (
                                $kp->status_kp == 'DAFTAR SEMINAR KP' ||
                                    $kp->status_kp == 'SEMINAR KP DIJADWALKAN' ||
                                    $kp->status_kp == 'SEMINAR KP SELESAI' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP ULANG' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI')
                                <td class="text-center p-2">
                                    <a href="/daftar-semkp/detail/{{ $kp->id }}" class="badge btn btn-info p-1"
                                        data-bs-toggle="tooltip" title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                </td>
                            @endif

                            @if (
                                $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN' ||
                                    $kp->status_kp == 'KP SELESAI' ||
                                    $kp->status_kp == 'BUKTI PENYERAHAN LAPORAN DITOLAK')
                                <td class="text-center p-2">
                                    <a href="/kpti10/detail/pembimbingprodi/{{ $kp->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </td>
                            @endif



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

@if (Auth::guard('web')->user()->role_id == 1)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const jumlahProdi = {!! json_encode($jml_prodi_kp) !!};
                if (jumlahProdi > 0) {
                    Swal.fire({
                        title: 'Ini adalah halaman Kerja Praktek',
                        html: `Ada <strong class="text-info"> ${jumlahProdi} Mahasiswa</strong> sedang melaksanakan Kerja Praktek.`,
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonColor: '#28a745',
                    });
                } else {
                    Swal.fire({
                        title: 'Ini adalah halaman Kerja Praktek',
                        html: `Belum ada mahasiswa yang melaksanakan Kerja Praktek.`,
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonColor: '#28a745',
                    });
                }
            });
        </script>
    @endpush()
@endif

@if (Auth::guard('web')->user()->role_id == 2 ||
        Auth::guard('web')->user()->role_id == 3 ||
        Auth::guard('web')->user()->role_id == 4)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const jumlahProdi = {!! json_encode($jml_prodikp) !!};
                if (jumlahProdi > 0) {
                    Swal.fire({
                        title: 'Ini adalah halaman Kerja Praktek',
                        html: `Ada <strong class="text-info"> ${jumlahProdi} Mahasiswa</strong> sedang melaksanakan Kerja Praktek.`,
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonColor: '#28a745',
                    });
                } else {
                    Swal.fire({
                        title: 'Ini adalah halaman Kerja Praktek',
                        html: `Belum ada mahasiswa yang melaksanakan Kerja Praktek.`,
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonColor: '#28a745',
                    });
                }
            });
        </script>
    @endpush()
@endif
