@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Jadwal Seminar
@endsection

@section('sub-title')
    Jadwal Seminar
@endsection

@section('content')

    @if (session()->has('message'))
        <div class="swal" data-swal="{{ session('message') }}"></div>
    @endif

    @if (session()->has('loginError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('loginError') }}
        </div>
    @endif
    <div class="container card  p-4">


        <ol class="breadcrumb col-lg-12">
            @if (Str::length(Auth::guard('dosen')->user()) > 0)
                @if (Auth::guard('dosen')->user()->role_id == 5 ||
                        Auth::guard('dosen')->user()->role_id == 6 ||
                        Auth::guard('dosen')->user()->role_id == 7 ||
                        Auth::guard('dosen')->user()->role_id == 8 ||
                        Auth::guard('dosen')->user()->role_id == 9 ||
                        Auth::guard('dosen')->user()->role_id == 10 ||
                        Auth::guard('dosen')->user()->role_id == 11)
                    <li><a href="/prodi/kp-skripsi/seminar" class="breadcrumb-item active fw-bold text-success px-1">Seminar
                            (<span>{{ $jml_seminar_kp + $jml_sempro + $jml_sidang }}</span>) </a></li>

                    <span class="px-2">|</span>
                    <li><a href="/kerja-praktek" class="px-1">Kerja Praktek (<span>{{ $jml_prodi_kp }}</span>)</a></li>

                    <span class="px-2">|</span>
                    <li><a href="/skripsi" class="px-1">Skripsi (<span>{{ $jml_prodi_skripsi }}</span>)</a></li>


                    <span class="px-2">|</span>
                    <li><a href="/prodi/riwayat" class="px-1">Riwayat
                            (<span>{{ $jml_riwayat_prodi_kp + $jml_riwayat_prodi_skripsi + $jml_riwayat_seminar_kp + $jml_riwayat_sempro + $jml_riwayat_skripsi }}</span>)</a>
                    </li>
                    <span class="px-2">|</span>
                    <li><a href="/statistik" class="px-1">Statistik (All)</a></li>
                @endif
            @endif


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
                    @if (Str::length(Auth::guard('dosen')->user()) > 0)
                        @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                Auth::guard('dosen')->user()->role_id == 10 ||
                                Auth::guard('dosen')->user()->role_id == 11)
                            <th class="text-center"scope="col">Aksi</th>
                        @endif
                    @endif
                </tr>
            </thead>
            <tbody>

                @foreach ($penjadwalan_kps as $kp)
                    
                        <tr>
                            <td class="text-center">{{ $kp->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 fw-bold">{{ $kp->mahasiswa->nama }}</td>
                            <td class="bg-primary text-center">{{ $kp->jenis_seminar }}</td>
                            <td class="text-center">{{ $kp->prodi->nama_prodi }}</td>
                            <td class="text-center">
                                @if($kp->tanggal != null)
                                {{ Carbon::parse($kp->tanggal)->translatedFormat('l, d F Y') }}
                                @else
                                @endif
                                </td>
                            <td class="text-center">{{ $kp->waktu }}</td>
                            <td class="text-center">{{ $kp->lokasi }}</td>
                            <td class="text-center">
                                <p>{{ $kp->pembimbing->nama_singkat }}</p>
                            </td>
                            <td class="text-center">
                                <p>{{ $kp->penguji->nama_singkat }}</p>
                            </td>
                            @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                        Auth::guard('dosen')->user()->role_id == 10 ||
                                        Auth::guard('dosen')->user()->role_id == 11)
                                    <td class="text-center">
                                        <a href="/form-kp/edit/koordinator/{{ Crypt::encryptString($kp->id) }}"
                                            class="badge bg-warning mt-2 p-2"><i class="fas fa-pen"></i></a>
                                    </td>
                                @endif
                            @endif
                        </tr>
                   
                @endforeach


                @foreach ($penjadwalan_sempros as $sempro)
                    
                        <tr>
                            <td class="text-center">{{ $sempro->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 fw-bold">{{ $sempro->mahasiswa->nama }}</td>
                            <td class="bg-success text-center">{{ $sempro->jenis_seminar }}</td>
                            <td class="text-center">{{ $sempro->prodi->nama_prodi }}</td>
                            @if ($sempro->tanggal == !null)
                                <td class="text-center">{{ Carbon::parse($sempro->tanggal)->translatedFormat('l, d F Y') }}
                                </td>
                            @else
                                <td class="text-center"></td>
                            @endif
                            <td class="text-center">{{ $sempro->waktu }}</td>
                            <td class="text-center">{{ $sempro->lokasi }}</td>
                            <td class="text-center">
                                @if ($sempro->pembimbingsatu == !null)
                                <p>1. {{ $sempro->pembimbingsatu->nama_singkat }}</p>
                                @endif
                                @if ($sempro->pembimbingdua == !null)
                                    <p>2. {{ $sempro->pembimbingdua->nama_singkat }}</p>
                                @endif
                            </td>
                            @if ($sempro->pengujisatu == !null || $sempro->pengujisatu == !null || $sempro->pengujitiga == !null)
                                <td class="text-center">
                                    <p>1. {{ $sempro->pengujisatu->nama_singkat }}</p>
                                    @if ($sempro->pengujidua == !null)
                                        <p>2. {{ $sempro->pengujidua->nama_singkat }}</p>
                                    @endif
                                    @if ($sempro->pengujitiga == !null)
                                        <p>3. {{ $sempro->pengujitiga->nama_singkat }}</p>
                                    @endif
                                </td>
                            @else
                                <td class="text-center"></td>
                            @endif
                            @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                        Auth::guard('dosen')->user()->role_id == 10 ||
                                        Auth::guard('dosen')->user()->role_id == 11)
                                    <td class="text-center">
                                        <a href="/form-sempro/edit/koordinator/{{ Crypt::encryptString($sempro->id) }}"
                                            class="badge bg-warning p-2"> <i class="fas fa-pen"></i><a>
                                    </td>
                                @endif
                            @endif
                        </tr>
                  
                @endforeach

                @foreach ($penjadwalan_skripsis as $skripsi)
                   
                        <tr>
                            <td class="text-center">{{ $skripsi->mahasiswa->nim }}</td>
                            <td class="text-left pl-3 pr-1 fw-bold">{{ $skripsi->mahasiswa->nama }}</td>
                            <td class="bg-warning text-center">{{ $skripsi->jenis_seminar }}</td>
                            <td class="text-center">{{ $skripsi->prodi->nama_prodi }}</td>
                            <td class="text-center">{{ Carbon::parse($skripsi->tanggal)->translatedFormat('l, d F Y') }}
                            </td>
                            <td class="text-center">{{ $skripsi->waktu }}</td>
                            <td class="text-center">{{ $skripsi->lokasi }}</td>
                            <td class="text-center">
                                @if ($skripsi->pembimbingsatu == !null)
                                <p>1. {{ $skripsi->pembimbingsatu->nama_singkat }}</p>
                                @endif
                                @if ($skripsi->pembimbingdua == !null)
                                    <p>2. {{ $skripsi->pembimbingdua->nama_singkat }}</p>
                                @endif
                            </td>
                            @if ($skripsi->pengujisatu == !null || $skripsi->pengujisatu == !null || $skripsi->pengujitiga == !null)
                                <td class="text-center">
                                    <p>1. {{ $skripsi->pengujisatu->nama_singkat }}</p>
                                    @if ($skripsi->pengujidua == !null)
                                        <p>2. {{ $skripsi->pengujidua->nama_singkat }}</p>
                                    @endif
                                    @if ($skripsi->pengujitiga == !null)
                                        <p>3. {{ $skripsi->pengujitiga->nama_singkat }}</p>
                                    @endif
                                </td>
                            @else
                                <td class="text-center"></td>
                            @endif

                            @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                        Auth::guard('dosen')->user()->role_id == 10 ||
                                        Auth::guard('dosen')->user()->role_id == 11)
                                    <td class="text-center">
                                        <a href="/form-skripsi/edit/koordinator/{{ Crypt::encryptString($skripsi->id) }}"
                                            class="badge bg-warning p-2"> <i class="fas fa-pen"></i><a>
                                    </td>
                                @endif
                            @endif
                        </tr>
                
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

@push('scripts')
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 2000);
    </script>
@endpush()

@push('scripts')
    <script>
        const swal = $('.swal').data('swal');
        if (swal) {
            Swal.fire({
                title: 'Berhasil',
                text: swal,
                confirmButtonColor: '#28A745',
                icon: 'success'
            })
        }
    </script>
@endpush()


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const waitingApprovalCount = {!! json_encode($jml_seminar_kp + $jml_sempro + $jml_sidang) !!};
            if (waitingApprovalCount > 0) {
                Swal.fire({
                    title: 'Ini adalah halaman Jadwal Seminar',
                    html: `Ada <strong class="text-info"> ${waitingApprovalCount} Mahasiswa</strong> akan melaksanakan seminar.`,
                    icon: 'info',
                    showConfirmButton: true,
                    confirmButtonColor: '#28a745',
                });
            } else {
                Swal.fire({
                    title: 'Ini adalah halaman Jadwal Seminar',
                    html: `Belum ada mahasiswa yang akan melaksanakan seminar.`,
                    icon: 'info',
                    showConfirmButton: true,
                    confirmButtonColor: '#28a745',
                });
            }
        });
    </script>
@endpush()
