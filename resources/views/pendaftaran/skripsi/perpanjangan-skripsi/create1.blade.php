@extends('layouts.main')

@section('title')
    Daftar KP | SIA ELEKTRO
@endsection

@section('sub-title')
    Permohonan Perpanjangan 1 Waktu Skripsi
@endsection

@section('content')

@foreach ($pendaftaran_skripsi as $skripsi)

<form action="/perpanjangan1-skripsi/create/{{$skripsi->id}}" method="POST" enctype="multipart/form-data">
@method('put')
        @csrf
    <div>
    <div class="row">


    <div class="col">

        <div class="mb-3">
            <label for="formFile" class="form-label">STI-22/Surat Pernyataan Perpanjangan Skripsi</label>
            <input name="sti_22_p1" class="form-control @error ('sti_22_p1') is-invalid @enderror" value="{{ old('sti_22_p1') }}" type="file" id="formFile" required autofocus>

            @error('sti_22_p1')
              <div class="invalid-feedback">
                  {{$message}}
              </div>
            @enderror
    </div>

        
        <button type="submit" class="btn btn-success  mt-4 float-end">Kirim</button>

                   
            </div>

        </div>
    </div>
</form>



@endforeach
@endsection

@push('scripts')


</script>
@endpush