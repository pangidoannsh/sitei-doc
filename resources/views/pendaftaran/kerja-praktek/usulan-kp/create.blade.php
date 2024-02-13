@extends('layouts.main')

@section('title')
    SITEI | Pendaftaran Kerja Praktek
@endsection

@section('sub-title')
    Pendaftaran Kerja Praktek
@endsection

@section('content')
    @if ($kp == null)
        <div class="container">
            <a href="/kp-skripsi" class="btn btn-success py-1 px-2 mb-3"><i class="fas fa-arrow-left fa-xs"></i> Kembali <a>
        </div>
    @else
        <div class="container">
            <a href="/usulankp/index" class="btn btn-success py-1 px-2 mb-3"><i class="fas fa-arrow-left fa-xs"></i> Kembali
                <a>
        </div>
    @endif

@if ($kp == null)
@if (Str::length(Auth::guard('mahasiswa')->user()) > 0)
        <div class="container-fluid ">
            <form action="{{ url('/usulankp/create') }}" class="mahasiswa-usulan" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="container ">
                    <div class="mb-2 field">
                        <label class="form-label mb-2 ">Perusahaan/Instansi<span class="text-danger">*</span></label>
                        <input type="text" name="nama_perusahaan"
                            class="form-control @error('nama_perusahaan') is-invalid @enderror"
                            value="{{ old('nama_perusahaan') }}" required autofocus>
                        @error('nama_perusahaan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-2 field">
                        <label class="form-label mb-2">Alamat Perusahaan<span class="text-danger">*</span></label>
                        <input type="text" name="alamat_perusahaan"
                            class="form-control @error('alamat_perusahaan') is-invalid @enderror"
                            value="{{ old('alamat_perusahaan') }}" required>
                        @error('alamat_perusahaan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-2 field">
                        <label class="form-label mb-2">Bidang Usaha/Kegiatan<span class="text-danger">*</span></label>
                        <input type="text" name="bidang_usaha"
                            class="form-control @error('bidang_usaha') is-invalid @enderror"
                            value="{{ old('bidang_usaha') }}" required>
                        @error('bidang_usaha')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="formFile" class="form-label mb-2 float-start">KRS Semester Berjalan<span
                                class="text-danger">*</span> <small class="text-secondary">( Format .pdf | Maks. 200 KB )
                            </small></label>
                        <input name="krs_berjalan" class="form-control @error('krs_berjalan') is-invalid @enderror"
                            value="{{ old('krs_berjalan') }}" type="file" id="formFile" required>
                        <!-- old tidak bisa di pakai disini karna pertimbangan security, jgn sampai org tau directory kita -->
                        @error('krs_berjalan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="formFile" class="form-label mb-2 float-start">Transkip Nilai<span
                                class="text-danger">*</span> <small class="text-secondary">( Format .pdf | Maks. 200 KB )
                            </small></label>
                        <input name="transkip_nilai" class="form-control @error('transkip_nilai') is-invalid @enderror"
                            value="{{ old('transkip_nilai') }}" type="file" id="formFile" required>

                        @error('transkip_nilai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-2 field">
                        <label for="dosen_pembimbing_nip" class="form-label mb-2">Dosen Pembimbing<span
                                class="text-danger">*</span></label>
                        <select name="dosen_pembimbing_nip" id="pembimbing_nip"
                            class="form-select @error('dosen_pembimbing_nip') is-invalid @enderror" required>
                            <option value="">- Pilih Pembimbing -</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->nip }}"
                                    {{ old('dosen_pembimbing_nip') == $dosen->nama ? 'selected' : null }}>
                                    {{ $dosen->nama }}</option>
                            @endforeach
                        </select>
                        @error('dosen_pembimbing_nip')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-2 field">
                        <label class="form-label mb-2">Rencana mulai KP<span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_rencana"
                            class="form-control @error('tanggal_rencana') is-invalid @enderror"
                            value="{{ old('tanggal_rencana') }}" required>
                        @error('tanggal_rencana')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button  type="submit" class="btn mt-4 btn-lg btn-success float-right" title="Usulkan KP">Usulkan</button>
                                        
                </div>
            </form>
        </div>
    @endif
    @endif
<br>
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
