@extends('layouts.main')

@php
    Use Carbon\Carbon;
@endphp

@section('title')
    Pendaftaran | SIA ELEKTRO
@endsection


@section('sub-title')
 Kapasitas Bimbingan
@endsection
@section('content')
<a href="/kapasitas-bimbingan/index" class="badge bg-success p-2 mb-4 "> Kembali <a>

<form action="/kapasitas-bimbingan/edit/{{$kp->id}}" method="POST" enctype="multipart/form-data">
    @csrf
     <div class="row">
    <div class="col">
<div class="mb-3 field">
            <label class="form-label">Kapasitas Bimbingan KP</label>
            <input type="number" name="kapasitas_kp" class="form-control @error('kapasitas_kp') is-invalid @enderror" value="{{ old('kapasitas_kp', $kp->kapasitas_kp) }}"> 
            @error('kapasitas_kp')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror           
        </div>

 <div class="mb-3">
     <label class="form-label pb-0">Kapasitas Bimbingan Skripsi<span class="text-danger">*</span></label>
            <input type="number" name="kapasitas_skripsi" class="form-control @error ('kapasitas_skripsi') is-invalid @enderror" value="{{ old('kapasitas_skripsi', $kp->kapasitas_skripsi) }}">
            @error('kapasitas_skripsi')
              <div class="invalid-feedback">
                  {{$message}}
              </div>
            @enderror
             </div>
       <button type="submit" class="btn btn-success  mt-4 float-end">Simpan</button>
</form>

        </div>
    </div>

@endsection