@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    Riwayat Persetujuan | SIA ELEKTRO
@endsection

@section('sub-title')
    Riwayat Persetujuan
@endsection

@section('content')

@if (session()->has('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{session('message')}}
</div>
@endif

<ol class="breadcrumb col-lg-12">
  <li class="breadcrumb-item"><a class="breadcrumb-item active" href="/persetujuan-kaprodi">Persetujuan</a></li>
  <li class="breadcrumb-item"><a href="/riwayat-kaprodi">Riwayat Persetujuan</a></li>  
</ol>

<table class="table table-bordered table-striped" id="datatables">
  <thead class="table-dark">
    <tr>
      <th scope="col">NIM</th>
      <th scope="col">Nama</th>
      <th scope="col">Seminar</th>
      <th scope="col">Prodi</th>
      <th scope="col">Tanggal</th>
      <th scope="col">Waktu</th>
      <th scope="col">Lokasi</th>              
      <th scope="col">Pembimbing</th>
      <th scope="col">Penguji</th>      
    </tr>
  </thead>
  <tbody>

    @foreach ($penjadwalan_kps as $kp)
        <tr>
          <td>{{$kp->mahasiswa->nim}}</td>                             
          <td>{{$kp->mahasiswa->nama}}</td>                     
          <td class="bg-primary">{{$kp->jenis_seminar}}</td>                     
          <td>{{$kp->prodi->nama_prodi}}</td>          
          <td>{{Carbon::parse($kp->tanggal)->translatedFormat('l, d F Y')}}</td>                   
          <td>{{$kp->waktu}}</td>                   
          <td>{{$kp->lokasi}}</td>                   
          <td>
            <p>{{$kp->pembimbing->nama}}</p>            
          </td> 
          <td>
            <p>{{$kp->penguji->nama}}</p>            
          </td>                                 
        </tr>
    @endforeach
    
    @foreach ($penjadwalan_sempros as $sempro)
        <tr>
          <td>{{$sempro->mahasiswa->nim}}</td>                             
          <td>{{$sempro->mahasiswa->nama}}</td>                     
          <td class="bg-success">{{$sempro->jenis_seminar}}</td>                     
          <td>{{$sempro->prodi->nama_prodi}}</td>          
          <td>{{Carbon::parse($sempro->tanggal)->translatedFormat('l, d F Y')}}</td>                   
          <td>{{$sempro->waktu}}</td>                   
          <td>{{$sempro->lokasi}}</td>                   
          <td>
            <p>1. {{$sempro->pembimbingsatu->nama}}</p>
            @if ($sempro->pembimbingdua == !null)
            <p>2. {{$sempro->pembimbingdua->nama}}</p>                               
            @endif
          </td> 
          <td>
            <p>1. {{$sempro->pengujisatu->nama}}</p>
            <p>2. {{$sempro->pengujidua->nama}}</p>
            <p>3. {{$sempro->pengujitiga->nama}}</p>
          </td>                                 
        </tr>
    @endforeach

    @foreach ($penjadwalan_skripsis as $skripsi)
        <tr>
          <td>{{$skripsi->mahasiswa->nim}}</td>                             
          <td>{{$skripsi->mahasiswa->nama}}</td>                     
          <td class="bg-warning">{{$skripsi->jenis_seminar}}</td>                     
          <td>{{$skripsi->prodi->nama_prodi}}</td>          
          <td>{{Carbon::parse($skripsi->tanggal)->translatedFormat('l, d F Y')}}</td>                   
          <td>{{$skripsi->waktu}}</td>                   
          <td>{{$skripsi->lokasi}}</td>                     
          <td>
            <p>1. {{$skripsi->pembimbingsatu->nama}}</p>
            @if ($skripsi->pembimbingdua == !null)
            <p>2. {{$skripsi->pembimbingdua->nama}}</p>                               
            @endif
          </td> 
          <td>
            <p>1. {{$skripsi->pengujisatu->nama}}</p>
            <p>2. {{$skripsi->pengujidua->nama}}</p>
            <p>3. {{$skripsi->pengujitiga->nama}}</p>
          </td>                                
        </tr>
    @endforeach
  </tbody>
</table>
    
@endsection

@push('scripts')
<script>
  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
      $(this).remove(); 
    });
  }, 2000);
</script>
@endpush()