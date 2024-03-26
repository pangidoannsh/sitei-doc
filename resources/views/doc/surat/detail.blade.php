@extends('doc.main-layout')

@php
    use Carbon\Carbon;
    function getProdi($prodiId)
    {
        switch ($prodiId) {
            case 1:
                return 'D3 TE';
                break;
            case 2:
                return 'S1 TE';
                break;
            case 3:
                return 'S1 TI';
                break;

            default:
                return 'UNDEFINED';
        }
    }
@endphp

@section('title')
    SITEI | Distribusi Dokuen & Surat
@endsection

@section('sub-title')
    Detail Pengajuan Surat
@endsection

@section('content')
    <section class="row pb-5">
        <div class="col-lg-8">
            <div class="dokumen-card">
                <div>
                    <div class="d-flex align-items-center">
                        <h2 style="width: 100%">Pengajuan Surat</h2>
                        <button class="btn text-secondary fw-semibold rounded-3" id="btnCopy" style="width:max-content"
                            title="Bagikan" data-slug="{{ route('surat.detail.public', Crypt::encrypt($surat->id)) }}">
                            <i class="fa-solid fa-share-nodes"></i>
                        </button>
                    </div>
                    <div class="divider-green"></div>
                </div>
                @switch($surat->status)
                    @case('staf_prodi')
                    @case('kaprodi')

                    @case('staf_jurusan')
                    @case('kajur')
                        <div class="rounded-2 py-3 text-center fw-semibold status-warning">
                            {{ $surat->keterangan_status }}
                            @if ($surat->status == 'staf_prodi' || $surat->status == 'kaprodi')
                                {{ getProdi($surat->prodi_user) }}
                            @endif
                        </div>
                    @break

                    @case('diterima')
                    @case('selesai')
                        <div class="status-success rounded-2 py-3 text-center fw-semibold">{{ $surat->keterangan_status }}
                        </div>
                    @break

                    @case('ditolak')
                        <div class="status-danger rounded-2 py-3 text-center fw-semibold">
                            <div>Pengajuan Ditolak: <span class="fw-medium">{{ $surat->alasan_ditolak }}</span></div>
                            <div>Ditolak Oleh : <span class="fw-medium">{{ $surat->rejection->role_akses }}</span></div>
                        </div>
                    @break

                    @default
                @endswitch
                <div class="d-flex flex-column gap-1">
                    <div class="label">Ditujukan Kepada</div>
                    <div class="value text-capitalize">{{ $surat->penerima->role_akses }}</div>
                </div>
                @if ($surat->nomor_surat)
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Nomor Surat</div>
                        <div class="value text-capitalize">{{ $surat->nomor_surat }}</div>
                    </div>
                @endif
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nama Surat</div>
                    <div class="value text-capitalize">{{ $surat->nama }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Keterangan</div>
                    <div class="value">{{ $surat->keterangan }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Tanggal Pengajuan</div>
                    <div class="value">
                        {{ Carbon::parse($surat->created_at)->translatedFormat('l, d F Y') }}
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Lampiran</div>
                    @if ($surat->url_lampiran || $surat->url_lampiran_lokal)
                        @if ($surat->url_lampiran_lokal)
                            <a href="{{ asset('storage/' . $surat->url_lampiran_lokal) }}" target="_blank"
                                class="btn btn-outline-success px-5 rounded-3" style="width:max-content">
                                Lihat Lampiran
                            </a>
                        @endif
                        @if ($surat->url_lampiran)
                            <a href="{{ $surat->url_lampiran }}" target="_blank"
                                class="btn btn-outline-success px-5 rounded-3" style="width:max-content">
                                Link
                            </a>
                        @endif
                    @else
                        <div class="value opacity-75">(Tidak ada file terlampir)</div>
                    @endif
                </div>
                @if ($surat->status === 'selesai')
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Softfile Surat</div>
                        <div>
                            <a class="btn btn-success rounded-3 px-4" target="_blank"
                                href="{{ asset('storage/' . $surat->url_surat_jadi) }}">Lihat Surat</a>
                        </div>
                    </div>
                @endif
                @if ($surat->user_created == $userId)
                    @if ($surat->status == 'staf_prodi')
                        <a href="{{ route('surat.edit', $surat->id) }}"
                            class="btn btn-outline-success py-2 fw-semibold rounded-3 mt-3">
                            Ubah Pengajuan
                        </a>
                    @endif
                    @if ($surat->status == 'ditolak')
                        <form action="{{ route('surat.delete', $surat->id) }}" method="POST" id="show-delete-confirm"
                            class="d-flex ">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn btn-outline-danger py-2 fw-semibold rounded-3"
                                style="width: 100%">
                                Hapus Pengajuan
                            </button>
                        </form>
                    @endif
                @endif
                @if ($jenisUser == 'admin')
                    @if (Auth::guard('web')->user()->role_id === 1 && $surat->status == 'staf_jurusan')
                        <a href="{{ route('surat.acc.stafjurusan', $surat->id) }}"
                            class="btn btn-success rounded-3 py-2 px-4">
                            Setujui Pengajuan Surat
                        </a>
                        <button id="btnReject" class="btn text-danger fw-bold rounded-3 py-2 px-4" style="margin-top: -8px">
                            Tolak Surat
                        </button>
                    @endif
                    @if ($surat->status == 'staf_prodi')
                        <a href="{{ route('surat.acc.stafprodi', $surat->id) }}"
                            class="btn btn-success rounded-3 py-2 px-4">
                            Setujui Pengajuan Surat
                        </a>
                        <button data-toggle="modal" data-target="#change_tujuan_modal"
                            class="btn btn-outline-success rounded-3 py-2 px-4">
                            Ubah Tujuan
                        </button>
                        <button id="btnReject" class="btn text-danger fw-bold rounded-3 py-2 px-4" style="margin-top: -8px">
                            Tolak Surat
                        </button>
                        {{-- Modal Ubah Tujuan Surat --}}
                        <div class="modal fade w-100" id="change_tujuan_modal" tabindex="-1" role="dialog"
                            aria-labelledby="accepted" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content p-4 rounded-4 d-flex flex-column gap-4">
                                    <form action="{{ route('surat.edit.tujuan', $surat->id) }}" method="POST"
                                        enctype="multipart/form-data" class="d-flex gap-1 flex-column">
                                        @csrf
                                        @method('post')
                                        <h5 class="modal-title">Ubah Tujuan Surat</h5>
                                        <div class="divider-green"></div>
                                        <div class="mt-2">
                                            <label for="kepada" class="fw-semibold">Tujuan Surat</label>
                                            <div class="input-group">
                                                <select name="tujuan_surat" id="kepada"
                                                    class="text-secondary text-capitalize rounded-3 text-capitalize @error('tujuan_surat') border border-danger @enderror">
                                                    <option value="" class="text-capitalize" selected disabled>
                                                        Pilih
                                                        Tujuan Surat</option>
                                                    @if (isset($dosens))
                                                        @foreach ($dosens as $dosen)
                                                            <option value="{{ $dosen->role_id }}" class="text-capitalize"
                                                                {{ $surat->role_tujuan == $dosen->role_id ? 'selected' : '' }}>
                                                                {{ $dosen->nama }}
                                                                ({{ $dosen->role->role_akses }})
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('tujuan_surat')
                                                    <div class="text-danger mt-1" style="font-size: 11px">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="rounded-3 btn mt-3 btn-success py-3">
                                            Kirim
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @elseif($surat->status === 'diterima' && $surat->role_handler == Auth::guard('web')->user()->role_id)
                        <button data-toggle="modal" data-target="#done_modal"
                            class="btn btn-success rounded-3 py-2 px-4">
                            Selesaikan Surat
                        </button>
                        {{-- Modal Penyelesaian Surat --}}
                        <div class="modal fade w-100" id="done_modal" tabindex="-1" role="dialog"
                            aria-labelledby="accepted" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content p-4 rounded-4 d-flex flex-column gap-4">
                                    <form action="{{ route('surat.done', $surat->id) }}" method="POST"
                                        enctype="multipart/form-data" class="d-flex gap-1 flex-column">
                                        @csrf
                                        @method('post')
                                        <h5 class="modal-title">Penyelesaian Surat</h5>
                                        <div class="divider-green"></div>
                                        <div class="mt-2">
                                            <label for="nomor_surat" class="fw-semibold">Nomor Surat<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input id="nomor_surat" name="nomor_surat" type="text"
                                                class="form-control rounded-3 py-4" required>
                                        </div>
                                        <div class="mt-2">
                                            <label for="surat" class="fw-semibold">Hasil Surat<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input id="surat" name="surat" type="file"
                                                class="form-control rounded-3" required>
                                        </div>
                                        <button type="submit" class="rounded-3 btn mt-3 btn-success py-3">
                                            Selesaikan Surat
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                @if ($jenisUser == 'dosen')
                    @if (Auth::guard('dosen')->user()->role_id == $surat->role_tujuan &&
                            !in_array($surat->status, ['diterima', 'ditolak', 'selesai']))
                        <a href="{{ route('surat.accept', $surat->id) }}" class="btn btn-success rounded-3 py-2 px-4">
                            Setujui Pengajuan Surat
                        </a>
                        <button id="btnReject" class="btn text-danger fw-bold rounded-3 py-2 px-4"
                            style="margin-top: -8px">
                            Tolak Surat
                        </button>
                    @elseif($isKaprodi && $surat->status == 'kaprodi')
                        <a href="{{ route('surat.acc.kaprodi', $surat->id) }}"
                            class="btn btn-success rounded-3 py-2 px-4">
                            Setujui Pengajuan Surat
                        </a>
                        <button id="btnReject" class="btn text-danger fw-bold rounded-3 py-2 px-4"
                            style="margin-top: -8px">
                            Tolak Surat
                        </button>
                    @endif
                @endif

            </div>
        </div>
        {{-- Section Kanan --}}
        <div class="col-lg-4">
            <div class="dokumen-card">
                <div>
                    <h2>Pengaju Surat</h2>
                    <div class="divider-green"></div>
                </div>
                @if (!in_array($surat->jenis_user, ['admin', 'plp']))
                    <div class="d-flex flex-column gap-1">
                        <div class="label">{{ $surat->jenis_user == 'dosen' ? 'NIP' : 'NIM' }}</div>
                        <div class="value text-capitalize">{{ $surat->user_created }}</div>
                    </div>
                @endif
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nama</div>
                    <div class="value text-capitalize">
                        {{ data_get($surat, ($surat->jenis_user == 'plp' ? 'admin' : $surat->jenis_user) . '.nama') }}</div>
                </div>
                @if (optional($surat->dosen)->role_id)
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Jabatan</div>
                        <div class="value text-capitalize">{{ $surat->dosen->role->role_akses }}</div>
                    </div>
                @endif
                @if ($surat->jenis_user == 'mahasiswa')
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Prodi</div>
                        <div class="value text-capitalize">{{ $surat->mahasiswa->prodi->nama_prodi }}</div>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Angkatan</div>
                        <div class="value text-capitalize">{{ $surat->mahasiswa->angkatan }}</div>
                    </div>
                @endif
            </div>
            @if ($surat->status === 'selesai')
                <div class="dokumen-card" style="margin-top: 16px">
                    <div>
                        <h2>Pengurus Surat</h2>
                        <div class="divider-green"></div>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Jabatan</div>
                        <div class="value text-capitalize">
                            {{ $surat->handler->role_akses }}
                        </div>
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
    <script>
        $('#btnCopy').click(function() {
            var slugToCopy = $(this).data('slug');

            var tempTextarea = $('<textarea>');
            $('body').append(tempTextarea);
            tempTextarea.val(slugToCopy).select();
            document.execCommand('copy');

            tempTextarea.remove();

            Swal.fire({
                toast: true,
                icon: 'success',
                title: 'Tautan usulan surat berhasil disalin ke clipboard!',
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
    </script>
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
