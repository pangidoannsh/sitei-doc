@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Distribusi Surat & Dokumen
@endsection

{{-- @section('sub-title')
    Buat Usulan
@endsection --}}

@section('content')
    <div class="">
        <h2 class="text-center fw-semibold ">Ubah Surat</h2>

        <form action="{{ route('surat.update', $surat->id) }}" method="POST" class="d-flex flex-column gap-3"
            enctype="multipart/form-data">
            @method('put')
            @csrf
            <div>
                <label for="kepada" class="fw-semibold">Tujuan Surat<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="tujuan_surat" id="kepada"
                        class="text-secondary text-capitalize rounded-3 text-capitalize @error('tujuan_surat') border border-danger @enderror">
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->role_id }}" class="text-capitalize"
                                {{ $surat->role_tujuan == $dosen->role_id ? 'selected' : '' }}>
                                {{ $dosen->nama ?? $role->nama_admin }} ({{ $dosen->role->role_akses }})
                            </option>
                        @endforeach
                    </select>
                    @error('tujuan_surat')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="nama" class="fw-semibold">Nama Surat</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror rounded-3 py-4" name="nama"
                    placeholder="Contoh: Surat Pengantar..." id="nama" value="{{ $surat->nama }}">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div>
                <label for="keterangan" class="fw-semibold">Keterangan</label>
                <textarea class="form-control rounded-3 py-4" placeholder="Keterangan" name="keterangan" id="keterangan" cols="3">{{ $surat->keterangan }}
                </textarea>
            </div>
            <div id="current-dokumen">
                <label class="fw-semibold">Lampiran</label>
                <div class="d-flex gap-2 align-items-center">
                    @if ($surat->url_lampiran || $surat->url_lampiran_lokal)
                        @if ($surat->url_lampiran)
                            <a href="{{ $surat->url_lampiran }}" target="_blank" class="btn btn-success">
                                Lampiran saat ini
                            </a>
                        @endif
                        @if ($surat->url_lampiran_lokal)
                            <a href="{{ asset('storage/' . $surat->url_lampiran_lokal) }}" target="_blank"
                                class="btn btn-success">
                                Lampiran saat ini
                            </a>
                        @endif
                    @else
                        <div class="text-secondary">
                            (belum ada dokumen dilampirkan)
                        </div>
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
                    <label for="dokumen" class="fw-semibold">Lampiran</label>
                    <input type="file" class="form-control rounded-3 @error('dokumen') is-invalid @enderror"
                        name="dokumen" id="dokumen">
                    @error('dokumen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="or-divider">atau</div>
                <div class="w-100">
                    <label for="url_lampiran" class="fw-semibold">Tempel URL Lampiran</label>
                    <input type="url" class="form-control rounded-3"
                        value="@if (!$surat->is_local_file) {{ $surat->url_lampiran }} @endif" name="url_lampiran"
                        placeholder="https://drive.google.com/..." id="url_lampiran">
                </div>
            </div>
            <div class="footer-submit">
                <button type="submit" class="btn btn-success">Ubah Surat</button>
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
        const currentDokumen = "{{ $surat->url_lampiran }}"

        const btnEditDokumen = $("#btn-edit-dokumen")
        btnEditDokumen.on("click", () => {
            $("#input-dokumen").removeClass("d-none")
            $("#input-dokumen").addClass("d-flex")
        })

        $("#close-button-input").on('click', () => {
            $("#input-dokumen").removeClass("d-flex")
            $("#input-dokumen").addClass("d-none")
            $("#url_lampiran").val(currentDokumen)
        })
    </script>
@endpush
