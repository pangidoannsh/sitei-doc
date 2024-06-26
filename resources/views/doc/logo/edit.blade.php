@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Data Logo
@endsection

@section('content')
    <div class="">
        <h2 class="text-center fw-semibold ">Ubah Data Logo</h2>

        <form action="{{ route('logo.update', $data->id) }}" method="POST"
            class="d-flex flex-column gap-3 mt-3 small-container" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div>
                <label for="nama">Nama<span class="text-danger">*</span></label>
                <input name="nama" id="nama" type="text" class="form-control rounded-3 py-4"
                    placeholder="Nama Logo" value="{{ old('nama') ?? $data->nama }}" required>
            </div>
            <div class="d-flex flex-column gap-2">
                <label>Logo Saat Ini</label>
                <div>
                    <img src="{{ asset('storage/' . $data->url) }}" height="42">
                </div>
            </div>
            <div>
                <label for="logo">Ubah Gambar Logo<span style="font-size: 11px"> (file:PNG, max:1MB)</span><span
                        class="text-danger">*</span>
                </label>
                <input type="file" class="form-control rounded-3 @error('logo') is-invalid @enderror" name="logo"
                    id="logo">
                @error('logo')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div>
                <label for="position">Posisi<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="position" id="position"
                        class="text-secondary form-select text-capitalize rounded-3 text-capitalize">
                        <option value="kiri" {{ $data->position == 'kiri' ? 'selected' : '' }}>Kiri</option>
                        <option value="kanan" {{ $data->position == 'kanan' ? 'selected' : '' }}>Kanan</option>
                    </select>
                </div>
            </div>
            <div class="ml-4">
                <input class="form-check-input" type="checkbox" value="is_mandatory" id="is_mandatory" name="is_mandatory"
                    {{ $data->is_mandatory ? 'checked' : '' }}>
                <label for="is_mandatory">Logo Wajib</label>
            </div>
            <div class="footer-submit">
                <button type="submit" class="btn btn-success ">Ubah</button>
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
