@extends('layouts.main')

@section('title')
    SITEI | Bukti Penyerahan Buku Skripsi
@endsection

@section('sub-title')
    Bukti Penyerahan Buku Skripsi
@endsection

@section('content')
    <div class="container">
        <a href="/usuljudul/index" class="btn btn-success py-1 px-2 mb-3"><i class="fas fa-arrow-left fa-xs"></i> Kembali <a>
    </div>

    @foreach ($pendaftaran_skripsi as $skripsi)
        <form action="/penyerahan-buku-skripsi/create/{{ $skripsi->id }}" method="POST" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="formFile" class="form-label float-start">Buku Skripsi<span
                                    class="text-danger">*</span> <small class="text-secondary">( Format .pdf | Maks. 10 MB )
                                </small> </label>
                            <input name="naskah" class="form-control @error('naskah') is-invalid @enderror"
                                value="{{ old('naskah') }}" type="file" id="formFile" required autofocus>

                            @error('naskah')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formFile" class="form-label float-start">STI/TE-17/Bukti Penyerahan Buku Skripsi<span
                                    class="text-danger">*</span> <small class="text-secondary">( Format .pdf | Maks. 200 KB
                                    ) </small></label>
                            <input name="sti_17" class="form-control @error('sti_17') is-invalid @enderror"
                                value="{{ old('sti_17') }}" type="file" id="formFile" required>

                            @error('sti_17')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!-- <div class="mb-3">
                                            <label for="formFile" class="form-label float-start">STI/TE-29/ Bukti Sudah Daftar Wisuda di Fakultas<span class="text-danger">*</span> <small class="text-secondary">( Format .pdf | Maks. 200 KB ) </small></label>
                                            <input name="sti_29" class="form-control @error('sti_29') is-invalid @enderror" value="{{ old('sti_29') }}" type="file" id="formFile"  >

                                            @error('sti_29')
        <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
    @enderror
                                    </div> -->

                        <a href="#ModalApprove" data-toggle="modal"
                            class="btn mt-4 btn-lg btn-success float-right">Kirim</a>
                        <div class="modal fade"id="ModalApprove">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow-sm">
                                    <div class="modal-body">
                                        <div class="container px-5 pt-5 pb-2">
                                            <h3 class="text-center">Apakah Anda Yakin?</h3>
                                            <p class="text-center">Jika belum, silahkan cek kembali Data yang akan Anda
                                                Kirim.</p>
                                            <div class="row text-center">
                                                <div class="col-3">
                                                </div>
                                                <div class="col-3">
                                                    <button type="button" class="btn p-2 px-3 btn-secondary"
                                                        data-dismiss="modal">Tidak</button>
                                                </div>
                                                <div class="col-3">
                                                    <button type="submit" class="btn btn-success py-2 px-3">Kirim</button>
                                                </div>
                                                <div class="col-3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </form>
    @endforeach
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
