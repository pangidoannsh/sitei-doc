@extends('layouts.main')

@section('title')
    Daftar Mahasiswa | SIA ELEKTRO
@endsection

@section('sub-title')
    Daftar Mahasiswa
@endsection

@section('content')

@if (session()->has('message'))
<div class="swal" data-swal="{{session('message')}}"></div>
@endif 

<a href="{{url ('/mahasiswa/create')}}" class="btn mahasiswa btn-success mb-3">+ Mahasiswa</a>

<div class="container card p-4">

<table class="table table-responsive-lg text-center table-bordered table-striped" style="width:100%" id="datatables">
  <thead class="table-dark">
    <tr>
      <th class="text-center" scope="col">#</th>      
      <th class="text-center" scope="col">NIM</th>
      <th class="text-center" scope="col">Nama</th>
      <th class="text-center" scope="col">Angkatan</th>
      <th class="text-center" scope="col">Program Studi</th>      
      <th class="text-center" scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($mahasiswas as $mhs)
        <tr>
          <td>{{$loop->iteration}}</td>          
          <td>{{$mhs->nim}}</td>
          <td>{{$mhs->nama}}</td>
          <td>{{$mhs->angkatan}}</td>
          <td>{{$mhs->prodi->nama_prodi}}</td>          
          <td class="text-center">        
            <a href="/mahasiswa/edit/{{$mhs->id}}" class="badge bg-warning"><i class="fas fa-pen"></i></a>
</form>
          </td>
        </tr>
    @endforeach
  </tbody>
</table>

</div>
    
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