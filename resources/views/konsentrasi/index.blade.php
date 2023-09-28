@extends('layouts.main')

@section('title')
    Daftar Konsentrasi | SIA ELEKTRO
@endsection

@section('sub-title')
    Daftar Konsentrasi
@endsection

@section('content')

@if (session()->has('message'))
<div class="swal" data-swal="{{session('message')}}"></div>
@endif 

<a href="{{url ('/konsentrasi/create')}}" class="btn konsentrasi btn-success mb-3">+ Konsentrasi</a>

<table class="table text-center table-bordered table-striped" style="width:100%" id="datatables">
  <thead class="table-dark">
    <tr>
      <th class="text-center" scope="col">#</th>
      <th class="text-center" scope="col">Konsentrasi</th>
      <th class="text-center" scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($konsentrasis as $konsentrasi)
        <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$konsentrasi->nama_konsentrasi}}</td>
          <td>        
            <a href="/konsentrasi/edit/{{$konsentrasi->id}}" class="badge bg-warning"><i class="fas fa-pen"></i></a>
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
@endpush