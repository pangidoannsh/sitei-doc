@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Persetujuan Kerja Praktek dan Skripsi
@endsection

@section('sub-title')
    Persetujuan Kerja Praktek dan Skripsi
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
                    <li><a href="/persetujuan/admin/index"
                            class="breadcrumb-item active fw-bold text-success px-1">Persetujuan
                            (<span>{{ $jml_persetujuan_kp + $jml_persetujuan_skripsi }}</span>)</a></li>
                    <span class="px-2">|</span>

                    <li><a href="/form" class="px-1">Seminar
                            (<span>{{ $jml_seminar_kp + $jml_sempro + $jml_sidang }}</span>)</a></li>
                    <span class="px-2">|</span>

                    <li><a href="/kerja-praktek/admin/index" class="px-1">Data KP (<span>{{ $jml_prodikp }}</span>)</a>
                    </li>
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
                        <!-- <th class="text-center p-2" scope="col">No.</th> -->
                        <th class="text-center" scope="col">NIM</th>
                        <th class="text-center" scope="col">Nama</th>
                        <!-- <th class="text-center" scope="col">Jenis Usulan</th> -->
                        <th class="text-center" scope="col">Status </th>
                        <th class="text-center" scope="col">Tanggal Usulan</th>
                        <!-- <th class="text-center" scope="col">Batas</th> -->
                        <th class="text-center" scope="col">Keterangan</th>
                        <th class="text-center" scope="col" style="padding-left: 50px; ">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    <div></div>
                    @foreach ($pendaftaran_kp as $kp)
                        <!-- USULAN KP -->
                        @php
                            $countDownDateUsulanKPAdmin = strtotime($kp->tgl_created_usulankp) + 4 * 24 * 60 * 60;
                            $nowUsulanKPAdmin = time();
                            $distanceUsulanKPAdmin = $countDownDateUsulanKPAdmin - $nowUsulanKPAdmin;
                            $daysUsulanKPAdmin = floor($distanceUsulanKPAdmin / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <!-- SEMINAR KP -->
                        @php
                            $countDownDateSeminarKPAdmin = strtotime($kp->tgl_created_semkp) + 4 * 24 * 60 * 60;
                            $nowSeminarKPAdmin = time();
                            $distanceSeminarKPAdmin = $countDownDateSeminarKPAdmin - $nowSeminarKPAdmin;
                            $daysSeminarKPAdmin = floor($distanceSeminarKPAdmin / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <!-- SEMINAR KP DISETUJUI-->
                        @php
                            $countDownDateSeminarKPDisetujui = strtotime($kp->tgl_disetujui_semkp_kaprodi) + 4 * 24 * 60 * 60;
                            $nowSeminarKPDisetujui = time();
                            $distanceSeminarKPDisetujui = $countDownDateSeminarKPDisetujui - $nowSeminarKPDisetujui;
                            $daysSeminarKPDisetujui = floor($distanceSeminarKPDisetujui / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <tr>
                            <!-- <td class="text-center px-1 py-2">{{ $loop->iteration }}</td> -->
                            <td class="text-center px-1 py-2">{{ $kp->mahasiswa_nim }}</td>
                            <td class="text-left pl-3 pr-1 py-2 fw-bold">{{ $kp->mahasiswa->nama }}</td>

                            <!-- <td class="text-center px-1 py-2">{{ $kp->jenis_usulan }}</td>       -->

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
                                    $kp->status_kp == 'SEMINAR KP SELESAI' ||
                                    $kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI' ||
                                    $kp->status_kp == 'KP SELESAI')
                                <td class="text-center px-1 py-2 bg-info">{{ $kp->status_kp }}</td>
                            @endif


                            @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                                <td class="text-center px-1 py-2 bg-success">{{ $kp->status_kp }}</td>
                            @endif

                            @if ($kp->status_kp == 'USULAN KP')
                                <td class="text-center px-1 py-2">
                                    {{ Carbon::parse($kp->tgl_created_usulan)->translatedFormat('l, d F Y') }}</td>
                            @endif
                            @if ($kp->status_kp == 'DAFTAR SEMINAR KP' || $kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI')
                                <td class="text-center px-1 py-2">
                                    {{ Carbon::parse($kp->tgl_created_semkp)->translatedFormat('l, d F Y') }}</td>
                            @endif

                            <!-- @if ($kp->status_kp == 'USULAN KP')
                                <td class="text-center px-1 py-2">
                                    @if ($daysUsulanKPAdmin > 0)
                                        <span class="text-danger"> {{ $daysUsulanKPAdmin }} hari lagi</span>
                                    @elseif($daysUsulanKPAdmin <= 0)
                                        Batas Waktu persetujuan telah habis
                                    @endif
                                </td>
                            @endif

                            @if ($kp->status_kp == 'DAFTAR SEMINAR KP')
                                <td class="text-center px-1 py-2">
                                    @if ($daysSeminarKPAdmin >= 0)
                                        <span class="text-danger"> {{ $daysSeminarKPAdmin }} hari lagi</span>
                                    @elseif($daysSeminarKPAdmin <= 0)
                                        Batas Waktu persetujuan telah habis
                                    @endif
                                </td>
                            @endif
                            @if ($kp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI')
                                <td class="text-center px-1 py-2">
                                    @if ($daysSeminarKPDisetujui >= 0)
                                        <span class="text-danger"> {{ $daysSeminarKPDisetujui }} hari lagi</span>
                                    @elseif($daysSeminarKPDisetujui <= 0)
                                        Batas Waktu persetujuan telah habis
                                    @endif
                                </td>
                            @endif -->

                            <td class="text-center px-1 py-2">{{ $kp->keterangan }}</td>

                            @if ($kp->status_kp == 'USULAN KP' || $kp->status_kp == 'USULAN KP DITERIMA')
                                <td class="text-center px-2 py-2">
                                    @if (Str::length(Auth::guard('web')->user()) > 0)
                                        @if (Auth::guard('web')->user()->role_id == 2 ||
                                                Auth::guard('web')->user()->role_id == 3 ||
                                                Auth::guard('web')->user()->role_id == 4)
                                            @if ($kp->keterangan == 'Menunggu persetujuan Admin Prodi' && $kp->status_kp == 'USULAN KP')
                                                <div class="row ml-0 ml-md-4">

                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <button onclick="tolakUsulanKPAdmin({{ $kp->id }})"
                                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                            title="Tolak"><i class="fas fa-times-circle"></i></button>
                                                    </div>
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <a href="/kp-skripsi/persetujuan/usulankp/{{ $kp->id }}"
                                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <form action="/usulankp/admin/approve/{{ $kp->id }}"
                                                            class="setujui-usulankp-admin" method="POST">
                                                            @method('put')
                                                            @csrf
                                                            <button class="btn btn-success badge p-1 "><i
                                                                    class="fas fa-check-circle"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            @endif


                            @if ($kp->status_kp == 'DAFTAR SEMINAR KP')
                                <td class="text-center px-2 py-2">
                                    @if (Str::length(Auth::guard('web')->user()) > 0)
                                        @if (Auth::guard('web')->user()->role_id == 2 ||
                                                Auth::guard('web')->user()->role_id == 3 ||
                                                Auth::guard('web')->user()->role_id == 4)
                                            @if ($kp->keterangan == 'Menunggu persetujuan Admin Prodi' && $kp->status_kp == 'DAFTAR SEMINAR KP')
                                                <div class="row ml-0 ml-md-4">

                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <button onclick="tolakSemKPAdmin({{ $kp->id }})"
                                                            class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                            title="Tolak"><i class="fas fa-times-circle"></i></button>
                                                    </div>
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <a href="/kp-skripsi/persetujuan/semkp/{{ $kp->id }}"
                                                            class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                            title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 py-2 py-md-0">
                                                        <form action="/semkp/admin/approve/{{ $kp->id }}"
                                                            class="setujui-semkp-admin" method="POST">
                                                            @method('put')
                                                            @csrf
                                                            <button class="btn btn-success badge p-1 "><i
                                                                    class="fas fa-check-circle"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            @endif


                        </tr>
                    @endforeach

                    @foreach ($pendaftaran_skripsi as $skripsi)
                        <!-- USULAN JUDUL -->
                        @php
                            $countDownDateUsulJudulAdmin = strtotime($skripsi->tgl_created_usuljudul) + 4 * 24 * 60 * 60;
                            $nowUsulJudulAdmin = time();
                            $distanceUsulJudulAdmin = $countDownDateUsulJudulAdmin - $nowUsulJudulAdmin;
                            $daysUsulJudulAdmin = floor($distanceUsulJudulAdmin / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <!-- USULAN DAFTAR SEMPRO -->

                        @php
                            $countDownDateDaftarSemproAdmin = strtotime($skripsi->pembimbing_2_nip != null ? $skripsi->tgl_disetujui_sempro_pemb2 : $skripsi->tgl_disetujui_sempro_pemb1) + 4 * 24 * 60 * 60;
                            $nowDaftarSemproAdmin = time();
                            $distanceDaftarSemproAdmin = $countDownDateDaftarSemproAdmin - $nowDaftarSemproAdmin;
                            $daysDaftarSemproAdmin = floor($distanceDaftarSemproAdmin / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <!-- USULAN DAFTAR SIDANG -->
                        @php
                            $countDownDateDaftarSidangAdmin = strtotime($skripsi->pembimbing_2_nip != null ? $skripsi->tgl_disetujui_sidang_pemb2 : $skripsi->tgl_disetujui_sidang_pemb1) + 4 * 24 * 60 * 60;
                            $nowDaftarSidangAdmin = time();
                            $distanceDaftarSidangAdmin = $countDownDateDaftarSidangAdmin - $nowDaftarSidangAdmin;
                            $daysDaftarSidangAdmin = floor($distanceDaftarSidangAdmin / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->

                        <!-- USULAN DAFTAR SIDANG -->
                        @php
                            $countDownDateMenungguSidangAdmin = strtotime($skripsi->tgl_disetujui_sidang_kaprodi) + 4 * 24 * 60 * 60;
                            $nowMenungguSidangAdmin = time();
                            $distanceMenungguSidangAdmin = $countDownDateMenungguSidangAdmin - $nowMenungguSidangAdmin;
                            $daysMenungguSidangAdmin = floor($distanceMenungguSidangAdmin / (60 * 60 * 24));
                        @endphp
                        <!-- BATAS -->


                        <div></div>
                        <tr>
                            <!--<td class="text-center px-1 py-2">{{ $loop->iteration }}</td>                             -->
                            <td class="text-center px-1 py-2">{{ $skripsi->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 py-2 fw-bold">{{ $skripsi->mahasiswa->nama }}</td>
                            <!-- <td class="text-center px-1 py-2">{{ $skripsi->konsentrasi->nama_konsentrasi }}</td> -->
                            <!-- <td class="text-center px-1 py-2">{{ $skripsi->jenis_usulan }}</td>          -->
                            <!-- USUL JUDUL  -->
                            @if (
                                $skripsi->status_skripsi == 'USULAN JUDUL' ||
                                    $skripsi->status_skripsi == 'DAFTAR SEMPRO' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI')
                                <td class="text-center px-1 py-2 bg-secondary">{{ $skripsi->status_skripsi }}</td>
                            @endif
                            @if (
                                $skripsi->status_skripsi == 'JUDUL DISETUJUI' ||
                                    $skripsi->status_skripsi == 'SEMPRO SELESAI' ||
                                    $skripsi->status_skripsi == 'PERPANJANGAN REVISI DISETUJUI' ||
                                    $skripsi->status_skripsi == 'SIDANG SELESAI' ||
                                    $skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI')
                                <td class="text-center px-1 py-2 bg-info">{{ $skripsi->status_skripsi }}</td>
                            @endif

                            @if ($skripsi->status_skripsi == 'SEMPRO DIJADWALKAN' || $skripsi->status_skripsi == 'SIDANG DIJADWALKAN')
                                <td class="text-center px-1 py-2 bg-success">{{ $skripsi->status_skripsi }}</td>
                            @endif

                            <!-- ___________batas____________ -->

                            @if ($skripsi->status_skripsi == 'USULAN JUDUL')
                                <td class="text-center px-1 py-2">
                                    {{ Carbon::parse($skripsi->tgl_created_usuljudul)->translatedFormat('l, d F Y') }}</td>
                            @endif
                            @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO')
                                <td class="text-center px-1 py-2">
                                    {{ Carbon::parse($skripsi->tgl_created_sempro)->translatedFormat('l, d F Y') }}</td>
                            @endif
                            @if ($skripsi->status_skripsi == 'DAFTAR SIDANG' || $skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI')
                                <td class="text-center px-1 py-2">
                                    {{ Carbon::parse($skripsi->tgl_created_sidang)->translatedFormat('l, d F Y') }}</td>
                            @endif

                            <!-- BATAS PERSETUJUAN -->
                            <!-- @if ($skripsi->status_skripsi == 'USULAN JUDUL')
                                <td class="text-center px-1 py-2">
                                    @if ($daysUsulJudulAdmin >= 0)
                                        <span class="text-danger"> {{ $daysUsulJudulAdmin }} hari lagi</span>
                                    @elseif($daysUsulJudulAdmin <= 0)
                                        Batas Waktu Persetujuan telah habis
                                    @endif
                                </td>
                            @endif -->

                            <!-- DAFTAR SEMPRO -->
                            <!-- @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO')
                                <td class="text-center px-1 py-2">
                                    @if ($daysDaftarSemproAdmin >= 0)
                                        <span class="text-danger"> {{ $daysDaftarSemproAdmin }} hari lagi</span>
                                    @elseif($daysDaftarSemproAdmin <= 0)
                                        Batas Waktu Persetujuan telah habis
                                    @endif
                                </td>
                            @endif -->

                            <!-- DAFTAR SIDANG -->
                            <!-- @if ($skripsi->status_skripsi == 'DAFTAR SIDANG')
                                <td class="text-center px-1 py-2">
                                    @if ($daysDaftarSidangAdmin >= 0)
                                        <span class="text-danger"> {{ $daysDaftarSidangAdmin }} hari lagi</span>
                                    @elseif($daysDaftarSidangAdmin <= 0)
                                        Batas Waktu Persetujuan telah habis
                                    @endif
                                </td>
                            @endif -->

                            <!-- DAFTAR MENUNGGU JADWAL SIDANG -->
                            <!-- @if ($skripsi->status_skripsi == 'DAFTAR SIDANG DISETUJUI')
                                <td class="text-center px-1 py-2">
                                    @if ($daysMenungguSidangAdmin >= 0)
                                        <span class="text-danger"> {{ $daysMenungguSidangAdmin }} hari lagi</span>
                                    @elseif($daysMenungguSidangAdmin <= 0)
                                        Batas Waktu Persetujuan telah habis
                                    @endif
                                </td>
                            @endif -->

                            <td class="text-center px-1 py-2"> {{ $skripsi->keterangan }}</td>

                            <!-- USUL JUDUL  -->
                            @if ($skripsi->status_skripsi == 'USULAN JUDUL' || $skripsi->status_skripsi == 'JUDUL DISETUJUI')
                                @if ($skripsi->keterangan == 'Menunggu persetujuan Admin Prodi' && $skripsi->status_skripsi == 'USULAN JUDUL')
                                    <td class="text-center px-1 py-2">
                                        <div class="row ml-0 ml-md-4  ">
                                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                                <button onclick="tolakUsulJudulAdmin({{ $skripsi->id }})"
                                                    class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                    title="Tolak"><i class="fas fa-times-circle"></i></button>
                                            </div>
                                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                                <a href="/persetujuan/admin/detail/usulanjudul/{{ $skripsi->id }}"
                                                    class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                    title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                            </div>
                                            <div class="col-lg-3 col-12 py-2 py-md-0">
                                                <form action="/usuljudul/admin/approve/{{ $skripsi->id }}"
                                                    class="setujui-usulanjudul-admin" method="POST">
                                                    @method('put')
                                                    @csrf
                                                    <button class="btn btn-success badge p-1 "><i
                                                            class="fas fa-check-circle"></i></button>
                                                </form>
                                            </div>
                                        </div>

                                    </td>
                                @endif
                            @endif

                            <!-- DAFTAR SEMPRO -->
                            @if ($skripsi->status_skripsi == 'DAFTAR SEMPRO' && $skripsi->keterangan == 'Menunggu persetujuan Admin Prodi')
                                <td class="text-center px-1 py-2">
                                    <div class="row ml-0 ml-md-4">
                                        <div class="col-lg-3 col-md-12 py-2 py-md-0">
                                            <button onclick="tolakSemproAdmin({{ $skripsi->id }})"
                                                class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                title="Tolak"><i class="fas fa-times-circle"></i></button>
                                        </div>
                                        <div class="col-lg-3 col-md-12 py-2 py-md-0">
                                            <a href="/persetujuan/admin/detail/sempro/{{ $skripsi->id }}"
                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                        </div>
                                        <div class="col-lg-3 col-md-12 py-2 py-md-0">
                                            <form action="/daftar-sempro/admin/approve/{{ $skripsi->id }}"
                                                class="setujui-sempro-admin" data-bs-toggle="tooltip"
                                                title="Setujui" method="POST">
                                                @method('put')
                                                @csrf
                                                <button class="btn btn-success badge p-1 "><i
                                                        class="fas fa-check-circle"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            @endif

                            <!-- DAFTAR SIDANG -->
                            @if ($skripsi->keterangan == 'Menunggu persetujuan Admin Prodi' && $skripsi->status_skripsi == 'DAFTAR SIDANG')
                                
                                
                                <td class="text-center px-1 py-2">
                                    <div class="row ml-0 ml-md-4">
                                        <div class="col-lg-3 col-md-12 py-2 py-md-0">
                                            <button onclick="tolakSidangAdmin({{ $skripsi->id }})"
                                                class="btn btn-danger badge p-1 " data-bs-toggle="tooltip"
                                                title="Tolak"><i class="fas fa-times-circle"></i></button>
                                        </div>
                                        <div class="col-lg-3 col-md-12 py-2 py-md-0">
                                            <a href="/persetujuan/admin/detail/sidang/{{ $skripsi->id }}"
                                                class="badge btn btn-info p-1" data-bs-toggle="tooltip"
                                                title="Lihat Detail"><i class="fas fa-info-circle"></i></a>
                                        </div>
                                        <div class="col-lg-3 col-md-12 py-2 py-md-0">
                                            <form action="/daftar-sidang/admin/approve/{{ $skripsi->id }}"
                                                class="setujui-sidang-admin" data-bs-toggle="tooltip"
                                                title="Setujui & Tandai Terjadwal" method="POST">
                                                @method('put')
                                                @csrf
                                                <button class="btn btn-success badge p-1 "><i
                                                        class="fas fa-check-circle"></i></button>
                                            </form>
                                        </div>
                                    </div>
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

@push('scripts')
    @foreach ($pendaftaran_kp as $kp)
        <script>
            $('.setujui-usulankp-admin').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan KP!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakUsulanKPAdmin(id) {
                Swal.fire({
                    title: 'Tolak Usulan KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan KP',
                            html: `
                        <form id="reasonForm" action="/usulankp/admin/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            $('.setujui-semkp-admin').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan Daftar Seminar KP!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSemKPAdmin(id) {
                Swal.fire({
                    title: 'Tolak Usulan Daftar Seminar KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Daftar Seminar KP',
                            html: `
                        <form id="reasonForm" action="/semkp/admin/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }
        </script>
    @endforeach
@endpush()

@push('scripts')
    @foreach ($pendaftaran_skripsi as $skripsi)
        <script>
            //SKRIPSI


            $('.setujui-usulanjudul-admin').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan Judul Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakUsulJudulAdmin(id) {
                Swal.fire({
                    title: 'Tolak Usulan Judul Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Judul Skripsi',
                            html: `
                        <form id="reasonForm" action="/usuljudul/admin/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }



            //SEMPRO
            $('.setujui-sempro-admin').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Daftar Seminar Proposal!',
                    text: "Apakah Anda yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSemproAdmin(id) {
                Swal.fire({
                    title: 'Tolak Daftar Seminar Proposal',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Daftar Seminar Proposal',
                            html: `
                        <form id="reasonForm" action="/daftar-sempro/admin/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }


            //SIDANG
            $('.setujui-sidang-admin').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Daftar Sidang Skripsi!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakSidangAdmin(id) {
                Swal.fire({
                    title: 'Tolak Daftar Sidang Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Daftar Sidang Skripsi',
                            html: `
                        <form id="reasonForm" action="/daftar-sidang/admin/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }

            //MENUNGGU JADWAL SIDANG
            $('.setujui-tunggu-sidang-admin').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Sidang Skripsi Dijadwalkan!',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Setuju'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakTungguSidangAdmin(id) {
                Swal.fire({
                    title: 'Tolak Daftar Sidang Skripsi',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Daftar Sidang Skripsi',
                            html: `
                        <form id="reasonForm" action="/daftar-tunggu-sidang/admin/tolak/${id}" method="POST">
                        @method('put')
                            @csrf
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                    }
                });
            }
        </script>
    @endforeach
@endpush()
