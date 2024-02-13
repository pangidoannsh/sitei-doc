@extends('layouts.main')

@section('title')
    SITEI | Daftar Seminar Proposal
@endsection

@section('sub-title')
    Daftar Seminar Proposal
@endsection
<style>
    @media screen and (max-width: 768px) {}
</style>
@section('content')
    @foreach ($pendaftaran_skripsi as $skripsi)
        <div class="container">
            <a href="/usuljudul/index" class="btn btn-success py-1 px-2 mb-4"><i class="fas fa-arrow-left fa-xs"></i> Kembali
                <a>
        </div>

        <form action="/daftar-sempro/create/{{ $skripsi->id }}" class="mahasiswa-usulan" method="POST" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div>
                <div class="row">


                    <div class="col">

                        <div class="mb-3">
                            <label for="formFile" class="form-label">Naskah Proposal<span class="text-danger">*</span>
                                <small class="text-secondary">( Format .pdf | Maks. 5 MB ) </small> </label>
                            <input name="naskah" class="form-control @error('naskah') is-invalid @enderror"
                                value="{{ old('naskah') }}" type="file" id="formFile" required autofocus>

                            @error('naskah')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formFile" class="form-label">KRS Berjalan<span class="text-danger">*</span> <small
                                    class="text-secondary">( Format .pdf | Maks. 200 KB ) </small> </label>
                            <input name="krs_berjalan" class="form-control @error('krs_berjalan') is-invalid @enderror"
                                value="{{ old('krs_berjalan') }}" type="file" id="formFile" required>

                            @error('krs_berjalan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formFile" class="form-label">Kartu Hasil Studi/KPTI-10<span
                                    class="text-danger">*</span> <small class="text-secondary">( Format .pdf | Maks. 200 KB
                                    ) </small></label>
                            <input name="khs" class="form-control @error('khs') is-invalid @enderror"
                                value="{{ old('khs') }}" type="file" id="formFile" required>

                            @error('khs')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formFile" class="form-label">Logbook<span class="text-danger">*</span> <small
                                    class="text-secondary">( Format .pdf | Maks. 200 KB ) </small></label>
                            <input name="logbook" class="form-control @error('logbook') is-invalid @enderror"
                                value="{{ old('logbook') }}" type="file" id="formFile" required>

                            @error('logbook')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>



                        <div class="mb-3">
                            <label for="formFile" class="form-label">STI/TE-30/Bukti Mengumpulkan Syarat Pendaftaran Seminar
                                Proposal<span class="text-danger">*</span> <small class="text-secondary">( Format .pdf |
                                    Maks. 200 KB ) </small> </label>
                            <input name="sti_30" class="form-control @error('sti_30') is-invalid @enderror"
                                value="{{ old('sti_30') }}" type="file" id="formFile" required>

                            @error('sti_30')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formFile" class="form-label">STI/TE-31/Surat Persetujuan Sertifikat Pendamping <small
                                    class="text-secondary">( Format .pdf | Maks. 200 KB ) </small></label>
                            <input name="sti_31" class="form-control @error('sti_31') is-invalid @enderror"
                                value="{{ old('sti_31') }}" type="file" id="formFile">

                            @error('sti_31')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button  type="submit" class="btn mt-4 btn-lg btn-success float-right" title="Daftar Seminar Proposal">Daftar Sempro</button>


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
