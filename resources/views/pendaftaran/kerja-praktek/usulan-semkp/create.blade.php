@extends('layouts.main')

@section('title')
    SITEI |Daftar Seminar Kerja Praktek
@endsection

@section('sub-title')
    Daftar Seminar Kerja Praktek
@endsection

@section('content')
    @foreach ($pendaftaran_kp as $kp)
        <div class="container">
            <a href="/usulankp/index" class="btn btn-success py-1 px-2 mb-3"><i class="fas fa-arrow-left fa-xs"></i> Kembali
                <a>
        </div>

        <form action="/daftar-semkp/create/{{ $kp->id }}" class="mahasiswa-usulan" method="POST" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div>
                <div class="row">


                    <div class="col">

                        <div class="mb-3 field">
                            <label class="form-label">Judul Laporan<span class="text-danger">*</span> </label>
                            <input type="text" name="judul_laporan"
                                class="form-control @error('judul_laporan') is-invalid @enderror"
                                value="{{ old('judul_laporan') }}" required autofocus>
                            @error('judul_laporan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label float-start">Laporan<span
                                    class="text-danger">*</span><small class="text-secondary">( Format .pdf | Maks. 5 MB )
                                </small></label>
                            <input name="laporan_kp" class="form-control @error('laporan_kp') is-invalid @enderror"
                                value="{{ old('laporan_kp') }}" type="file" id="formFile" required>

                            @error('laporan_kp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formFile" class="form-label float-start ">KPTI/TE-11/Bukti Mengumpulkan Syarat
                                Pendaftaran Seminar KP<span class="text-danger">*</span> <small class="text-secondary">(
                                    Format .pdf | Maks. 200 KB ) </small></label>
                            <input name="kpti_11" class="form-control @error('kpti_11') is-invalid @enderror"
                                value="{{ old('kpti_11') }}" type="file" id="formFile" required>

                            @error('kpti_11')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label float-start">STI/TE-31/Surat Persetujuan Sertifikat
                                Pendamping <small class="text-secondary">( Format .pdf | Maks. 200 KB ) </small></label>
                            <input name="sti_31" class="form-control @error('sti_31') is-invalid @enderror"
                                value="{{ old('sti_31') }}" type="file" id="formFile">

                            @error('sti_31')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <button  type="submit" class="btn mt-4 btn-lg btn-success float-right" title="Daftar Seminar KP">Kirim</button>


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
        $('.mahasiswa-usulan').submit(function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Jika belum, silahkan cek kembali data yang akan Anda kirim.",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#28a745',
                cancelButtonColor: 'grey',
                confirmButtonText: 'Kirim'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.currentTarget.submit();
                }
            });
        });
    });
    </script>
@endpush