@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    Kerja Praktek | SIA ELEKTRO
@endsection

@section('sub-title')
    Kerja Praktek
@endsection

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif



    <div class="container-fluid">

        @if (Str::length(Auth::guard('mahasiswa')->user()) > 0)
            <div class="card card-timeline px-2 border-none">
                @foreach ($pendaftaran_kp as $kp)
                    <h5 class="text-center">
                        <div class="row text-center justify-content-center mb-5">
                            <div class="col-xl-6 col-lg-8">
                                <h2 class="font-weight-bold mt-3">Timeline Progress Kerja Praktek</h2>
                                <!-- <p class="text-muted">We’re very proud of the path we’ve taken. Explore the history that made us the company we are today.</p> -->
                            </div>
                        </div>

                        <ul class="bs4-order-tracking">
                            <li class="step active">
                                <div>
                                    <i class="fas fa-check"></i>
                                </div>
                                <p class="mt-3"> USULAN </p>
                                <span class="mt-3 "> Tanggal disetujui <br></span>
                                <span
                                    class="mt-3 text-bold">{{ Carbon::parse($kp->tgl_disetujui_usulan)->translatedFormat('l, d F Y') }}</span>
                            </li>

                            <li class="step active ">
                                <div><i class="fas fa-check"></i>
                                </div>
                                <p class="mt-3"> PERMOHONAN KP </p>
                                <span class="mt-3 "> Tanggal disetujui <br></span>
                                <span
                                    class="mt-3 text-bold">{{ Carbon::parse($kp->tgl_disetujui_permohonan)->translatedFormat('l, d F Y') }}</span>
                            </li>

                            <li class="step active">
                                <div><i class="fas fa-check "></i>
                                </div>
                                <p class="mt-3"> USULAN KP</p>
                                <span class="mt-3 "> Tanggal disetujui <br></span>
                                <span class="mt-3 text-bold">
                                    {{ Carbon::parse($kp->tgl_disetujui_balasan)->translatedFormat('l, d F Y') }}</span>
                            </li>

                            @if ($kp->status_kp == 'USULAN SEMINAR KP')
                                <li class="step active">
                                    <div><i class="fas"> </i>
                                    </div>
                                    <p class="mt-3"> USULAN SEMINAR KP </p>
                                </li>
                                <li class="step ">
                                    <div><i class="fas"></i>
                                    </div>
                                    <p class="mt-3"> KP DI JADWALKAN </p>
                                </li>
                                <li class="step ">
                                    <div><i class="fas "></i>
                                    </div>
                                    <p class="mt-3"> SEMINAR KP SELESAI </p>
                                </li>
                                <li class="step ">
                                    <div><i class="fas "></i>
                                    </div>
                                    <p class="mt-3"> KP SELESAI </p>
                                </li>
                            @endif
                            @if ($kp->status_kp == 'SEMINAR KP DISETUJUI')
                                <li class="step active ">
                                    <div><i class="fas fa-check "> </i>
                                    </div>
                                    <p class="mt-3"> USULAN SEMINAR KP </p>
                                    <span class="mt-3 "> Tanggal disetujui <br></span>
                                    <span
                                        class="mt-3 text-bold">{{ Carbon::parse($kp->tgl_disetujui_semkp)->translatedFormat('l, d F Y') }}</span>
                                </li>
                                <li class="step ">
                                    <div><i class="fas"></i>
                                    </div>
                                    <p class="mt-3"> KP DI JADWALKAN </p>
                                </li>
                                <li class="step ">
                                    <div><i class="fas "></i>
                                    </div>
                                    <p class="mt-3"> SEMINAR KP SELESAI </p>
                                </li>
                                <li class="step ">
                                    <div><i class="fas "></i>
                                    </div>
                                    <p class="mt-3"> KP SELESAI </p>
                                </li>
                            @endif

                            @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                                <li class="step active ">
                                    <div><i class="fas fa-check "> </i>
                                    </div>
                                    <p class="mt-3"> USULAN SEMINAR KP </p>
                                    <span class="mt-3 "> Tanggal disetujui <br></span>
                                    <span
                                        class="mt-3 text-bold">{{ Carbon::parse($kp->tgl_disetujui_semkp)->translatedFormat('l, d F Y') }}</span>
                                </li>
                                <li class="step active">
                                    <div><i class="fas fa-check"></i>
                                    </div>
                                    <p class="mt-3"> KP DI JADWALKAN </p>
                                    <span class="mt-3 "> Tanggal disetujui <br></span>
                                    <span
                                        class="mt-3 text-bold">{{ Carbon::parse($kp->tgl_disetujui_jadwal)->translatedFormat('l, d F Y') }}</span>
                                </li>
                                <li class="step ">
                                    <div><i class="fas "></i>
                                    </div>
                                    <p class="mt-3"> SEMINAR KP SELESAI </p>
                                </li>
                                <li class="step ">
                                    <div><i class="fas "></i>
                                    </div>
                                    <p class="mt-3"> KP SELESAI </p>
                                </li>
                            @endif
                            @if ($kp->status_kp == 'SEMINAR KP SELESAI')
                                <li class="step active ">
                                    <div><i class="fas fa-check "> </i>
                                    </div>
                                    <p class="mt-3"> USULAN SEMINAR KP </p>
                                    <span class="mt-3 "> Tanggal disetujui <br></span>
                                    <span
                                        class="mt-3 text-bold">{{ Carbon::parse($kp->tgl_disetujui_semkp)->translatedFormat('l, d F Y') }}</span>
                                </li>
                                <li class="step active">
                                    <div><i class="fas fa-check"></i>
                                    </div>
                                    <p class="mt-3"> KP DI JADWALKAN </p>
                                    <span class="mt-3 "> Tanggal disetujui <br></span>
                                    <span
                                        class="mt-3 text-bold">{{ Carbon::parse($kp->tgl_disetujui_jadwal)->translatedFormat('l, d F Y') }}</span>
                                </li>
                                <li class="step active">
                                    <div><i class="fas fa-check"></i>
                                    </div>
                                    <p class="mt-3"> SEMINAR KP SELESAI </p>
                                    <span class="mt-3 "> Tanggal disetujui <br></span>
                                    <span
                                        class="mt-3 text-bold">{{ Carbon::parse($kp->tgl_disetujui_kpti_10)->translatedFormat('l, d F Y') }}</span>
                                </li>
                                <li class="step ">
                                    <div><i class="fas "></i>
                                    </div>
                                    <p class="mt-3"> KP SELESAI </p>
                                </li>
                            @endif


                        </ul>
            </div>

            <div class="container-fluid">


                @if ($kp->status_kp == 'SEMINAR KP SELESAI')
                    <div class="mt-2">
                        <a href="/kpti10-kp/create/{{ $kp->id }}" class="btn mahasiswa btn-success mb-3">Upload
                            KPTI/TE-10 / Bukti Penyerahan Laporan</a>
                    </div>
                @endif
                @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                    <div class="mt-2">
                        <a href="/jadwal" class="btn mahasiswa btn-success mb-3">Cek jadwal</a>
                    </div>
                @endif
        @endforeach
    </div>
    @endif

    @foreach ($pendaftaran_kp as $kp)
        <div class="card card-timeline px-2 border-none">

            <div class="row  justify-content mb-2">

            </div>

            <div class="row">

                <div class="col mb-3">

                    <ol class=" mt-2">
                        <a href="/usulan-semkp/detail/{{ $kp->id }}" class="badge bg-success p-2 fas fa-eye"> Lihat
                            Detail</a>
                        <h5 class="font-weight-bold mt-3">Status Kerja Praktek</h5>
                        @if ($kp->status_kp == 'USULAN SEMINAR KP')
                            <li class="list-group-item badge bg-secondary p-3 d-flex text-center ">
                                <div class="ms-2 me-auto text-center gridratakiri">
                                    <!-- <div class="fw-bold ">NIM</div> -->
                                    <span class="fw-bold text-center ">{{ $kp->status_kp }}</span>
                                </div>
                            </li>
                        @endif
                        @if ($kp->status_kp == 'SEMINAR KP DISETUJUI')
                            <li class="list-group-item badge bg-info p-3 d-flex text-center ">
                                <div class="ms-2 me-auto text-center gridratakiri">
                                    <!-- <div class="fw-bold ">NIM</div> -->
                                    <span class="fw-bold text-center ">{{ $kp->status_kp }}</span>
                                </div>
                            </li>
                        @endif
                        @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                            <li class="list-group-item badge bg-info p-3 d-flex text-center ">
                                <div class="ms-2 me-auto text-center gridratakiri">
                                    <!-- <div class="fw-bold ">NIM</div> -->
                                    <span class="fw-bold text-center ">{{ $kp->status_kp }}</span>
                                </div>
                            </li>
                        @endif
                        @if ($kp->status_kp == 'SEMINAR KP SELESAI')
                            <li class="list-group-item badge bg-info p-3 d-flex text-center ">
                                <div class="ms-2 me-auto text-center gridratakiri">
                                    <!-- <div class="fw-bold ">NIM</div> -->
                                    <span class="fw-bold text-center ">{{ $kp->status_kp }}</span>
                                </div>
                            </li>
                        @endif


                        <h5 class="font-weight-bold mt-3">Keterangan</h5>
                        <li class="list-group-item mt-3 d-flex justify-content-center align-items-start">
                            <div class="ms-2 me-auto gridratakiri">

                                <span class="fw-bold ">{{ $kp->keterangan }}</span>
                            </div>
                        </li>
                    </ol>

                </div>


                <div class="col-md ">
                    @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                        @foreach ($penjadwalan_kp as $kp)
                            @if ($kp->mahasiswa_nim == Auth::user()->nim)
                                @if ($kp !== null)
                                    <ol class="mt-5">
                                        <h5 class="font-weight-bold mt-4">Jadwal Seminar</h5>
                                        <li class="list-group-item mt-2 d-flex justify-content-center align-items-start">
                                            <div class="ms-2 me-auto gridratakiri">
                                                <span class="mr-2 ">Tanggal :</span>
                                                <span
                                                    class="fw-bold ">{{ Carbon::parse($kp->tanggal)->translatedFormat('l, d F Y') }}</span>
                                            </div>
                                            <div class="ms-2 me-auto gridratakiri">
                                                <span class="mr-2">Jam :</span>
                                                <span
                                                    class="fw-bold ">{{ Carbon::parse($kp->waktu)->translatedFormat('H:i') }}
                                                    wib</span>
                                            </div>
                                        </li>
                                        <h5 class="font-weight-bold mt-3">Lokasi</h5>
                                        <li class="list-group-item mt-3 d-flex justify-content-center align-items-start">
                                            <div class="ms-2 me-auto gridratakiri">
                                                <!-- <span class="mr-2">Tanggal</span> -->
                                                <span class="fw-bold ">{{ $kp->lokasi }}</span>
                                            </div>
                                        </li>
                                    </ol>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
    @endforeach
    </div>

    </div>




    </div>




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
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 2000);
    </script>
@endpush()
