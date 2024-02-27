@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Distribusi Surat & Dokumen
@endsection

@section('content')
    <div class="">
        <h2 class="text-center fw-semibold ">Surat Baru</h2>

        <form action="{{ route('surat.store') }}" method="POST" class="d-flex flex-column gap-3" enctype="multipart/form-data">
            @method('post')
            @csrf
            <div>
                <label for="kepada" class="fw-semibold">Tujuan Surat<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="tujuan_surat" id="kepada"
                        class="text-secondary form-select text-capitalize rounded-3 text-capitalize @error('tujuan_surat') border border-danger @enderror">
                        <option value="" class="text-capitalize" selected disabled>Pilih Tujuan Surat</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->role_id }}" class="text-capitalize"
                                {{ old('tujuan_surat') == $dosen->role_id ? 'selected' : '' }}>
                                {{ $dosen->nama }} ({{ $dosen->role->role_akses }})
                            </option>
                        @endforeach
                    </select>
                    @error('tujuan_surat')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="nama" class="fw-semibold">Nama Surat<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror rounded-3 py-4" name="nama"
                    placeholder="Contoh: Surat Pengantar..." id="nama" value="{{ old('nama') }}">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div>
                <label for="semester" class="fw-semibold">Semester</label>
                <input type="text" class="form-control rounded-3 py-4 text-capitalize" disabled id="semester"
                    value="{{ $semester->nama }}">
                <input type="hidden" name="semester" value="{{ $semester->nama }}">
            </div>

            <div>
                <label for="keterangan" class="fw-semibold">Keterangan</label>
                <textarea class="form-control rounded-3 py-4" placeholder="Keterangan" name="keterangan" id="keterangan" cols="3">{{ old('keterangan') }}</textarea>
            </div>
            <div class="d-flex gap-4 align-items-center">
                <div class="w-100">
                    <label for="dokumen" class="fw-semibold">Lampiran</label>
                    <input type="file" class="form-control rounded-3 @error('dokumen') is-invalid @enderror"
                        name="dokumen" id="dokumen">
                    @error('dokumen')
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
            <div class="footer-submit">
                <button type="submit" class="btn btn-success">Buat Surat</button>
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
