@extends('layouts.main')

@section('title')
    SITEI | Usulan Judul Skripsi
@endsection

@section('sub-title')
    Usulan Judul Skripsi
@endsection
<style>
    @media screen and (max-width: 768px) {}
</style>
@section('content')
    @if ($skripsi == null)
        <div class="container">
            <a href="/kp-skripsi" class="btn btn-success py-1 px-2 mb-3"><i class="fas fa-arrow-left fa-xs"></i> Kembali <a>
        </div>
    @else
        <div class="container">
            <a href="/usuljudul/index" class="btn btn-success py-1 px-2 mb-3"><i class="fas fa-arrow-left fa-xs"></i> Kembali
                <a>
        </div>
    @endif
@if ($skripsi == null)
    <form action="{{ url('/usuljudul/create') }}" class="mahasiswa-usulan" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label class="form-label pb-0">Judul Skripsi<span class="text-danger">*</span></label>
                        <input type="text" name="judul_skripsi"
                            class="form-control @error('judul_skripsi') is-invalid @enderror"
                            value="{{ old('judul_skripsi') }}" required autofocus>
                        @error('judul_skripsi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label pb-0">KRS Semester Berjalan<span
                                class="text-danger">*</span><small class="text-secondary">( Format .pdf | Maks. 200 KB )
                            </small> </label>
                        <input name="krs_berjalan" class="form-control @error('krs_berjalan') is-invalid @enderror"
                            value="{{ old('krs_berjalan') }}" type="file" id="formFile" required>

                        @error('krs_berjalan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label pb-0">Kartu Hasil Studi<span
                                class="text-danger">*</span><small class="text-secondary">( Format .pdf | Maks. 200 KB )
                            </small> </label>
                        <input name="khs" class="form-control @error('khs') is-invalid @enderror"
                            value="{{ old('khs') }}" type="file" id="formFile" required>

                        @error('khs')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label pb-0">Transkip Nilai<span class="text-danger">*</span><small
                                class="text-secondary">( Format .pdf | Maks. 200 KB ) </small> </label>
                        <input name="transkip_nilai" class="form-control @error('transkip_nilai') is-invalid @enderror"
                            value="{{ old('transkip_nilai') }}" type="file" id="formFile" required>

                        @error('transkip_nilai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="pembimbing_1" class="form-label pb-0">Pembimbing 1<span
                                class="text-danger">*</span></label>
                        <select name="pembimbing_1_nip" id="pembimbing_1_nip"
                            class="form-select @error('pembimbing_1_nip') is-invalid @enderror" required>
                            <option value="">- Pilih Pembimbing 1 -</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->nip }}"
                                    {{ old('pembimbing_1_nip') == $dosen->nama ? 'selected' : null }}>{{ $dosen->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('pembimbing_1_nip')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="pembimbing_2" class="form-label pb-0">Pembimbing 2</label>
                        <select name="pembimbing_2_nip" id="pembimbing_2_nip"
                            class="form-select @error('pembimbing_2_nip') is-invalid @enderror">
                            <option value="">- Pilih Pembimbing 2 -</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->nip }}"
                                    {{ old('pembimbing_2_nip') == $dosen->nama ? 'selected' : null }}>{{ $dosen->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('pembimbing_2_nip')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button  type="submit" class="btn mt-4 btn-lg btn-success float-right" title="Usulkan Judul">Usulkan Judul</button>




                </div>

            </div>
        </div>
    </form>
@endif
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