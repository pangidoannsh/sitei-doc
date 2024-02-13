@extends('layouts.main')

@section('title')
    SITEI | Edit Jadwal Seminar KP
@endsection

@section('sub-title')
    Edit Jadwal Seminar KP
@endsection

@section('content')



    @if (Str::length(Auth::guard('web')->user()) > 0)
        @if (Auth::guard('web')->user()->role_id == 2 ||
                Auth::guard('web')->user()->role_id == 3 ||
                Auth::guard('web')->user()->role_id == 4)
            <div class="container">
                <a href="/form" class="btn btn-success mb-4"><i class="fas fa-arrow-left fa-xs"></i> Kembali</a>
            </div>
            <div class="container">
                <form action="/form-kp/edit/{{ $kp->id }}" method="POST">
                    @method('put')
                    @csrf

                    <div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3 field">
                                    <label for="mahasiswa_nim" class="form-label">Mahasiswa</label>
                                    <input type="hidden" class="form-control" name="mahasiswa_nim"
                                        value="{{ old('mahasiswa_nim', $kp->mahasiswa->nim ?? '') }}" readonly>
                                    <input class="form-control disable" value="{{ $kp->mahasiswa->nama }}" readonly>
                                    @error('mahasiswa_nim')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                @if (Str::length(Auth::guard('web')->user()) > 0)
                                    @if (Auth::guard('web')->user()->role_id == 2 ||
                                            Auth::guard('web')->user()->role_id == 3 ||
                                            Auth::guard('web')->user()->role_id == 4)
                                        <div class="mb-3 field">
                                            <label for="prodi_id" class="form-label">Program Studi</label>
                                            <input type="hidden" name="prodi_id" class="form-control"
                                                value="{{ old('prodi_id', $kp->prodi_id ?? '') }}" readonly>
                                            <input class="form-control disable" value="{{ $kp->prodi->nama_prodi }}"
                                                readonly>
                                            @error('prodi_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    @endif
                                @endif

                                @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                    @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                            Auth::guard('dosen')->user()->role_id == 10 ||
                                            Auth::guard('dosen')->user()->role_id == 11)
                                        <div class="mb-3 field">
                                            <label for="prodi_id" class="form-label">Program Studi</label>
                                            <input type="hidden" name="prodi_id" class="form-control"
                                                value="{{ old('prodi_id', $kp->prodi_id ?? '') }}" readonly>
                                            <input class="form-control disable" value="{{ $kp->prodi->nama_prodi }}"
                                                readonly>
                                            @error('prodi_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    @endif
                                @endif


                                <div class="mb-3 field">
                                    <label class="form-label">Judul Laporan Kerja Praktek</label>
                                    <input type="text" name="judul_kp"
                                        class="form-control @error('judul_kp') is-invalid @enderror"
                                        value="{{ old('judul_kp', $kp->judul_kp) }}" readonly>
                                    @error('judul_kp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 field">
                                    <label class="form-label">Ruangan</label>
                                    <input type="text" name="lokasi"
                                        class="form-control @error('lokasi') is-invalid @enderror"
                                        value="{{ old('lokasi', $kp->lokasi) }}" required>
                                    @error('lokasi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-3 field">
                                    <label for="pembimbing_nip" class="form-label">Pembimbing</label>
                                    <input type="hidden" name="pembimbing_nip" class="form-control"
                                        value="{{ old('pembimbing_nip', $kp->pembimbing_nip ?? '') }}" readonly>
                                    <input class="form-control disable" value="{{ $kp->pembimbing->nama }}" readonly>
                                    @error('pembimbing_nip')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 field">
                                    <label for="penguji_nip" class="form-label">Penguji</label>
                                    <select name="penguji_nip" id="penguji"
                                        class="form-select @error('penguji_nip') is-invalid @enderror">
                                        <option value="">-Pilih-</option>
                                        @foreach ($dosens as $dosen)
                                            <option value="{{ $dosen->nip }}"
                                                {{ old('penguji_nip', $kp->penguji_nip) == $dosen->nip ? 'selected' : null }}>
                                                {{ $dosen->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('penguji_nip')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 field">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal', $kp->tanggal) }}" required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 field">
                                    <label class="form-label">Waktu</label>
                                    <input type="time" name="waktu"
                                        class="form-control @error('waktu') is-invalid @enderror"
                                        value="{{ old('waktu', $kp->waktu) }}" required>
                                    @error('waktu')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                @if ($kpp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI')
                                    <a href="#ModalApprove" data-toggle="modal"
                                        class="btn mt-4 btn-lg btn-success float-right">Jadwalkan</a>
                                    <div class="modal fade"id="ModalApprove">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content shadow-sm">
                                                <div class="modal-body">
                                                    <div class="container px-5 pt-5 pb-2">
                                                        <h3 class="text-center">Apakah Anda Yakin?</h3>
                                                        <p class="text-center">Status Mahasiswa akan di Jadwalkan Seminar
                                                            KP.</p>
                                                        <div class="row text-center">
                                                            <div class="col-3">
                                                            </div>
                                                            <div class="col-3">
                                                                <button type="button" class="btn p-2 px-3 btn-secondary"
                                                                    data-dismiss="modal">Tidak</button>
                                                            </div>
                                                            <div class="col-3">
                                                                <button type="submit"
                                                                    class="btn p-2 px-3 btn-success float-right">Ya</button>
                                                            </div>
                                                            <div class="col-3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <button type="submit"
                                        class="btn btn-lg btn-success float-right mt-4">Perbarui</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        @endif
    @endif

    @if (Str::length(Auth::guard('dosen')->user()) > 0)
        @if (Auth::guard('dosen')->user()->role_id == 9 ||
                Auth::guard('dosen')->user()->role_id == 10 ||
                Auth::guard('dosen')->user()->role_id == 11)
            <div class="container">
                <a href="/prodi/kp-skripsi/seminar" class="btn btn-success mb-4"><i class="fas fa-arrow-left fa-xs"></i>
                    Kembali</a>
            </div>

            <div class="container">
                <form action="/form-kp/edit/koordinator/{{ $kp->id }}" method="POST">
                    @method('put')
                    @csrf

                    <div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3 field">
                                    <label for="mahasiswa_nim" class="form-label">Mahasiswa</label>
                                    <input type="hidden" class="form-control" name="mahasiswa_nim"
                                        value="{{ old('mahasiswa_nim', $kp->mahasiswa->nim ?? '') }}" readonly>
                                    <input class="form-control disable" value="{{ $kp->mahasiswa->nama }}" readonly>

                                    @error('mahasiswa_nim')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                @if (Str::length(Auth::guard('web')->user()) > 0)
                                    @if (Auth::guard('web')->user()->role_id == 2 ||
                                            Auth::guard('web')->user()->role_id == 3 ||
                                            Auth::guard('web')->user()->role_id == 4)
                                        <div class="mb-3 field">
                                            <label for="prodi_id" class="form-label">Program Studi</label>
                                            <input type="hidden" name="prodi_id" class="form-control"
                                                value="{{ old('prodi_id', $kp->prodi_id ?? '') }}" readonly>
                                            <input class="form-control disable" value="{{ $kp->prodi->nama_prodi }}"
                                                readonly>

                                            @error('prodi_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    @endif
                                @endif

                                @if (Str::length(Auth::guard('dosen')->user()) > 0)
                                    @if (Auth::guard('dosen')->user()->role_id == 9 ||
                                            Auth::guard('dosen')->user()->role_id == 10 ||
                                            Auth::guard('dosen')->user()->role_id == 11)
                                        <div class="mb-3 field">
                                            <label for="prodi_id" class="form-label">Program Studi</label>
                                            <input type="hidden" name="prodi_id" class="form-control"
                                                value="{{ old('prodi_id', $kp->prodi_id ?? '') }}" readonly>
                                            <input class="form-control disable" value="{{ $kp->prodi->nama_prodi }}"
                                                readonly>


                                            @error('prodi_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    @endif
                                @endif


                                 <div class="mb-3 field">
                                    <label class="form-label">Judul Laporan Kerja Praktek</label>
                                    <input type="text" name="judul_kp"
                                        class="form-control @error('judul_kp') is-invalid @enderror"
                                        value="{{ old('judul_kp', $kp->judul_kp) }}" readonly>
                                    @error('judul_kp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 field">
                                    <label class="form-label">Ruangan</label>
                                    <input type="text" name="lokasi"
                                        class="form-control @error('lokasi') is-invalid @enderror"
                                        value="{{ old('lokasi', $kp->lokasi) }}" required>
                                    @error('lokasi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-3 field">
                                    <label for="pembimbing_nip" class="form-label">Pembimbing</label>
                                    <input type="hidden" name="pembimbing_nip" class="form-control"
                                        value="{{ old('pembimbing_nip', $kp->pembimbing_nip ?? '') }}" readonly>
                                    <input class="form-control disable" value="{{ $kp->pembimbing->nama }}" readonly>
                                    @error('pembimbing_nip')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 field">
                                    <label for="penguji_nip" class="form-label">Penguji</label>
                                    <select name="penguji_nip" id="penguji"
                                        class="form-select @error('penguji_nip') is-invalid @enderror">
                                        <option value="">-Pilih-</option>
                                        @foreach ($dosens as $dosen)
                                            <option value="{{ $dosen->nip }}"
                                                {{ old('penguji_nip', $kp->penguji_nip) == $dosen->nip ? 'selected' : null }}>
                                                {{ $dosen->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('penguji_nip')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 field">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal', $kp->tanggal) }}" required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 field">
                                    <label class="form-label">Waktu</label>
                                    <input type="time" name="waktu"
                                        class="form-control @error('waktu') is-invalid @enderror"
                                        value="{{ old('waktu', $kp->waktu) }}" required>
                                    @error('waktu')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                        
                                     @if ($kpp->status_kp == 'DAFTAR SEMINAR KP DISETUJUI')
                                    <a href="#ModalApprove2" data-toggle="modal"
                                        class="btn mt-4 btn-lg btn-success float-right">Jadwalkan</a>
                                    <div class="modal fade"id="ModalApprove2">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content shadow-sm">
                                                <div class="modal-body">
                                                    <div class="container px-5 pt-5 pb-2">
                                                        <h3 class="text-center">Apakah Anda Yakin?</h3>
                                                        <p class="text-center">Status Mahasiswa akan di Jadwalkan Seminar
                                                            KP.</p>
                                                        <div class="row text-center">
                                                            <div class="col-3">
                                                            </div>
                                                            <div class="col-3">
                                                                <button type="button" class="btn p-2 px-3 btn-secondary"
                                                                    data-dismiss="modal">Tidak</button>
                                                            </div>
                                                            <div class="col-3">
                                                                <button type="submit"
                                                                    class="btn p-2 px-3 btn-success float-right">Ya</button>
                                                            </div>
                                                            <div class="col-3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <button type="submit"
                                        class="btn btn-lg btn-success float-right mt-4">Perbarui</button>
                                @endif
                       
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        @endif
    @endif

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
                    <a class="text-success fw-bold" formtarget="_blank" target="_blank" href="/developer/m-seprinaldi">
                        M. Seprinaldi</a><span class="text-success fw-bold">)</span></small></p>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#mhs').select2();
        });

        $(document).ready(function() {
            $('#pembimbing').select2();
        });

        $(document).ready(function() {
            $('#penguji').select2();
        });
    </script>
@endpush
