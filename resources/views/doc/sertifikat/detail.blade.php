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
    Detail Pembuatan Sertifikat
@endsection

@section('content')
    <section class="row">
        <div class="col-lg-8">
            <div class="dokumen-card">
                <div>
                    <h2>{{ $data->nama }}</h2>
                    <div class="divider-green"></div>
                </div>
                @if ($data->is_done)
                    <div class="bg-success rounded-2 py-3 text-center fw-semibold text-white">
                        Sertifikat Telah Dibagikan
                    </div>
                @else
                    <div class="rounded-2 py-3 text-center fw-semibold text-white" style="background-color: #fbbf24">
                        Dalam Proses Pembuatan
                    </div>
                @endif
                {{-- <div class="d-flex flex-column gap-1">
                    <div class="label">Nama Sertifikat</div>
                    <div class="value text-capitalize">{{ $data->nama }}</div>
                </div> --}}
                @if ($data->isi)
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Isi</div>
                        <div class="value">{{ $data->isi }}</div>
                    </div>
                @endif
                <div class="d-flex flex-column gap-1">
                    <div class="label">Tanggal Diusulkan</div>
                    <div class="value">
                        {{ Carbon::parse($data->created_at)->translatedFormat('l, d F Y') }}
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Tanggal Sertifikat</div>
                    <div class="value">
                        {{ Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                @if ($data->user_created == $userId)
                    @if (!$data->is_done)
                        <a href="{{ route('sertif.edit', $data->id) }}"
                            class="btn btn-outline-success py-2 fw-semibold rounded-3 mt-3">
                            Ubah Sertifikat
                        </a>
                        <form action="{{ route('sertif.delete', $data->id) }}" method="POST" id="show-delete-confirm"
                            class="d-flex ">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn text-danger py-2 fw-semibold rounded-3" style="width: 100%">
                                Hapus Sertifikat
                            </button>
                        </form>
                    @endif
                @endif
                @if ($jenisUser == 'admin' && !$data->is_done)
                    <a href="{{ route('sertif.make', $data->id) }}" class="btn btn-success rounded-3 py-2 px-4">
                        Buat Sertifikat
                    </a>
                    <button id="btnReject" class="btn btn-outline-danger rounded-3 py-2 px-4">
                        Tolak Surat
                    </button>
                @endif
            </div>
        </div>
        {{-- Section Kanan --}}
        <div class="col-lg-4">
            <div class="dokumen-card">
                <div>
                    <h2>Pemberi Sertifikat</h2>
                    <div class="divider-green"></div>
                </div>
                @if ($data->jenis_user == 'dosen')
                    <div class="d-flex flex-column gap-1">
                        <div class="label">NIP</div>
                        <div class="value text-capitalize">{{ $data->user_created }}</div>
                    </div>
                @endif
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nama</div>
                    <div class="value text-capitalize">{{ data_get($data, $data->jenis_user . '.nama') }}</div>
                </div>
                @if (optional($data->dosen)->role_id)
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Jabatan</div>
                        <div class="value text-capitalize">{{ $data->dosen->role->role_akses }}</div>
                    </div>
                @endif
            </div>

            <div class="dokumen-card" style="margin-top: 16px">
                <div>
                    <h2>Penerima Sertifikat</h2>
                    <div class="divider-green"></div>
                </div>
                <ul style="margin-bottom: 0px">
                    @foreach ($data->penerimas as $penerima)
                        @if ($penerima->user_penerima)
                            <li style="margin-bottom: 4px">{{ $penerima->mahasiswa->nama }}</li>
                        @else
                            <li style="margin-bottom: 4px">{{ $penerima->nama_penerima }}</li>
                        @endif
                    @endforeach
                </ul>
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
