@extends('layouts.main')

@php
    Use Carbon\Carbon;
@endphp

@section('title')
   SITEI ELEKTRO | Bimbingan Kerja Praktek
@endsection

@section('sub-title')
Daftar Bimbingan Kerja Praktek
@endsection

@section('content')

@if (session()->has('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{session('message')}}
</div>
@endif

<div class="container card p-4">

<ol class="breadcrumb col-lg-12">
     
<h5 class="">Data Bimbingan <span class="fw-bold fs-5">{{$dosen->nama}}  </span></h5>

</ol>

<div class="container-fluid">

<table class="table table-responsive-lg table-bordered table-striped" width="100%" id="datatables">
  <thead class="table-dark">
    <tr>      
        <th class="text-center px-0" scope="col">No.</th>
        <th class="text-center" scope="col">NIM</th>
        <th class="text-center" scope="col">Nama</th>
        <th class="text-center" scope="col">Program Studi</th>
        <th class="text-center" scope="col">Konsentrasi</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($pendaftaran_kp as $kp)

<div></div>
        <tr>        
            <td class="text-center">{{$loop->iteration}}</td>                             
            <td class="text-center">{{$kp->mahasiswa->nim}}</td>                             
            <td class="text-center">{{$kp->mahasiswa->nama}}</td>
          
            <td class="text-center ">{{$kp->mahasiswa->prodi->nama_prodi}}</td>

            <td class="text-center">{{$kp->mahasiswa->konsentrasi->nama_konsentrasi}}</td>

        </tr>

    @endforeach
  </tbody>


</table>
</div>
</div>
@endsection



