@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Data Skripsi Mahasiswa
@endsection

@section('sub-title')
    Data Skripsi Mahasiswa
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
                    <li><a href="/kerja-praktek/admin/index" class="px-1">Data
                            KP
                            (<span>{{ $jml_prodi_kp }}</span>)</a></li>
                    <span class="px-2">|</span>
                    <li><a href="/sidang/admin/index" class="breadcrumb-item active fw-bold text-success px-1">Data Skripsi
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
                    <li><a href="/kerja-praktek/admin/index" class="px-1">Data
                            KP (<span>{{ $jml_prodikp }}</span>)</a></li>
                    <span class="px-2">|</span>
                    <li><a href="/sidang/admin/index" class="breadcrumb-item active fw-bold text-success px-1">Data Skripsi
                            (<span>{{ $jml_prodiskripsi }}</span>)</a>
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
                        <!-- <th class="text-center" scope="col">Konsentrasi</th> -->
                        <!-- <th class="text-center" scope="col">Jenis Usulan</th> -->
                        <th class="text-center" scope="col">Status</th>
                        <th class="text-center" scope="col">Tanggal Penting</th>
                        <th class="text-center" scope="col">Keterangan</th>
                        <th class="text-center" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($pendaftaran_skripsi as $skripsi)
                        <div></div>
                        <tr>
                            <td class="text-center px-1 py-2">{{ $loop->iteration }}</td>
                            <td class="text-center px-1 py-2">{{ $skripsi->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 py-2 fw-bold">{{ $skripsi->mahasiswa->nama }}</td>
                            <!-- <td class="text-center px-1 py-2">{{ $skripsi->konsentrasi->nama_konsentrasi }}</td> -->
                            <!-- <td class="text-center px-1 py-2">{{ $skripsi->jenis_usulan }}</td> -->
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
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO DISETUJUI' ||
                                    $skripsi->status_skripsi == 'SIDANG SELESAI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI' ||
                                    $skripsi->status_skripsi == 'SKRIPSI SELESAI' ||
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
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG ULANG' 
                                    || $skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK' ||
                                    $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK')
                                <td class="text-center px-1 py-2 bg-danger">{{ $skripsi->status_skripsi }}</td>
                            @endif

                            @if ($skripsi->status_skripsi == 'USULAN JUDUL')
                                <td class="text-center px-1 py-2"> <small> Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_created_usuljudul)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'JUDUL DISETUJUI')
                                <td class="text-center px-1 py-2"> <small> Tanggal Disetujui:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_disetujui_usuljudul_kaprodi)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if (
                                $skripsi->status_skripsi == 'DAFTAR SEMPRO' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO DITOLAK')
                                <td class="text-center px-1 py-2"> <small> Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_created_sempro)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO DISETUJUI')
                                <td class="text-center px-1 py-2"> <small> Tanggal Disetujui:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_disetujui_sempro_admin)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'SEMPRO DIJADWALKAN')
                                <td class="text-center px-1 py-2"> <small> Tanggal Dijadwalkan:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_disetujui_jadwalsempro)->translatedFormat(' d F Y') }}
                                </td>
                            @endif
                            @if ($skripsi->status_skripsi == 'SEMPRO SELESAI')
                                <td class="text-center px-1 py-2"> <small> Tanggal Selesai:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_semproselesai)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'PERPANJANGAN 1' || $skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK')
                                <td class="text-center px-1 py-2"> <small> Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_created_perpanjangan1)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI')
                                <td class="text-center px-1 py-2"> <small> Tanggal Disetujui:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_disetujui_perpanjangan1_kaprodi)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'PERPANJANGAN 2' || $skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK')
                                <td class="text-center px-1 py-2"> <small> Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_created_perpanjangan2)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI')
                                <td class="text-center px-1 py-2"> <small> Tanggal Disetujui:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_disetujui_perpanjangan2_kaprodi)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if (
                                $skripsi->status_skripsi == 'DAFTAR SIDANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG ULANG' )
                                <td class="text-center px-1 py-2"> <small> Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_created_sidang)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI')
                                <td class="text-center px-1 py-2"> <small> Tanggal Disetujui:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_disetujui_sidang_kaprodi)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'SIDANG DIJADWALKAN')
                                <td class="text-center px-1 py-2"> <small> Tanggal Dijadwalkan:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_disetujui_jadwal_sidang)->translatedFormat(' d F Y') }}
                                </td>
                            @endif
                            
                            @if ($skripsi->status_skripsi == 'SIDANG SELESAI')
                                <td class="text-center px-1 py-2"> <small> Tanggal Selesai:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_selesai_sidang)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'PERPANJANGAN REVISI' || $skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK')
                                <td class="text-center px-1 py-2"> <small> Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_created_revisi)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if ($skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI')
                                <td class="text-center px-1 py-2"> <small> Tanggal Disetujui:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_disetujui_revisi_kaprodi)->translatedFormat(' d F Y') }}
                                </td>
                            @endif

                            @if (
                                $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI' ||
                                    $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK')
                                <td class="text-center px-1 py-2"> <small> Tanggal Usulan:
                                        <br></small>{{ Carbon::parse($skripsi->tgl_created_sti_17)->translatedFormat(' d F Y') }}
                                </td>
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
                                <td class="text-center px-1 py-2 text-danger">{{ $skripsi->keterangan }}</td>
                            @elseif(
                                ($skripsi->keterangan == 'Menunggu persetujuan Admin Prodi' && Auth::guard('web')->user()->role_id == 2) ||
                                    ($skripsi->keterangan == 'Menunggu persetujuan Admin Prodi' && Auth::guard('web')->user()->role_id == 3) ||
                                    ($skripsi->keterangan == 'Menunggu persetujuan Admin Prodi' && Auth::guard('web')->user()->role_id == 4))
                                <td class="text-center px-1 py-2 text-success">
                                    <i class="fas fa-circle small-icon"></i> {{ $skripsi->keterangan }}
                                </td>
                            @else
                                <td class="text-center px-1 py-2">{{ $skripsi->keterangan }}</td>
                            @endif

                            <!-- USUL JUDUL  -->
                            @if ($skripsi->status_skripsi == 'USULAN JUDUL' || $skripsi->status_skripsi == 'JUDUL DISETUJUI')
                                <td class="text-center px-1 py-2">

                                    <a href="/usuljudul/detail/pembimbing/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>

                                </td>
                            @endif

                            <!-- DAFTAR SEMPRO -->
                            @if (
                                $skripsi->status_skripsi == 'DAFTAR SEMPRO' ||
                                    $skripsi->status_skripsi == 'SEMPRO DIJADWALKAN' ||
                                    $skripsi->status_skripsi == 'SEMPRO SELESAI' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO DISETUJUI' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO ULANG' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO DITOLAK')
                                <td class="text-center px-1 py-2">
                                    <a href="/daftar-sempro/detail/pembimbing/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </td>
                            @endif

                            <!-- DAFTAR SIDANG -->
                            @if (
                                $skripsi->status_skripsi == 'DAFTAR SIDANG' ||
                                    $skripsi->status_skripsi == 'SIDANG DIJADWALKAN' ||
                                    $skripsi->status_skripsi == 'SIDANG SELESAI' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG DITOLAK' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG ULANG')
                                <td class="text-center px-1 py-2">
                                    <a href="/daftar-sidang/detail/pembimbing/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </td>
                            @endif
                            @if (
                                $skripsi->status_skripsi == 'PERPANJANGAN 1' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 1 DISETUJUI')
                                <td class="text-center px-1 py-2">
                                    <a href="/sidang/admin/perpanjangan-1/detail/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1 mb-1" data-bs-toggle="tooltip"
                                        title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                </td>
                            @endif
                            @if (
                                $skripsi->status_skripsi == 'PERPANJANGAN 2' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2 DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN 2 DISETUJUI')
                                <td class="text-center px-1 py-2">
                                    <a href="/sidang/admin/perpanjangan-2/detail/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1 mb-1" data-bs-toggle="tooltip"
                                        title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                </td>
                            @endif
                            @if (
                                $skripsi->status_skripsi == 'PERPANJANGAN REVISI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI DITOLAK' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI')
                                <td class="text-center px-1 py-2">
                                    <a href="/perpanjangan-revisi/detail/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1" data-bs-toggle="tooltip" title="Lihat Detail"><i
                                            class="fas fa-info-circle"></i></a>
                                </td>
                            @endif
                            @if (
                                $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI' ||
                                    $skripsi->status_skripsi == 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK' ||
                                    $skripsi->status_skripsi == 'SKRIPSI SELESAI')
                                <td class="text-center px-1 py-2">
                                    <a href="/bukti-buku-skripsi/detail/{{ $skripsi->id }}"
                                        class="badge btn btn-info p-1 mb-1" data-bs-toggle="tooltip"
                                        title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
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
                const waitingApprovalCount = {!! json_encode($jml_prodi_skripsi) !!};
                if (waitingApprovalCount > 0) {
                    Swal.fire({
                        title: 'Ini adalah halaman Skripsi',
                        html: `Ada <strong class="text-info"> ${waitingApprovalCount} Mahasiswa</strong> sedang melaksanakan Skripsi.`,
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonColor: '#28a745',
                    });
                } else {
                    Swal.fire({
                        title: 'Ini adalah halaman Skripsi',
                        html: `Belum ada mahasiswa yang melaksanakan Skripsi.`,
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
                const waitingApprovalCount = {!! json_encode($jml_prodiskripsi) !!};
                if (waitingApprovalCount > 0) {
                    Swal.fire({
                        title: 'Ini adalah halaman Skripsi',
                        html: `Ada <strong class="text-info"> ${waitingApprovalCount} Mahasiswa</strong> sedang melaksanakan Skripsi.`,
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonColor: '#28a745',
                    });
                } else {
                    Swal.fire({
                        title: 'Ini adalah halaman Skripsi',
                        html: `Belum ada mahasiswa yang melaksanakan Skripsi.`,
                        icon: 'info',
                        showConfirmButton: true,
                        confirmButtonColor: '#28a745',
                    });
                }
            });
        </script>
    @endpush()
@endif
