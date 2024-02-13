@extends('layouts.main')

@section('title')
    SITEI | Edit Jadwal Sidang Skripsi
@endsection

@section('sub-title')
    Edit Jadwal Sidang Skripsi
@endsection

@section('content')

    @if (Str::length(Auth::guard('web')->user()) > 0)
        @if (Auth::guard('web')->user()->role_id == 2 ||
                Auth::guard('web')->user()->role_id == 3 ||
                Auth::guard('web')->user()->role_id == 4)
            <div class="container">
                <a href="/form" class="btn btn-success mb-4"><i class="fas fa-arrow-left fa-xs"></i> Kembali</a>
            </div>

            <form action="/form-skripsi/edit/{{ $skripsi->id }}" method="POST">
                @method('put')
                @csrf
                <div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3 field">
                                <label for="mahasiswa_nim" class="form-label">Mahasiswa</label>

                                <input type="hidden" class="form-control" name="mahasiswa_nim"
                                    value="{{ old('mahasiswa_nim', $skripsi->mahasiswa->nim ?? '') }}" readonly>
                                <input class="form-control disable" value="{{ $skripsi->mahasiswa->nama }}" readonly>

                                <!-- <select name="mahasiswa_nim" id="mhs" class="form-select @error('mahasiswa_nim') is-invalid @enderror">
                        <option value="">-Pilih-</option>
                        @foreach ($mahasiswas as $mhs)
    <option value="{{ $mhs->nim }}" {{ old('mahasiswa_nim', $skripsi->mahasiswa_nim) == $mhs->nim ? 'selected' : null }}>{{ $mhs->nama }}</option>
    @endforeach
                    </select> -->
                                @error('mahasiswa_nim')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                <label for="prodi_id" class="form-label">Program Studi</label>

                                <input type="hidden" name="prodi_id" class="form-control"
                                    value="{{ old('prodi_id', $skripsi->prodi_id ?? '') }}" readonly>
                                <input class="form-control disable" value="{{ $skripsi->prodi->nama_prodi }}" readonly>

                                <!-- <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror">
                    @if (auth()->user()->role_id == 2)
    <option value="1">Teknik Elektro D3</option>
    @endif
                    @if (auth()->user()->role_id == 3)
    <option value="2">Teknik Elektro S1</option>
    @endif
                    @if (auth()->user()->role_id == 4)
    <option value="3">Teknik Informatika S1</option>
    @endif
                    </select> -->
                                @error('prodi_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                @if (auth()->user()->role_id == 2)
                                    <label class="form-label">Judul Tugas Akhir</label>
                                @endif
                                @if (auth()->user()->role_id == 3)
                                    <label class="form-label">Judul Skripsi</label>
                                @endif
                                @if (auth()->user()->role_id == 4)
                                    <label class="form-label">Judul Skripsi</label>
                                @endif
                                <input type="text" name="judul_skripsi"
                                    class="form-control @error('judul_skripsi') is-invalid @enderror"
                                    value="{{ old('judul_skripsi', $skripsi->judul_skripsi) }}" readonly>
                                @error('judul_skripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            <div class="row">
                                <div class="col">
                                    <div class="mb-3 field">
                                        <label class="form-label">Tanggal</label>
                                        <input type="date" name="tanggal"
                                            class="form-control @error('tanggal') is-invalid @enderror"
                                            value="{{ old('tanggal', $skripsi->tanggal) }}">
                                        @error('tanggal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3 field">
                                        <label class="form-label">Waktu</label>
                                        <input type="time" name="waktu"
                                            class="form-control @error('waktu') is-invalid @enderror"
                                            value="{{ old('waktu', $skripsi->waktu) }}">
                                        @error('waktu')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>



                            <div class="mb-3 field">
                                <label class="form-label">Ruangan</label>
                                <input type="text" name="lokasi"
                                    class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi',$skripsi->lokasi) }}">
                                @error('lokasi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>





                        </div>
                        <div class="col-md">
                            <div class="mb-3 field">
                                <label for="pembimbingsatu_nip" class="form-label">Pembimbing Satu</label>
                                <input type="hidden" name="pembimbingsatu_nip" class="form-control"
                                    value="{{ old('pembimbingsatu_nip', $skripsi->pembimbingsatu_nip ?? '') }}" readonly>
                                <input class="form-control disable" value="{{ $skripsi->pembimbingsatu->nama }}" readonly>

                                <!-- <select name="pembimbingsatu_nip" id="pembimbing1" class="form-select @error('pembimbingsatu_nip') is-invalid @enderror">
                        <option value="">-Pilih-</option>
                        @foreach ($dosens as $dosen)
    <option value="{{ $dosen->nip }}" {{ old('pembimbingsatu_nip', $skripsi->pembimbingsatu_nip) == $dosen->nip ? 'selected' : null }}>{{ $dosen->nama }}</option>
    @endforeach
                    </select> -->
                                @error('pembimbingsatu_nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                <label for="pembimbingdua_nip" class="form-label">Pembimbing Dua</label>
                                <input type="hidden" name="pembimbingdua_nip" class="form-control"
                                    value="{{ old('pembimbingdua_nip', $skripsi->pembimbingdua_nip ?? '') }}" readonly>
                                <input class="form-control disable"
                                    value="{{ $skripsi->pembimbingdua_nip != null ? $skripsi->pembimbingdua->nama : '' }}"
                                    readonly>

                                <!-- <select name="pembimbingdua_nip" id="pembimbing2" class="form-select @error('pembimbingdua_nip') is-invalid @enderror">
                        <option value="1">-Pilih-</option>
                        @foreach ($dosens as $dosen)
    <option value="{{ $dosen->nip }}" {{ old('pembimbingdua_nip', $skripsi->pembimbingdua_nip) == $dosen->nip ? 'selected' : null }}>{{ $dosen->nama }}</option>
    @endforeach
                    </select> -->
                                @error('pembimbingdua_nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                <label for="pengujisatu_nip" class="form-label">Penguji Satu</label>
                                <select name="pengujisatu_nip" id="penguji1"
                                    class="form-select @error('pengujisatu_nip') is-invalid @enderror">
                                    <option value="">-Pilih-</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nip }}"
                                            {{ old('pengujisatu_nip', $skripsi->pengujisatu_nip) == $dosen->nip ? 'selected' : null }}>
                                            {{ $dosen->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pengujisatu_nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                <label for="pengujidua_nip" class="form-label">Penguji Dua</label>
                                <select name="pengujidua_nip" id="penguji2"
                                    class="form-select @error('pengujidua_nip') is-invalid @enderror">
                                    <option value="">-Pilih-</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nip }}"
                                            {{ old('pengujidua_nip', $skripsi->pengujidua_nip) == $dosen->nip ? 'selected' : null }}>
                                            {{ $dosen->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pengujidua_nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                <label for="pengujitiga_nip" class="form-label">Penguji Tiga</label>
                                <select name="pengujitiga_nip" id="penguji3"
                                    class="form-select @error('pengujitiga_nip') is-invalid @enderror">
                                    <option value="">-Pilih-</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nip }}"
                                            {{ old('pengujitiga_nip', $skripsi->pengujitiga_nip) == $dosen->nip ? 'selected' : null }}>
                                            {{ $dosen->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pengujitiga_nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            @if ($skripsip->status_skripsi == 'DAFTAR SIDANG DISETUJUI')
                                <a href="#ModalApprove" data-toggle="modal"
                                    class="btn mt-4 btn-lg btn-success float-right">Jadwalkan</a>
                                <div class="modal fade"id="ModalApprove">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content shadow-sm">
                                            <div class="modal-body">
                                                <div class="container text-center px-5 pt-5 pb-2">
                                                    <h3 class="text-center">Apakah Anda Yakin?</h3>
                                                    <p class="text-center">Status Mahasiswa akan di Jadwalkan Sidang
                                                        Skripsi.</p>
                                                    <div class="row text-center">

                                                        <div class="col-6 text-end">
                                                            <button type="button" class="btn p-2 px-3 btn-secondary"
                                                                data-dismiss="modal">Tidak</button>
                                                        </div>
                                                        <div class="col-6 text-start">
                                                            <button type="submit"
                                                                class="btn p-2 px-3 btn-success ">Ya</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button type="submit" class="btn btn-lg btn-success float-right mt-4">Perbarui</button>
                            @endif

                        </div>
                    </div>
                </div>
            </form>
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

            <form action="/form-skripsi/edit/koordinator/{{ $skripsi->id }}" method="POST">
                @method('put')
                @csrf
                <div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3 field">
                                <label for="mahasiswa_nim" class="form-label">Mahasiswa</label>

                                <input type="hidden" class="form-control" name="mahasiswa_nim"
                                    value="{{ old('mahasiswa_nim', $skripsi->mahasiswa->nim ?? '') }}" readonly>
                                <input class="form-control disable" value="{{ $skripsi->mahasiswa->nama }}" readonly>

                                <!-- <select name="mahasiswa_nim" id="mhs" class="form-select @error('mahasiswa_nim') is-invalid @enderror">
                        <option value="">-Pilih-</option>
                        @foreach ($mahasiswas as $mhs)
    <option value="{{ $mhs->nim }}" {{ old('mahasiswa_nim', $skripsi->mahasiswa_nim) == $mhs->nim ? 'selected' : null }}>{{ $mhs->nama }}</option>
    @endforeach
                    </select> -->
                                @error('mahasiswa_nim')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                <label for="prodi_id" class="form-label">Program Studi</label>

                                <input type="hidden" name="prodi_id" class="form-control"
                                    value="{{ old('prodi_id', $skripsi->prodi_id ?? '') }}" readonly>
                                <input class="form-control disable" value="{{ $skripsi->prodi->nama_prodi }}" readonly>

                                <!-- <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror">
                    @if (auth()->user()->role_id == 2)
    <option value="1">Teknik Elektro D3</option>
    @endif
                    @if (auth()->user()->role_id == 3)
    <option value="2">Teknik Elektro S1</option>
    @endif
                    @if (auth()->user()->role_id == 4)
    <option value="3">Teknik Informatika S1</option>
    @endif
                    </select> -->
                                @error('prodi_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                @if (auth()->user()->role_id == 9)
                                    <label class="form-label">Judul Tugas Akhir</label>
                                @endif
                                @if (auth()->user()->role_id == 10)
                                    <label class="form-label">Judul Skripsi</label>
                                @endif
                                @if (auth()->user()->role_id == 11)
                                    <label class="form-label">Judul Skripsi</label>
                                @endif
                                <input type="text" name="judul_skripsi"
                                    class="form-control @error('judul_skripsi') is-invalid @enderror"
                                    value="{{ old('judul_skripsi', $skripsi->judul_skripsi) }}" readonly>
                                @error('judul_skripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            <div class="row">
                                <div class="col">
                                    <div class="mb-3 field">
                                        <label class="form-label">Tanggal</label>
                                        <input type="date" name="tanggal"
                                            class="form-control @error('tanggal') is-invalid @enderror"
                                            value="{{ old('tanggal', $skripsi->tanggal) }}">
                                        @error('tanggal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3 field">
                                        <label class="form-label">Waktu</label>
                                        <input type="time" name="waktu"
                                            class="form-control @error('waktu') is-invalid @enderror"
                                            value="{{ old('waktu', $skripsi->waktu) }}">
                                        @error('waktu')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="mb-3 field">
                                <label class="form-label">Ruangan</label>
                                <input type="text" name="lokasi"
                                    class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi',$skripsi->lokasi) }}">
                                @error('lokasi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>





                        </div>
                        <div class="col-md">
                            <div class="mb-3 field">
                                <label for="pembimbingsatu_nip" class="form-label">Pembimbing Satu</label>
                                <input type="hidden" name="pembimbingsatu_nip" class="form-control"
                                    value="{{ old('pembimbingsatu_nip', $skripsi->pembimbingsatu_nip ?? '') }}" readonly>
                                <input class="form-control disable" value="{{ $skripsi->pembimbingsatu->nama }}"
                                    readonly>

                                <!-- <select name="pembimbingsatu_nip" id="pembimbing1" class="form-select @error('pembimbingsatu_nip') is-invalid @enderror">
                        <option value="">-Pilih-</option>
                        @foreach ($dosens as $dosen)
    <option value="{{ $dosen->nip }}" {{ old('pembimbingsatu_nip', $skripsi->pembimbingsatu_nip) == $dosen->nip ? 'selected' : null }}>{{ $dosen->nama }}</option>
    @endforeach
                    </select> -->
                                @error('pembimbingsatu_nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                <label for="pembimbingdua_nip" class="form-label">Pembimbing Dua</label>
                                <input type="hidden" name="pembimbingdua_nip" class="form-control"
                                    value="{{ old('pembimbingdua_nip', $skripsi->pembimbingdua_nip ?? '') }}" readonly>
                                <input class="form-control disable"
                                    value="{{ $skripsi->pembimbingdua_nip != null ? $skripsi->pembimbingdua->nama : '' }}"
                                    readonly>

                                <!-- <select name="pembimbingdua_nip" id="pembimbing2" class="form-select @error('pembimbingdua_nip') is-invalid @enderror">
                        <option value="1">-Pilih-</option>
                        @foreach ($dosens as $dosen)
    <option value="{{ $dosen->nip }}" {{ old('pembimbingdua_nip', $skripsi->pembimbingdua_nip) == $dosen->nip ? 'selected' : null }}>{{ $dosen->nama }}</option>
    @endforeach
                    </select> -->
                                @error('pembimbingdua_nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                <label for="pengujisatu_nip" class="form-label">Penguji Satu</label>
                                <select name="pengujisatu_nip" id="penguji1"
                                    class="form-select @error('pengujisatu_nip') is-invalid @enderror">
                                    <option value="">-Pilih-</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nip }}"
                                            {{ old('pengujisatu_nip', $skripsi->pengujisatu_nip) == $dosen->nip ? 'selected' : null }}>
                                            {{ $dosen->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pengujisatu_nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                <label for="pengujidua_nip" class="form-label">Penguji Dua</label>
                                <select name="pengujidua_nip" id="penguji2"
                                    class="form-select @error('pengujidua_nip') is-invalid @enderror">
                                    <option value="">-Pilih-</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nip }}"
                                            {{ old('pengujidua_nip', $skripsi->pengujidua_nip) == $dosen->nip ? 'selected' : null }}>
                                            {{ $dosen->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pengujidua_nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 field">
                                <label for="pengujitiga_nip" class="form-label">Penguji Tiga</label>
                                <select name="pengujitiga_nip" id="penguji3"
                                    class="form-select @error('pengujitiga_nip') is-invalid @enderror">
                                    <option value="1">-Pilih-</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nip }}"
                                            {{ old('pengujitiga_nip', $skripsi->pengujitiga_nip) == $dosen->nip ? 'selected' : null }}>
                                            {{ $dosen->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pengujitiga_nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                                @if ($skripsip->status_skripsi == 'DAFTAR SIDANG DISETUJUI')
                                <a href="#ModalApprove" data-toggle="modal"
                                    class="btn mt-4 btn-lg btn-success float-right">Jadwalkan</a>
                                <div class="modal fade"id="ModalApprove">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content shadow-sm">
                                            <div class="modal-body">
                                                <div class="container text-center px-5 pt-5 pb-2">
                                                    <h3 class="text-center">Apakah Anda Yakin?</h3>
                                                    <p class="text-center">Status Mahasiswa akan di Jadwalkan Sidang
                                                        Skripsi.</p>
                                                    <div class="row text-center">

                                                        <div class="col-6 text-end">
                                                            <button type="button" class="btn p-2 px-3 btn-secondary"
                                                                data-dismiss="modal">Tidak</button>
                                                        </div>
                                                        <div class="col-6 text-start">
                                                            <button type="submit"
                                                                class="btn p-2 px-3 btn-success ">Ya</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button type="submit" class="btn btn-lg btn-success float-right mt-4">Perbarui</button>
                            @endif
                 

                        </div>
                    </div>
                </div>
            </form>
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
            $('#pembimbing1').select2();
        });

        $(document).ready(function() {
            $('#pembimbing2').select2();
        });


        $(document).ready(function() {
            $('#penguji1').select2();
        });

        $(document).ready(function() {
            $('#penguji2').select2();
        });


        $(document).ready(function() {
            $('#penguji3').select2();
        });
    </script>
@endpush
