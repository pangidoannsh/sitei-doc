@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Distribusi Dokuen & Surat
@endsection

@section('sub-title')
    Detail Pengajuan Cuti
@endsection

@section('content')
    <section class="row">
        <div class="col-lg-8">
            <div class="dokumen-card">
                <div>
                    <div class="d-flex align-items-center">
                        <h2 style="width: 100%">Pengajuan Surat Cuti</h2>
                        <button class="btn text-secondary fw-semibold rounded-3" id="btnCopy" style="width:max-content"
                            title="Bagikan" data-slug="{{ route('suratcuti.detail.public', Crypt::encrypt($data->id)) }}">
                            <i class="fa-solid fa-share-nodes"></i>
                        </button>
                    </div>
                    <div class="divider-green"></div>
                </div>
                @switch($data->status)
                    @case('staf_jurusan')
                        <div class="rounded-2 py-3 text-center fw-semibold status-warning">
                            Dalam Pengecekan Ke Staf Administrasi Jurusan
                        </div>
                    @break

                    @case('proses')
                        <div class="rounded-2 py-3 text-center fw-semibold status-warning">
                            Dalam Pengajuan Ke Ketua Jurusan
                        </div>
                    @break

                    @case('diterima')
                        <div class="status-success rounded-2 py-3 text-center fw-semibold">
                            Pengajuan Disetujui: Usulan Cuti Diterima
                        </div>
                    @break

                    @case('ditolak')
                        <div class="rounded-2 py-3 text-center fw-semibold status-danger">
                            Pengajuan Ditolak : {{ $data->alasan_ditolak }}
                        </div>
                    @break

                    @default
                @endswitch
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nomor Telepon</div>
                    <div class="value text-capitalize">{{ $data->nomor_telepon }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Jenis Cuti</div>
                    <div class="value text-capitalize">{{ $data->jenis_cuti }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Alasan Cuti</div>
                    <div class="value text-capitalize">{{ $data->alasan_cuti }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Alamat Selama Cuti</div>
                    <div class="value text-capitalize">{{ $data->alamat_cuti }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Tanggal Usulan</div>
                    <div class="value">
                        {{ Carbon::parse($data->created_at)->translatedFormat('l, d F Y') }}
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Lama Cuti</div>
                    <div class="d-flex gap-3 align-items-center">
                        <span class="value">{{ Carbon::parse($data->mulai_cuti)->translatedFormat('d F Y') }}</span>
                        <span class="text-secondary">-</span>
                        <span class="value">{{ Carbon::parse($data->selesai_cuti)->translatedFormat('d F Y') }}</span>
                        <span class="text-success"> ({{ $data->lama_cuti }} Hari)</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Lampiran</div>
                    @if ($data->url_lampiran || $data->url_lampiran_lokal)
                        @if ($data->url_lampiran_lokal)
                            <a href="{{ asset('storage/' . $data->url_lampiran_lokal) }}" target="_blank"
                                class="btn btn-success px-5 rounded-3" style="width:max-content">
                                Lampiran
                            </a>
                        @endif
                        @if ($data->url_lampiran)
                            <a href="{{ $data->url_lampiran }}" target="_blank" class="btn btn-success px-5 rounded-3"
                                style="width:max-content">
                                Lampiran Link
                            </a>
                        @endif
                    @else
                        <div class="value">(Tidak ada file terlampir)</div>
                    @endif
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Tanda Tangan</div>
                    <div>
                        <img src="{{ asset('storage/' . $data->tanda_tangan) }}" width="64">
                    </div>
                </div>
                <hr>
                @if ($data->user_created == $userId)
                    @if ($data->status != 'diterima')
                        <div class="d-flex flex-column gap-1">
                            @if ($data->status == 'ditolak')
                                <form action="{{ route('suratcuti.delete', $data->id) }}" method="POST"
                                    id="show-delete-confirm">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn text-danger py-2 fw-semibold rounded-3"
                                        style="width: 100%">
                                        Hapus Pengajuan
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('suratcuti.edit', $data->id) }}"
                                    class="btn btn-outline-success py-2 fw-semibold rounded-3">
                                    Ubah Data Pengajuan
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="d-flex flex-column gap-1">
                            <a href="{{ route('suratcuti.download', $data->id) }}"
                                class="btn btn-success py-2 fw-semibold rounded-3">
                                <i class="fa-solid fa-download"></i> Unduh Surat Cuti
                            </a>
                        </div>
                    @endif
                @else
                    @if ($userRole == 5 && $data->status == 'proses')
                        <div class="d-flex flex-column gap-1">
                            <a href="{{ route('suratcuti.approve', $data->id) }}"
                                class="btn btn-success py-2 fw-semibold rounded-3">
                                Setujui Pengajuan
                            </a>
                            <button class="btn text-danger py-2 fw-semibold rounded-3" style="width: 100%" id="btnReject">
                                Tolak Pengajuan
                            </button>
                        </div>
                    @elseif($userRole == 1)
                        <div class="d-flex flex-column gap-1">
                            <button data-toggle="modal" data-target="#isi_penandatangan_modal"
                                class="btn btn-success py-2 fw-semibold rounded-3">
                                Teruskan Pengajuan Ke Ketua Jurusan
                            </button>
                            <button class="btn text-danger py-2 fw-semibold rounded-3" style="width: 100%" id="btnReject">
                                Tolak Pengajuan
                            </button>
                        </div>
                        {{-- Modal Menambah Data Penyetuju Surat --}}
                        <div class="modal fade w-100" id="isi_penandatangan_modal" tabindex="-1" role="dialog"
                            aria-labelledby="accepted" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content p-4 rounded-4 d-flex flex-column gap-4">
                                    <form action="{{ route('suratcuti.acc.admin', $data->id) }}" method="POST"
                                        class="d-flex gap-1 flex-column">
                                        @csrf
                                        @method('post')
                                        <h5 class="modal-title">Yang Menyetujui Surat</h5>
                                        <div class="divider-green"></div>
                                        {{-- NIP --}}
                                        <div>
                                            <label for="nip_penandatangan_akhir" class="fw-semibold">NIP</label>
                                            <input type="text" class="form-control rounded-3 py-4"
                                                name="nip_penandatangan_akhir" id="nip_penandatangan_akhir" required>
                                        </div>
                                        {{-- Nama --}}
                                        <div>
                                            <label for="nama_penandatangan_akhir" class="fw-semibold">Nama</label>
                                            <input type="text" class="form-control rounded-3 py-4"
                                                name="nama_penandatangan_akhir" id="nama_penandatangan_akhir" required>
                                        </div>
                                        {{-- Jabatan --}}
                                        <div>
                                            <label for="jabatan_penandatangan_akhir" class="fw-semibold">Jabatan</label>
                                            <input type="text" class="form-control rounded-3 py-4"
                                                name="jabatan_penandatangan_akhir" id="jabatan_penandatangan_akhir"
                                                required>
                                        </div>
                                        <button type="submit" class="rounded-3 btn mt-3 btn-success py-3">
                                            Kirim
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        {{-- Section Kanan --}}
        <div class="col-lg-4">
            <div class="dokumen-card">
                <div>
                    <h2>Pengaju Surat Cuti</h2>
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
                @if ($data->jenis_user == 'dosen')
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Jabatan</div>
                        <div class="value text-capitalize">
                            {{ optional($data->dosen->role)->role_akses ?? 'Dosen Teknik Elektro' }}</div>
                    </div>
                @else
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Jabatan</div>
                        <div class="value text-capitalize">{{ data_get($data, $data->jenis_user . '.role.role_akses') }}
                        </div>
                    </div>
                @endif
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
                title: 'Hapus Pengajuan Cuti?',
                text: 'Apakah Anda Yakin?',
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.currentTarget.submit()
                }
            })
        })
    </script>
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
                title: 'Tautan surat cuti berhasil disalin ke clipboard!',
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
        function rejectSurat() {
            Swal.fire({
                title: 'Tolak Pengajuan Cuti',
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
                        <form id="reasonForm" action="{{ route('suratcuti.reject', $data->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <label for="alasan_ditolak">Alasan Penolakan :</label>
                            <textarea class="form-control" id="alasan"_ditolak name="alasan_ditolak" rows="4" cols="50" required></textarea>
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
