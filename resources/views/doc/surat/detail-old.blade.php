@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Distribusi Dokuen & Surat
@endsection

@section('sub-title')
    Detail Pengajuan Surat
@endsection

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        </div>
    @endif


    <section class="mb-5">
        <div class="container d-flex justify-content-between">
            <a href="{{ route('doc.index') }}" class="btn btn-success py-1 px-2 mb-3">
                <i class="fas fa-arrow-left fa-xs"></i>
                Kembali
            </a>
            @if ($surat->user_created == $userId)
                <div class="d-flex align-items-center gap-2">
                    @if ($surat->status == 'proses')
                        <a href="{{ route('surat.edit', $surat->id) }}" class="btn btn-warning py-1 px-2 text-white">
                            <i class="fa-solid fa-file-pen"></i>
                            Edit
                        </a>
                    @endif
                    @if ($surat->status != 'diterima')
                        <form action="{{ route('surat.delete', $surat->id) }}" method="POST" id="show-delete-confirm">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger py-1 px-2" title="Hapus dokumen">
                                <i class="fa-solid fa-trash"></i>
                                Hapus Pengajuan
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        <div class="container">
            <div class="row rounded shadow-sm">
                <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start">
                    <h5 class="text-bold">Pengajuan Surat</h5>
                    <hr>
                    <p class="card-title text-secondary text-sm ">Nama Surat</p>
                    <p class="card-text text-start">{{ $surat->nama }}</p>
                    <p class="card-title text-secondary text-sm ">Keterangan</p>
                    <p class="card-text text-start">{{ $surat->keterangan }}</p>
                    <p class="card-title text-secondary text-sm ">Status</p>
                    <div class="my-2 card-text text-start fw-semibold rounded-pill px-3 py-1 @switch($surat->status)
                        @case('proses')
                            bg-warning
                            @break
                        @case('ditolak')
                            bg-danger
                            @break
                        @case('diterima')
                            bg-success
                            @break
                        @default
                    @endswitch"
                        style="width: max-content">
                        {{ $surat->keterangan_status }}
                    </div>
                    @if ($surat->status == 'ditolak')
                        <p class="card-title text-secondary text-sm ">Alasan Pengajuan Ditolak</p>
                        <p class="card-text text-start">{{ $surat->alasan_ditolak }}</p>
                    @endif
                    @if ($surat->nomor_surat)
                        <p class="card-title text-secondary text-sm ">Nomor Surat</p>
                        <p class="card-text text-start">{{ $surat->nomor_surat }}</p>
                    @endif
                    @if ($surat->status == 'diterima')
                        <div class="flex flex-column my-3">
                            <div class=" text-secondary text-sm " style="font-size: 20px">Hasil Surat</div>
                            <a href="{{ asset('storage/' . $surat->url_surat_jadi) }}" target="_blank"
                                class="btn btn-success ">Lihat
                                Surat</a>
                        </div>
                    @endif
                    <p class="card-title text-secondary text-sm ">Lampiran</p>
                    @if ($surat->url_lampiran != null)
                        <p class="card-text text-start">
                            <a href="{{ asset('storage/' . $surat->url_lampiran) }}" target="_blank"
                                class="btn btn-outline-primary">
                                Lampiran
                            </a>
                        </p>
                    @else
                        <p class="card-text text-start">
                            (tidak ada dokumen dilampirkan)
                        </p>
                    @endif
                </div>
                <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end">
                    <h5 class="text-bold">Pengaju Surat</h5>
                    <hr>
                    {{ data_get($surat, $surat->jenis_user . '.nama') }}
                </div>
            </div>
        </div>
        @if ($jenisUser == 'admin' && $surat->status == 'proses')
            <div class="d-flex gap-2 mt-3 justify-content-end">
                <button data-toggle="modal" data-target="#accept_modal" class="btn btn-success rounded py-2 px-4">
                    Tandai Selesai
                </button>
                <button id="btnReject" class="btn btn-danger rounded py-2 px-4">
                    Tolak Surat
                </button>
            </div>
            {{-- Modal --}}
            <div class="modal fade w-100" id="accept_modal" tabindex="-1" role="dialog" aria-labelledby="accepted"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content p-4 rounded-lg d-flex flex-column gap-4">
                        <form action="{{ route('surat.done', $surat->id) }}" method="POST" enctype="multipart/form-data"
                            class="d-flex gap-1 flex-column">
                            @csrf
                            @method('post')
                            <h5 class="modal-title">Detail Surat Selesai</h5>
                            <div class="divider-green"></div>
                            <div class="mt-3">
                                <label for="nomor_surat" class="fw-semibold">Nomor Surat</label>
                                <input type="text"
                                    class="form-control @error('nomor_surat') is-invalid @enderror rounded-3 py-4"
                                    name="nomor_surat" placeholder="Nomor Surat" id="nomor_surat"
                                    value="{{ old('nomor_surat') }}">
                                @error('nomor_surat')
                                    <div class="invalid-feedback">{{ $message }} </div>
                                @enderror
                            </div>
                            <div class="mt-2 mb-3">
                                <label for="surat" class="fw-semibold">
                                    Hasil Surat <span style="font-size: 12px">(PDF)</span>
                                </label>
                                <input type="file" class="form-control rounded-3 @error('surat') is-invalid @enderror"
                                    name="surat" id="surat">
                                @error('surat')
                                    <div class="invalid-feedback">{{ $message }} </div>
                                @enderror
                            </div>
                            <button type="submit" class="rounded-3 btn btn-success py-3">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection

@section('footer')
    <section class="bg-dark p-1">
        <div class="container">
            <p class="developer">Dikembangkan oleh Prodi Teknik Informatika UNRI <a class="text-success fw-bold"
                    formtarget="_blank" target="_blank" href="https://pangidoannsh.vercel.app">( Pangidoan Nugroho Syahputra
                    Harahap)</a></p>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $('#show-delete-confirm').submit((e) => {
            const form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Pengajuan Surat "{{ $surat->nama }}"',
                text: 'Apakah Anda Yakin?',
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Hapus',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.currentTarget.submit()
                }
            })
        })
    </script>
    <script>
        function rejectSurat() {
            Swal.fire({
                title: 'Tolak Pengajuan Surat',
                text: 'Apakah Anda Yakin?',
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Tolak',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Tolak Pengajuan Surat',
                        html: `
                        <form id="reasonForm" action="{{ route('surat.reject', $surat->id) }}" method="POST">
                            @csrf
                            @method('post')
                            <label for="alasan">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" cols="50" required></textarea>
                            <br>
                            <button type="submit" class="btn btn-danger p-2 px-3">Kirim</button>
                            <button type="button" onclick="Swal.close();" class="btn btn-secondary p-2 px-3">Batal</button>
                        </form>
                    `,
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                }
            });
        }

        $("#btnReject").on("click", rejectSurat)
    </script>
@endpush
