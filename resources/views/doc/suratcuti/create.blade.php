@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Distribusi Surat & Dokumen
@endsection

@section('content')
    <div class="">
        <h2 class="text-center fw-semibold ">Formulir Surat Cuti</h2>
        <form action="{{ route('suratcuti.store') }}" method="POST" class="d-flex flex-column gap-3"
            enctype="multipart/form-data">
            @method('post')
            @csrf
            {{-- Nama --}}
            <div>
                <label for="nama" class="fw-semibold">Nama</label>
                <input type="text" class="form-control rounded-3 py-4" name="nama"
                    value="{{ Auth::guard($jenis_user == 'admin' || $jenis_user == 'plp' ? 'web' : $jenis_user)->user()->nama }}"
                    id="nama" disabled>
            </div>
            {{-- NIP --}}
            @if (Auth::guard('dosen')->check())
                <div>
                    <label for="nip" class="fw-semibold">NIP</label>
                    <input type="text" class="form-control rounded-3 py-4" name="nip"
                        value="{{ Auth::guard('dosen')->user()->nip }}" id="nip" disabled>
                </div>
            @endif
            {{-- Jabatan --}}
            @if ($jabatan)
                <div>
                    <label for="jabatan" class="fw-semibold">Jabatan</label>
                    <input type="text" class="form-control rounded-3 py-4 text-capitalize" name="jabatan"
                        value="{{ $jabatan }}" id="jabatan" disabled>
                </div>
            @endif
            {{-- Nomor Telepon --}}
            <div>
                <label for="nomor_telepon" class="fw-semibold">Nomor Telepon<span class="text-danger">*</span></label>
                <input type="tel" class="form-control rounded-3 py-4" name="nomor_telepon" id="nomor_telepon"
                    placeholder="08***" value="{{ old('nomor_telepon') }}">
            </div>
            {{-- Jenis Cuti --}}
            <div>
                <label for="jenis_cuti" class="fw-semibold">Jenis Cuti<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="jenis_cuti" id="pilih_jenis"
                        class="form-select text-secondary text-capitalize rounded-3 @error('jenis_cuti') border border-danger @enderror">
                        <option value="{{ null }}" disabled selected>Pilih Jenis Cuti</option>
                        @foreach ($jenis_cuti as $jenis)
                            <option value="{{ $jenis }}" class="text-capitalize"
                                {{ old('jenis_cuti') == $jenis ? 'selected' : '' }}>{{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_cuti')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            {{-- Alasan Cuti --}}
            <div>
                <label for="alasan_cuti" class="fw-semibold">Alasan Cuti<span class="text-danger">*</span></label>
                <textarea class="form-control rounded-3 py-4 @error('alasan_cuti') is-invalid @enderror" placeholder="Alasan Cuti"
                    name="alasan_cuti" id="alasan_cuti" cols="3">{{ old('alasan_cuti') }}</textarea>
                @error('alasan_cuti')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            {{-- Mulai Cuti - Selesai Cuti --}}
            <div
                class="d-flex align-items-center gap-2 @error('mulai_cuti') mb-4 @enderror @error('selesai_cuti') mb-4 @enderror">
                <div class="w-100 position-relative">
                    <label for="mulai_cuti" class="fw-semibold">Mulai Cuti<span class="text-danger">*</span></label>
                    <input type="date" class="form-control rounded-3 py-4 @error('mulai_cuti') is-invalid @enderror"
                        name="mulai_cuti" id="mulai_cuti" value="{{ old('mulai_cuti') }}">
                    @error('mulai_cuti')
                        <div class="error-input-left text-danger">{{ $message }} </div>
                    @enderror
                </div>
                <div style="width: 28px;height: 2px;translate: 0 12px" class="bg-secondary rounded-circle"></div>
                <div class="w-100 position-relative">
                    <label for="selesai_cuti" class="fw-semibold">Selesai Cuti<span class="text-danger">*</span></label>
                    <input type="date" class="form-control rounded-3 py-4 @error('selesai_cuti') is-invalid @enderror"
                        name="selesai_cuti" id="selesai_cuti" value="{{ old('selesai_cuti') }}">
                    @error('selesai_cuti')
                        <div class="error-input-right text-danger">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            {{-- Lampiran --}}
            <div class="d-flex gap-2 align-items-center">
                <div class="w-100">
                    <label for="lampiran" class="fw-semibold">Lampiran</label>
                    <input type="file" class="form-control rounded-3 @error('lampiran') is-invalid @enderror"
                        name="lampiran" id="lampiran">
                    @error('lampiran')
                        <div class="invalid-feedback">{{ $message }} </div>
                    @enderror
                </div>
                <div class="or-divider">atau</div>
                <div class="w-100">
                    <label for="url_lampiran" class="fw-semibold">Tempel URL Lampiran</label>
                    <input type="url" class="form-control rounded-3" value="{{ old('url_lampiran') }}"
                        name="url_lampiran" placeholder="Contoh: https://drive.google.com/..." id="url_lampiran">
                </div>
            </div>
            {{-- Alamat Cuti --}}
            <div>
                <label for="alamat_cuti" class="fw-semibold">Alamat Selama Cuti<span class="text-danger">*</span></label>
                <textarea class="form-control rounded-3 py-4 @error('alamat_cuti') is-invalid @enderror"
                    placeholder="Alamat Selama Cuti" name="alamat_cuti" id="alamat_cuti" cols="3">{{ old('alamat_cuti') }}</textarea>
                @error('alamat_cuti')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            {{-- Tanda Tangan --}}
            <div style="margin-bottom: 120px">
                <label for="tanda_tangan" class="fw-semibold">
                    Tanda Tangan<span class="text-danger">*</span>
                    <span class="text-secondary" style="font-size: 12px">(max:256KB)
                        <a href="{{ asset('assets/img/ttd-sample.png') }}" target="_blank" style="font-size: 12px">Lihat
                            Contoh</a></span>
                </label>
                <input type="file" class="form-control rounded-3 @error('tanda_tangan') is-invalid @enderror"
                    name="tanda_tangan" id="tanda_tangan" required>
                @error('tanda_tangan')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
                <div id="preview_tanda_tangan" class="my-3" style="height: 144px"></div>
            </div>
            <div class="footer-submit">
                <button type="submit" class="btn btn-success">Buat Surat Cuti</button>
                <a type="button" class="btn btn-outline-success" href={{ url()->previous() }}>Kembali</a>
            </div>
        </form>
    </div>
@endsection

@section('footer')
    <section class="bg-dark p-1">
        <div class="container d-flex justify-content-center">
            <p class="developer">Dikembangkan oleh Prodi Teknik Informatika UNRI
                (<a class="text-success fw-bold" href="https://pangidoannsh.vercel.app" target="_blank">
                    Pangidoan Nugroho Syahputra Harahap
                </a>)
            </p>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview_tanda_tangan').html('<img src="' + e.target.result +
                        '" style="max-width: 100%; max-height: 100%;">');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Menangkap perubahan pada input file
        $("#tanda_tangan").change(function() {
            readURL(this);
        });
    </script>
@endpush
