@extends('layouts.main')

@section('title')
    SITEI | Bukti Penyerahan Laporan Kerja Praktek
@endsection

@section('sub-title')
    Bukti Penyerahan Laporan Kerja Praktek
@endsection

@section('content')
    @foreach ($pendaftaran_kp as $kp)
        <form action="/kpti10-kp/create/{{ $kp->id }}" class="mahasiswa-usulan" method="POST" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Laporan KP<span class="text-danger">*</span> <small
                                    class="text-secondary">( Format .pdf | Maks. 1 MB ) </small></label>
                            <input name="laporan_akhir"
                                class="mb-3 form-control @error('laporan_akhir') is-invalid @enderror"
                                value="{{ old('laporan_akhir') }}" type="file" id="formFile" required autofocus>

                            @error('laporan_akhir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <label for="formFile" class="form-label">KPTI/TE-10/Bukti Penyerahan Laporan KP<span
                                    class="text-danger">*</span> <small class="text-secondary">( Format .pdf | Maks. 200 KB
                                    ) </small></label>
                            <input name="kpti_10" class="form-control mb-2 @error('kpti_10') is-invalid @enderror"
                                value="{{ old('kpti_10') }}" type="file" id="formFile" required autofocus>

                            @error('kpti_10')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button  type="submit" class="btn mt-4 btn-lg btn-success float-right" title="Kirim Bukti Penyerahan Laporan">Kirim</button>


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

@push('scripts')
    <script>
        $(document).ready(function() {
        $('.mahasiswa-usulan').submit(function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Jika belum, silahkan periksa kembali data yang akan Anda kirim.",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'Kembali',
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
