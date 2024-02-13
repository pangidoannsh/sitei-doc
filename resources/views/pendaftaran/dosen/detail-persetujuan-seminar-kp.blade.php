@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Detail Mahasiswa
@endsection

@section('sub-title')
    Detail Mahasiswa
@endsection

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif




    <section class="mb-5">
        <div class="container">
            @if (Str::length(Auth::guard('dosen')->user()) > 0)
                <a href="/persetujuan-kp-skripsi" class="btn btn-success py-1 px-2 mb-3"><i
                        class="fas fa-arrow-left fa-xs"></i> Kembali <a>
            @endif
            @if (Str::length(Auth::guard('web')->user()) > 0)
                <a href="/persetujuan/admin/index" class="btn btn-success py-1 px-2 mb-3"><i
                        class="fas fa-arrow-left fa-xs"></i> Kembali <a>
            @endif
        </div>

        @foreach ($pendaftaran_kp as $kp)
            <div class="container">
                <div class="row rounded shadow-sm">
                    <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                        <h5 class="text-bold">Mahasiswa</h5>
                        <hr>
                        <p class="card-title text-secondary text-sm ">Nama</p>
                        <p class="card-text text-start">{{ $kp->mahasiswa->nama }}</p>
                        <p class="card-title text-secondary text-sm ">NIM</p>
                        <p class="card-text text-start">{{ $kp->mahasiswa->nim }}</p>
                        <p class="card-title text-secondary text-sm ">Program Studi</p>
                        <p class="card-text text-start">{{ $kp->mahasiswa->prodi->nama_prodi }}</p>
                        <p class="card-title text-secondary text-sm ">Konsentrasi</p>
                        <p class="card-text text-start">{{ $kp->mahasiswa->konsentrasi->nama_konsentrasi }}</p>
                    </div>
                    <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                        <h5 class="text-bold">Dosen Pembimbing</h5>
                        <hr>
                        <p class="card-title text-secondary text-sm">Nama</p>
                        <p class="card-text text-start">{{ $kp->dosen_pembimbingkp->nama }}</p>
                    </div>
                </div>
            </div>

            <div class="container">
            <div class="row rounded shadow-sm">
                <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                    <h5 class="text-bold">Laporan KP</h5>
                    <hr>
                <p class="card-title text-secondary text-sm">Judul Laporan</p>
                    <p class="card-text text-start"><span>{{ $kp->judul_laporan ?? '-' }}</span></p>
                
                    <p class="card-title text-secondary text-sm">Nama Perusahaan/Instansi</p>
                    <p class="card-text text-start"><span>
                        @if($kp->nama_perusahaan != null)
                        {{ $kp->nama_perusahaan}}
                        @else
                        -
                        @endif
                    </span></p>

                </div>
                <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                    <h5 class="text-bold">Surat Persetujuan Permohonan KP</h5>
                    <hr>
                
                    <p class="card-title text-secondary text-sm">KPTI/TE-1 - Surat Permohonan KP</p>
                        <p class="card-text  text-start"><button onclick="window.open('/surat-permohonan-kp/{{ $kp->id }}', '_blank')" class="badge bg-dark px-2 py-1">Buka</button>
                        </p>
                        <p class="card-title text-secondary text-sm">KPTI/TE-2 - Form Permohonan KP</p>
                        <p class="card-text text-start">
                            <button onclick="window.open('/form-permohonan-kp/{{ $kp->id }}', '_blank')" class="badge bg-dark px-2 py-1">Buka</button>
                            </p>
                </div>
            </div>
        </div>


            <div class="container">
                <div class="row rounded shadow-sm">
                    <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                        <h5 class="text-bold">Data Usulan</h5>
                        <hr>
                        <p class="card-title text-secondary text-sm">Judul Laporan</p>
                        <p class="card-text text-start"> {{ $kp->judul_laporan }}</p>
                        <p class="card-title text-secondary text-sm">Laporan</p>
                        <p class="card-text  text-start"><a formtarget="_blank" target="_blank"
                                href="{{ asset('storage/' . $kp->laporan_kp) }}" class="badge bg-dark px-3 py-2">Buka</a>
                        </p>
                        <p class="card-title text-secondary text-sm">KPTI/TE-11/Bukti Mengumpulkan Syarat Pendaftaran Seminar
                            KP</p>
                        <p class="card-text  text-start"><a formtarget="_blank" target="_blank"
                                href="{{ asset('storage/' . $kp->kpti_11) }}" class="badge bg-dark px-3 py-2">Buka</a></p>
                        <p class="card-title text-secondary text-sm">STI/TE-31/Surat Persetujuan Sertifikat Pendamping</p>
                        @if($kp->sti_31 != null)
                        <p class="card-text  text-start"><a formtarget="_blank" target="_blank"
                                href="{{ asset('storage/' . $kp->sti_31) }}" class="badge bg-dark px-3 py-2">Buka</a></p>
                        @else
                        <p class="card-text text-start">-</p>
                        @endif
                    </div>
                    <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                        <h5 class="text-bold">Keterangan Pendaftaran</h5>
                        <hr>
                        <p class="card-title text-secondary text-sm">Jenis Usulan</p>
                        <p class="card-text text-start"><span>{{ $kp->jenis_usulan }}</span></p>
                        @if ($kp->status_kp == 'DAFTAR SEMINAR KP')
                            <p class="card-title text-secondary text-sm">Status KP</p>
                            <p class="card-text text-start"><span class="badge p-2 bg-secondary text-bold pr-3 pl-3"
                                    style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                        @endif
                        @if ($kp->status_kp == 'DAFTAR SEMINAR KP DITOLAK')
                            <p class="card-title text-secondary text-sm">Status KP</p>
                            <p class="card-text text-start"><span class="badge p-2 bg-danger text-bold pr-3 pl-3"
                                    style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                        @endif
                        @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                            <p class="card-title text-secondary text-sm ">Status KP</p>
                            <p class="card-text text-start"><span class="badge p-2 bg-success text-bold pr-3 pl-3"
                                    style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                        @endif
                        @if ($kp->status_kp == 'SEMINAR KP SELESAI')
                            <p class="card-title text-secondary text-sm ">Status KP</p>
                            <p class="card-text text-start"><span class="badge p-2 bg-info text-bold pr-3 pl-3"
                                    style="border-radius:20px;">{{ $kp->status_kp }}</span></p>
                        @endif
                        <p class="card-title text-secondary text-sm">Keterangan</p>
                        <p class="card-text text-start"><span>{{ $kp->keterangan }}</span></p>
                    </div>
                </div>
            </div>

            <div class="container">
                @if (Str::length(Auth::guard('web')->user()) > 0)
                    @if (Auth::guard('web')->user()->role_id == 2 ||
                            Auth::guard('web')->user()->role_id == 3 ||
                            Auth::guard('web')->user()->role_id == 4)
                        @if ($kp->status_kp == 'DAFTAR SEMINAR KP' && $kp->keterangan == 'Menunggu persetujuan Admin Prodi')
                            <div class="mb-5 mt-3 float-right">
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <button onclick="tolakSemKPAdmin()" class="btn btn-danger py-2 px-3 mb-3"
                                            data-bs-toggle="tooltip" title="Tolak">Tolak</button>
                                    </div>
                                    <div class="col">
                                        <form action="/semkp/admin/approve/{{ $kp->id }}" class="setujui-semkp-admin"
                                            method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success py-2 px-3 mb-3">Setujui</i></button>
                                        </form>
                                    </div>
                                </div>
                        @endif
                    @endif
                @endif

                @if ($kp->dosen_pembimbing_nip == Auth::user()->nip)
                    @if ($kp->status_kp == 'DAFTAR SEMINAR KP' && $kp->keterangan == 'Menunggu persetujuan Pembimbing')
                        <div class="mb-5 mt-3 float-right">
                            <div class="row row-cols-2">
                                <div class="col">
                                    <button onclick="tolakSemKPPemb()" class="btn btn-danger py-2 px-3 mb-3"
                                        data-bs-toggle="tooltip" title="Tolak">Tolak</button>
                                </div>
                                <div class="col">
                                    <form action="/usulan-semkp/pembimbing/approve/{{ $kp->id }}"
                                        class="setujui-semkp-pembimbing" method="POST">
                                        @method('put')
                                        @csrf
                                        <button class="btn btn-success py-2 px-3 mb-3">Setujui</i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if ($kp->dosen_pembimbing_nip == Auth::user()->nip)
                    @if ($kp->status_kp == 'SEMINAR KP DIJADWALKAN')
                        <div class="mb-5 mt-3 float-right">
                            <div class="row row-cols-2">
                                <div class="col">
                                    <button onclick="tolakGagalSemKPPemb()" class="btn btn-danger py-2 px-3 mb-3"
                                        data-bs-toggle="tooltip" title="Tolak">Gagal</button>
                                </div>
                                <div class="col">
                                    <form action="/selesaiseminar-kp/pembimbing/approve/{{ $kp->id }}"
                                        class="setujui-selesai-semkp-pembimbing" method="POST">
                                        @method('put')
                                        @csrf
                                        <button class="btn btn-success py-2 px-3 mb-3">Selesai</i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- APPROVAL KOORDINATOR KP  -->
                @if (Str::length(Auth::guard('dosen')->user()) > 0)
                    @if (Auth::guard('dosen')->user()->role_id == 9 ||
                            Auth::guard('dosen')->user()->role_id == 10 ||
                            Auth::guard('dosen')->user()->role_id == 11)
                        @if ($kp->status_kp == 'DAFTAR SEMINAR KP' && $kp->keterangan == 'Menunggu persetujuan Koordinator KP')
                            <div class="mb-5 mt-3 float-right">
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <button onclick="tolakSemKPKoordinator()" class="btn btn-danger py-2 px-3 mb-3"
                                            data-bs-toggle="tooltip" title="Tolak">Tolak</button>
                                    </div>
                                    <div class="col">
                                        <form action="/usulan-semkp/koordinator/approve/{{ $kp->id }}"
                                            class="setujui-semkp-koordinator" method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success py-2 px-3 mb-3">Setujui</i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
                @if (Str::length(Auth::guard('dosen')->user()) > 0)
                    @if (Auth::guard('dosen')->user()->role_id == 6 ||
                            Auth::guard('dosen')->user()->role_id == 7 ||
                            Auth::guard('dosen')->user()->role_id == 8)
                        @if ($kp->status_kp == 'DAFTAR SEMINAR KP' && $kp->keterangan == 'Menunggu persetujuan Koordinator Program Studi')
                            <div class="mb-5 mt-3 float-right">
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <button onclick="tolakSemKPKaprodi()" class="btn btn-danger py-2 px-3 mb-3"
                                            data-bs-toggle="tooltip" title="Tolak">Tolak</button>
                                    </div>
                                    <div class="col">
                                        <form action="/usulan-semkp/kaprodi/approve/{{ $kp->id }}"
                                            class="setujui-semkp-kaprodi" method="POST">
                                            @method('put')
                                            @csrf
                                            <button class="btn btn-success py-2 px-3 mb-3">Setujui</i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        @endforeach
    </section>
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

            function tolakSemKPAdmin() {
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
                        <form id="reasonForm" action="/semkp/admin/tolak/{{ $kp->id }}" method="POST">
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



            $('.setujui-semkp-pembimbing').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Seminar KP!',
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

            function tolakSemKPPemb() {
                Swal.fire({
                    title: 'Tolak Usulan Seminar KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Seminar KP',
                            html: `
                        <form id="reasonForm" action="/usulan-semkp/pembimbing/tolak/{{ $kp->id }}" method="POST">
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

            //APPROVAL
            $('.setujui-semkp-koordinator').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan Seminar KP!',
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

            function tolakSemKPKoordinator() {
                Swal.fire({
                    title: 'Tolak Usulan Seminar KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Seminar KP',
                            html: `
                        <form id="reasonForm" action="/usulan-semkp/koordinator/tolak/{{ $kp->id }}" method="POST">
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
            //APPROVAL
            $('.setujui-semkp-kaprodi').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Setujui Usulan Seminar KP!',
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

            function tolakSemKPKaprodi() {
                Swal.fire({
                    title: 'Tolak Usulan Seminar KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Tolak',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Tolak Usulan Seminar KP',
                            html: `
                        <form id="reasonForm" action="/usulan-semkp/kaprodi/tolak/{{ $kp->id }}" method="POST">
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

            $('.setujui-selesai-semkp-pembimbing').submit(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Selesai Seminar KP',
                    text: "Apakah Anda Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: 'grey',
                    confirmButtonText: 'Selesai'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.currentTarget.submit();
                    }
                })
            });

            function tolakGagalSemKPPemb() {
                Swal.fire({
                    title: 'Gagal Seminar KP',
                    text: 'Apakah Anda Yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Gagal',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Gagal Seminar KP',
                            html: `
                        <form id="reasonForm" action="/selesaiseminar-kp/pembimbing/tolak/{{ $kp->id }}" method="POST">
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
