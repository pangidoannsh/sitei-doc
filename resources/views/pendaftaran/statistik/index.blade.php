@extends('layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Data Statistik
@endsection

@section('sub-title')
    Data Statistik
@endsection

@section('js')
    <script src="{{ $chart->cdn() }}"></script>

    {{ $chart->script() }}
@endsection

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="container card p-4">

        <ol class="breadcrumb col-lg-12">

            <li>
                <a href="/statistik" class="breadcrumb-item active fw-bold text-success px-1">Statistik</a>
            </li>

            <span class="px-2">|</span>
            <li><a href="/statistik/bimbingan-kp" class="px-1">Bimbingan KP</a></li>
            <span class="px-2">|</span>
            <li><a href="/statistik/bimbingan-skripsi" class="px-1">Bimbingan Skripsi</a></li>

        </ol>

        <div class="container-fluid">

    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                {!! $chart->container() !!}
            </div>
            
            <div class="col-lg-6">
                {!! $chart2->container() !!}
            </div>
            
        </div>
            
        </div>

           
        </div>
    </div>
<br>
    <br>
    <br>
@endsection

@section('footer')
    <section class="bg-dark p-1">
        <div class="container">
            <p class="developer">Dikembangkan oleh Prodi Teknik Informatika UNRI <a class="text-success fw-bold"
                    formtarget="_blank" target="_blank" href="/developer/m-seprinaldi">( M. Seprinaldi )</a></p>
        </div>
    </section>
@endsection







