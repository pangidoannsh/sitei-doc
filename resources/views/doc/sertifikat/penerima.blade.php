@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Distribusi Dokuen & Surat
@endsection

@section('content')
    @include('doc.components.preview-sertif')
    <section class="row pb-5" style="margin-top: -24px">
        <div class="col-lg-8">
            <div class="dokumen-card">
                <div>
                    <h2>{{ $data->sertifikat->nama }}</h2>
                    <div class="divider-green"></div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Tanggal Sertifikat</div>
                    <div class="value">
                        {{ Carbon::parse($data->sertifikat->tanggal)->translatedFormat('l, d F Y') }}
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nomor Sertifikat</div>
                    <div class="value">
                        {{ $data->nomor_sertif }}
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Sertifikat</div>
                    <a href="{{ route('sertif.download', $data->slug) }}" class="btn btn-success px-3 rounded-3 btnDownload"
                        style="width:max-content" title="unduh sertifikat">
                        <i class="fa-solid fa-download"></i> Unduh Sertifikat
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dokumen-card">
                <div>
                    <h2>Penerima Sertifikat</h2>
                    <div class="divider-green"></div>
                </div>
                @if ($data->jenis_penerima == 'dosen' || $data->jenis_penerima == 'mahasiswa')
                    <div class="d-flex flex-column gap-1">
                        <div class="label">{{ $data->jenis_penerima == 'dosen' ? 'NIP' : 'NIM' }}</div>
                        <div class="value text-capitalize">{{ $data->user_penerima }}
                        </div>
                    </div>
                @endif
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nama</div>
                    <div class="value text-capitalize">
                        @if ($data->user_penerima)
                            {{ data_get($data, $data->jenis_penerima . '.nama') }}
                        @else
                            {{ $data->nama_penerima }}
                        @endif
                    </div>
                </div>
                @if ($data->jenis_penerima == 'mahasiswa')
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Prodi</div>
                        <div class="value text-capitalize">{{ $data->mahasiswa->prodi->nama_prodi }}</div>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Angkatan</div>
                        <div class="value text-capitalize">{{ $data->mahasiswa->angkatan }}</div>
                    </div>
                @endif
            </div>
        </div>
        </div>
    </section>
@endsection

@section('footer')
    <section class="bg-dark p-1">
        <div class="container">
            <p class="developer">Dikembangkan oleh Prodi Teknik Informatika UNRI <a class="text-success fw-bold"
                    formtarget="_blank" target="_blank" href="https://pangidoannsh.vercel.app">( Pangidoan Nugroho
                    Syahputra
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
                title: 'Hapus Sertifikat "{{ $data->nama }}"',
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
        $(".btnDownload").click(function() {
            Swal.fire({
                toast: true,
                icon: 'info',
                title: 'Sedang memproses sertifikat',
                animation: false,
                position: 'bottom',
                showConfirmButton: false,
                timer: 12000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        })
    </script>
@endpush
