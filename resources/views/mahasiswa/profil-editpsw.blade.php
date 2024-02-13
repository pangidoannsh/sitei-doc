@extends('layouts.main')

@section('title')
    Edit Password | SIA ELEKTRO
@endsection

@section('sub-title')
    Edit Password
@endsection

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-danger alert-dismissible fade show col-lg-5" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="col-lg-6">
        <form action="/profil-mhs/editpasswordmhs" method="POST" enctype="multipart/form-data">
            @method('put')
            @csrf

            <div class="mb-3 field">
                <label class="form-label">Password Lama</label>
                <input type="password" name="password_lama" class="form-control @error('password_lama') is-invalid @enderror">
                @error('password_lama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 field">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 field">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror">
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn editpw btn-success mb-5">Ubah</button>

        </form>
    </div>
@endsection

@section('footer')
    <section class="bg-dark p-1">
        <div class="container">
            <p class="developer">Dikembangkan oleh Prodi Teknik Informatika UNRI <span
                    class="text-success fw-bold">(</span><a class="text-success fw-bold" formtarget="_blank" target="_blank"
                    href="https://fahrilhadi.com"> Fahril Hadi</a> <span class="text-success fw-bold"> & </span>
                <a class="text-success fw-bold" formtarget="_blank" target="_blank"
                    href="/developer/rahul-ilsa-tajri-mukhti">Rahul Ilsa Tajri Mukhti </a> <span
                    class="text-success fw-bold">)</span>
            </p>
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
