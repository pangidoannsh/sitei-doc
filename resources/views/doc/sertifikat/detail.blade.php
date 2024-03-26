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
    @include('doc.components.preview-sertif')
    <section class="row pb-3">
        <div class="col-lg-8">
            <div class="dokumen-card">
                <div>
                    <h2>Sertifikat</h2>
                    <div class="divider-green"></div>
                </div>
                @if ($data->status == 'selesai')
                    <div class="status-success rounded-2 py-3 text-center fw-semibold">
                        Sertifikat Telah Dibagikan
                    </div>
                @elseif($data->status == 'ditolak')
                    <div class="status-danger rounded-2 py-3 text-center fw-semibold">
                        <div>Pengajuan Ditolak: <span class="fw-medium">{{ $data->alasan_ditolak }}</span></div>
                        <div>Ditolak Oleh : <span class="fw-medium">
                                @if ($data->rejectByDosen)
                                    {{ $data->rejectByDosen->nama }}
                                @elseif ($data->rejectByAdmin)
                                    {{ $data->rejectByAdmin->role->role_akses }}
                                @endif
                            </span></div>
                    </div>
                @else
                    <div class="rounded-2 py-3 text-center fw-semibold status-warning">
                        @switch($data->status)
                            @case('staf_jurusan')
                                Menunggu Persetujun Staf Administrasi Jurusan
                            @break

                            @case('kajur')
                                Menunggu Persetujun Ketua Jurusan
                            @break

                            @case('meminta_persetujuan')
                                Menunggu Persetujun Penandatangan Sertifikat
                            @break

                            @case('disetujui')
                                Menunggu Proses Pelengkapan Sertifikat
                            @break

                            @default
                        @endswitch
                    </div>
                @endif
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nama Sertifikat</div>
                    <div class="value text-capitalize">{{ $data->nama }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Penandatangan</div>
                    <div class="value text-capitalize">{{ $data->signed->nama }}</div>
                </div>
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
                <hr>
                @if ($data->user_created == $userId)
                    @if ($data->status != 'selesai')
                        <div class="d-flex flex-column gap-2">
                            @if ($data->status == 'staf_jurusan')
                                <a href="{{ route('sertif.edit', $data->id) }}"
                                    class="btn btn-outline-success py-2 fw-semibold rounded-3 mt-3">
                                    Ubah Sertifikat
                                </a>
                            @endif
                            @if ($data->status == 'ditolak' || $data->status == 'staf_jurusan')
                                <form action="{{ route('sertif.delete', $data->id) }}" method="POST"
                                    id="show-delete-confirm" class="d-flex ">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn text-danger py-2 fw-semibold rounded-3"
                                        style="width: 100%">
                                        Hapus Sertifikat
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                @endif
                <div class="d-flex flex-column gap-2">
                    @if ($jenisUser == 'admin')
                        @if (Auth::guard('web')->user()->role_id == 1 && $data->status == 'staf_jurusan')
                            <a href="{{ route('sertif.acc.admin', $data->id) }}"
                                class="btn btn-success rounded-3 py-2 px-4">
                                Setujui Pembuatan Sertifikat
                            </a>
                            <button id="btnReject" type="submit" style="width: 100%"
                                class="btn text-danger fw-semibold rounded-3 py-2 px-4">
                                Tolak Pembuatan Sertifikat
                            </button>
                        @elseif(Auth::guard('web')->user()->role_id == 1 && $data->status == 'disetujui')
                            <a href="{{ route('sertif.make', $data->id) }}" class="btn btn-success rounded-3 py-2 px-4">
                                Lengkapi Sertifikat
                            </a>
                        @endif
                    @else
                        @if (Auth::guard($jenisUser == 'plp' ? 'web' : $jenisUser)->user()->role_id == 5)
                            @if ($data->status == 'kajur')
                                @if ($data->sign_by === $userId)
                                    <a href="{{ route('sertif.acc.sign', $data->id) }}"
                                        class="btn btn-success rounded-3 py-2 px-4">
                                        Setujui Sertifikat
                                    </a>
                                @elseif ($data->status == 'kajur')
                                    <a href="{{ route('sertif.acc.kajur', $data->id) }}"
                                        class="btn btn-success rounded-3 py-2 px-4">
                                        Setujui Pembuatan Sertifikat
                                    </a>
                                @endif
                                <button id="btnReject" type="submit"
                                    class="btn text-danger fw-semibold rounded-3 py-2 px-4" style="width: 100%">
                                    Tolak Pembuatan Sertifikat
                                </button>
                            @endif
                        @elseif ($data->sign_by == $userId && $data->status == 'meminta_persetujuan')
                            <a href="{{ route('sertif.acc.sign', $data->id) }}"
                                class="btn btn-success rounded-3 py-2 px-4">
                                Setujui Sertifikat
                            </a>
                            <button id="btnReject" type="submit" class="btn text-danger fw-semibold rounded-3 py-2 px-4"
                                style="width: 100%">
                                Tolak Pembuatan Sertifikat
                            </button>
                        @endif
                    @endif
                </div>
            </div>
            @if ($data->status == 'selesai')
                <div class="dokumen-card mt-3">
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
            @endif
        </div>
        {{-- Section Kanan --}}
        <div class="col-lg-4">
            {{-- Penerima before Done --}}
            @if ($data->status != 'selesai')
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
                        <h2>Calon Penerima Sertifikat</h2>
                        <div class="divider-green"></div>
                    </div>
                    <ul style="margin-bottom: 0px">
                        @foreach ($data->penerimas as $penerima)
                            <li style="margin-bottom: 4px">
                                {{ data_get($penerima, $penerima->jenis_penerima . '.nama') ?? $penerima->nama_penerima }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="dokumen-card">
                    <div>
                        <h2>Penerima Sertifikat</h2>
                        <div class="divider-green"></div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        @foreach ($data->penerimas as $penerima)
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column gap-1">
                                    <div class="label">Nama</div>
                                    <div class="value text-capitalize">
                                        {{ data_get($penerima, $penerima->jenis_penerima . '.nama') ?? $penerima->nama_penerima }}
                                    </div>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <button class="btn text-secondary fw-semibold rounded-3 btnCopy"
                                        style="width:max-content" title="Bagikan"
                                        data-slug="{{ route('sertif.penerima', $penerima->slug) }}">
                                        <i class="fa-solid fa-share-nodes"></i>
                                    </button>
                                    <a href="{{ route('sertif.download', $penerima->slug) }}"
                                        class="btn btn-outline-success rounded-3 btnDownload" style="width:max-content"
                                        title="download sertifikat">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            @endif
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
    {{-- Delete Function --}}
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
        $('#btnReject').submit((e) => {
            console.log('test');
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
    {{-- Reject Function --}}
    <script>
        function rejectSurat() {
            Swal.fire({
                title: 'Tolak Pembuatan Sertifikat',
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
                        <form id="reasonForm" action="{{ route('sertif.reject', $data->id) }}" method="POST">
                            @csrf
                            @method('post')
                            <label for="alasan_ditolak">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan" name="alasan_ditolak" rows="4" cols="50" required></textarea>
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
    {{-- Copy Function --}}
    <script>
        $('.btnCopy').click(function() {
            var slugToCopy = $(this).data('slug');

            var tempTextarea = $('<textarea>');
            $('body').append(tempTextarea);
            tempTextarea.val(slugToCopy).select();
            document.execCommand('copy');

            tempTextarea.remove();

            Swal.fire({
                toast: true,
                icon: 'success',
                title: 'Tautan sertifikat berhasil disalin ke clipboard!',
                animation: false,
                position: 'bottom',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: false,
                showClass: {
                    popup: "",
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        });
        $(".btnDownload").click(function() {
            Swal.fire({
                toast: true,
                icon: 'info',
                title: 'Sedang mengunduh sertifikat',
                animation: false,
                position: 'bottom-right',
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
