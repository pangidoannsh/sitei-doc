@extends('doc.main-layout')

@php
    use Carbon\Carbon;
    $batasPengajuan = Carbon::parse($data->created_at)->addDays(3);
    $sisaHari = now()->diffInDays($batasPengajuan, false);
@endphp

@section('title')
    SITEI | Distribusi Dokuen & Surat
@endsection

@section('sub-title')
    Sertifikat
@endsection

@section('content')
    <section class="row">
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
                    <div class="d-flex gap-2">
                        <a href="#" target="_blank" class="btn btn-success px-3 rounded-3" style="width:max-content">
                            Lihat Sertifikat
                        </a>
                        <a href="#" target="_blank" class="btn btn-outline-success  rounded-3"
                            style="width:max-content" title="download sertifikat">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dokumen-card">
                <div>
                    <h2>Penerima Sertifikat</h2>
                    <div class="divider-green"></div>
                </div>
                @if ($data->user_penerima)
                    <div class="d-flex flex-column gap-1">
                        <div class="label">NIM</div>
                        <div class="value text-capitalize">{{ $data->user_penerima }}</div>
                    </div>
                @endif
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nama</div>
                    <div class="value text-capitalize">
                        @if ($data->user_penerima)
                            {{ $data->mahasiswa->nama }}
                        @else
                            {{ $data->nama_penerima }}
                        @endif
                    </div>
                </div>
                @if ($data->user_penerima)
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
    </script>
    {{-- <script>
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
                        <form id="reasonForm" action="{{ route('surat.reject', $data->id) }}" method="POST">
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
    </script> --}}
@endpush
