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

        <form action="{{ route('suratcuti.update', ['id' => $suratCuti->id]) }}" method="POST"
            class="d-flex flex-column gap-3" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div>
                <label for="nama" class="fw-semibold">Nama</label>
                <input type="text" class="form-control rounded-3 py-4" name="nama"
                    value="{{ Auth::guard($jenis_user == 'admin' ? 'web' : $jenis_user)->user()->nama }}" id="nama"
                    disabled>
            </div>
            @if (Auth::guard('dosen')->check())
                <div>
                    <label for="nip" class="fw-semibold">NIP</label>
                    <input type="text" class="form-control rounded-3 py-4" name="nip"
                        value="{{ Auth::guard('dosen')->user()->nip }}" id="nip" disabled>
                </div>
            @endif
            <div>
                <label for="jabatan" class="fw-semibold">Jabatan</label>
                <input type="text" class="form-control rounded-3 py-4 text-capitalize" name="jabatan"
                    value="{{ $jabatan }}" id="jabatan" disabled>
            </div>
            {{-- Nomor Telepon --}}
            <div>
                <label for="nomor_telepon" class="fw-semibold">Nomor Telepon<span class="text-danger">*</span></label>
                <input type="tel" class="form-control rounded-3 py-4" name="nomor_telepon" id="nomor_telepon"
                    placeholder="08***" value="{{ old('nomor_telepon') ?? $suratCuti->nomor_telepon }}" required>
            </div>
            <div>
                <label for="jenis_cuti" class="fw-semibold">Jenis Cuti</label>
                <div class="input-group">
                    <select name="jenis_cuti" id="pilih_jenis"
                        class="text-secondary text-capitalize rounded-3 @error('jenis_cuti') border border-danger @enderror">
                        <option value="{{ null }}" disabled selected>Pilih Jenis Cuti</option>
                        @foreach ($jenis_cuti as $jenis)
                            <option value="{{ $jenis }}" class="text-capitalize"
                                {{ $suratCuti->jenis_cuti == $jenis ? 'selected' : '' }}>{{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_cuti')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="alasan_cuti" class="fw-semibold">Alasan Cuti</label>
                <textarea class="form-control rounded-3 py-4 @error('alasan_cuti') is-invalid @enderror" placeholder="Alasan Cuti"
                    name="alasan_cuti" id="alasan_cuti" cols="3">{{ $suratCuti->alasan_cuti }}</textarea>
                @error('alasan_cuti')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div
                class="d-flex align-items-center gap-2 @error('mulai_cuti') mb-4 @enderror @error('selesai_cuti') mb-4 @enderror">
                <div class="w-100 position-relative">
                    <label for="mulai_cuti" class="fw-semibold">Mulai Cuti</label>
                    <input type="date" class="form-control rounded-3 py-4 @error('mulai_cuti') is-invalid @enderror"
                        name="mulai_cuti" id="mulai_cuti" value="{{ $suratCuti->mulai_cuti }}">
                    @error('mulai_cuti')
                        <div class="error-input-left text-danger">{{ $message }} </div>
                    @enderror
                </div>
                <div style="width: 28px;height: 2px;translate: 0 12px" class="bg-secondary rounded-circle"></div>
                <div class="w-100 position-relative">
                    <label for="selesai_cuti" class="fw-semibold">Selesai Cuti</label>
                    <input type="date" class="form-control rounded-3 py-4 @error('selesai_cuti') is-invalid @enderror"
                        name="selesai_cuti" id="selesai_cuti" value="{{ $suratCuti->selesai_cuti }}">
                    @error('selesai_cuti')
                        <div class="error-input-right text-danger">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            <div id="current-dokumen">
                <label class="fw-semibold">Lampiran</label>
                <div class="d-flex gap-2 align-items-center">
                    @if ($suratCuti->url_lampiran || $suratCuti->url_lampiran_lokal)
                        @if ($suratCuti->url_lampiran_lokal)
                            <a href="{{ asset('storage/' . $suratCuti->url_lampiran_lokal) }}" target="_blank"
                                class="btn btn-success px-3 rounded-3" style="width:max-content">
                                Lampiran Saat ini
                            </a>
                        @endif
                        @if ($suratCuti->url_lampiran)
                            <a href="{{ $suratCuti->url_lampiran }}" target="_blank" class="btn btn-success px-3 rounded-3"
                                style="width:max-content">
                                Lampiran Saat ini
                            </a>
                        @endif
                    @else
                        (Belum ada file terlampir)
                    @endif
                    <button class="btn text-warning" type="button" id="btn-edit-dokumen" title="ubah dokumen">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
            </div>
            <div class="gap-4 align-items-center d-none bg-white p-4 pt-5 rounded-3 position-relative" id="input-dokumen">
                <button type="button" id="close-button-input" class="btn text-secondary position-absolute"
                    title="batal ubah link dokumen" style="right: 4px;top:0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="w-100">
                    <label for="lampiran" class="fw-semibold">Lampiran</label>
                    <input type="file" class="form-control rounded-3 @error('lampiran') is-invalid @enderror"
                        name="lampiran" id="lampiran">
                    @error('lampiran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="or-divider">atau</div>
                <div class="w-100">
                    <label for="url_lampiran" class="fw-semibold">Tempel URL Lampiran</label>
                    <input type="url" class="form-control rounded-3" value="{{ $suratCuti->url_lampiran }}"
                        name="url_lampiran" placeholder="https://drive.google.com/..." id="url_lampiran">
                </div>
            </div>
            <div>
                <label for="alamat_cuti" class="fw-semibold">Alamat Selama Cuti</label>
                <textarea class="form-control rounded-3 py-4 @error('alamat_cuti') is-invalid @enderror"
                    placeholder="Alamat Selama Cuti" name="alamat_cuti" id="alamat_cuti" cols="3">{{ $suratCuti->alamat_cuti }}</textarea>
                @error('alamat_cuti')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            {{-- Tanda Tangan --}}
            <div style="margin-bottom: 120px">
                <label for="tanda_tangan" class="fw-semibold">Tanda Tangan <span class="text-secondary"
                        style="font-size: 12px">(max:256KB)
                        <a href="{{ asset('assets/img/ttd-sample.png') }}" target="_blank" style="font-size: 12px">Lihat
                            Contoh</a></span></label>
                <input type="file" class="form-control rounded-3 @error('tanda_tangan') is-invalid @enderror"
                    name="tanda_tangan" id="tanda_tangan">
                @error('tanda_tangan')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
                <div id="preview_tanda_tangan" class="my-3" style="height: 144px"></div>
            </div>
            <div class="footer-submit">
                <button type="submit" class="btn btn-success">Ubah Pengjuan Cuti</button>
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
        const btnEditDokumen = $("#btn-edit-dokumen")
        btnEditDokumen.on("click", () => {
            $("#input-dokumen").removeClass("d-none")
            $("#input-dokumen").addClass("d-flex")
        })

        $("#close-button-input").on('click', () => {
            $("#input-dokumen").removeClass("d-flex")
            $("#input-dokumen").addClass("d-none")
        })
    </script>
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

        $('#preview_tanda_tangan').html(
            `<img src="{{ asset('storage/' . $suratCuti->tanda_tangan) }}" style="max-width: 100%; max-height: 100%;">`
        );
    </script>
@endpush
