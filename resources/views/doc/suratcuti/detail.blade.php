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
                    <h2>Pengajuan Surat Cuti</h2>
                    <div class="divider-green"></div>
                </div>
                @switch($data->status)
                    @case('proses')
                        <div class="rounded-2 py-3 text-center fw-semibold text-white" style="background-color: #fbbf24">
                            Dalam Pengajuan Ke Ketua Jurusan
                        </div>
                    @break

                    @case('diterima')
                        <div class="bg-success rounded-2 py-3 text-center fw-semibold text-white">Pengajuan Disetujui: Surat Sudah
                            Dapat Diambil</div>
                    @break

                    @case('ditolak')
                        <div class="bg-danger rounded-2 py-3 text-center fw-semibold text-white">
                            Pengajuan Ditolak
                        </div>
                    @break

                    @default
                @endswitch
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
                <hr>
                @if ($data->user_created == $userId)
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('suratcuti.edit', $data->id) }}"
                            class="btn btn-outline-success py-2 fw-semibold rounded-3">
                            Ubah Pengajuan
                        </a>
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
                        @endif
                    </div>
                @else
                    @if ($userRole == 5)
                        <div class="d-flex flex-column gap-1">
                            <a href="{{ route('suratcuti.approve', $data->id) }}"
                                class="btn btn-success py-2 fw-semibold rounded-3">
                                Setujui Pengajuan
                            </a>
                            <form action="{{ route('suratcuti.reject', $data->id) }}" method="POST"
                                id="show-reject-confirm">
                                @method('delete')
                                @csrf
                                <button type="submit" class="btn text-danger py-2 fw-semibold rounded-3"
                                    style="width: 100%">
                                    Tolak Pengajuan
                                </button>
                            </form>
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
                        <div class="value text-capitalize">{{ $data->dosen->role->role_akses }}</div>
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
                title: 'Hapus Dokumen "{{ $data->nama }}"',
                text: 'Apakah Anda Yakin?',
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Tolak',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.currentTarget.submit()
                }
            })
        })
        $('#show-reject-confirm').submit((e) => {
            const form = $(this).closest("form");
            e.preventDefault();
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
                    e.currentTarget.submit()
                }
            })
        })
    </script>
@endpush
