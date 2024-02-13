@extends('layouts.main')

@section('title')
    SITEI | Permohonan Perpanjangan Revisi
@endsection

@section('sub-title')
    Permohonan Perpanjangan Revisi
@endsection

@section('content')
    @foreach ($pendaftaran_skripsi as $skripsi)
        <form action="/perpanjangan/revisi/create/{{ $skripsi->id }}" method="POST" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div>
                <div class="row">

                    <div class="col">

                        <div class="mb-3">
                            <label for="formFile" class="form-label">STI/TE-23/Surat Pernyataan Perpanjangan Revisi Skripsi
                                <small class="text-secondary">( Format .pdf| Maks. 200 KB ) </small></label>
                            <input name="sti_23" class="form-control @error('sti_23') is-invalid @enderror"
                                value="{{ old('sti_23') }}" type="file" id="formFile" autofocus required>

                            @error('sti_23')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success  mt-4 float-left">Submit</button>

                    </div>
                    <div class="col-md mb-5">
                        <img height="380" width="500" src="/assets/img/il5.png" class="rounded mx-auto d-block"
                            alt="...">
                    </div>
                </div>
            </div>
        </form>
    @endforeach
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
