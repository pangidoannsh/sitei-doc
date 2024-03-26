@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Distribusi Dokuen & Surat
@endsection

@section('sub-title')
    Detail Dokumen
@endsection

@section('content')
    <section class="row pb-5">
        <div class="col-lg-8">
            <div class="dokumen-card">
                <div>
                    <div class="d-flex align-items-center">
                        <h2 style="width: 100%">Arsip Dokumen</h2>
                        <button class="btn text-secondary fw-semibold rounded-3" id="btnCopy" style="width:max-content"
                            title="Bagikan" data-slug="{{ route('dokumen.detail.public', Crypt::encrypt($dokumen->id)) }}">
                            <i class="fa-solid fa-share-nodes"></i>
                        </button>
                    </div>
                    <div class="divider-green"></div>
                </div>

                <div class="d-flex flex-column gap-1">
                    <div class="label">Nomor Dokumen</div>
                    <div class="value text-capitalize">{{ $dokumen->nomor_dokumen }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nama Dokumen</div>
                    <div class="value text-capitalize">{{ $dokumen->nama }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Kategori</div>
                    <div class="value text-capitalize">{{ $dokumen->kategori }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Keterangan</div>
                    <div class="value">{{ $dokumen->keterangan ?? '-' }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Semester</div>
                    <div class="value text-capitalize">{{ $dokumen->semester }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Tanggal Dokumen</div>
                    <div class="value">
                        {{ Carbon::parse($dokumen->tgl_dokumen)->translatedFormat('l, d F Y') }}
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Tanggal Usulan</div>
                    <div class="value">
                        {{ Carbon::parse($dokumen->created_at)->translatedFormat('l, d F Y') }}
                    </div>
                </div>
                @if ($dokumen->nomor_dokumen)
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Nomor Dokumen</div>
                        <div class="value">{{ $dokumen->nomor_dokumen }}</div>
                    </div>
                @endif
                <div class="d-flex flex-column gap-1">
                    <div class="label">Dokumen</div>
                    @if (!$dokumen->url_dokumen && !$dokumen->url_dokumen_lokal)
                        (Tidak ada dokumen dilampirkan)
                    @endif
                    @if ($dokumen->url_dokumen)
                        <a href="{{ $dokumen->url_dokumen }}" target="_blank" class="btn btn-success px-5 rounded-2"
                            style="width:max-content">
                            Lihat Lampiran
                        </a>
                    @endif
                    @if ($dokumen->url_dokumen_lokal)
                        <a href="{{ asset('storage/' . $dokumen->url_dokumen_lokal) }}" target="_blank"
                            class="btn btn-success px-5 rounded-2" style="width:max-content">
                            Lihat Dokumen
                        </a>
                    @endif
                </div>
                <hr>
                @if ($dokumen->user_created == $userId)
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('dokumen.edit', $dokumen->id) }}"
                            class="btn btn-outline-success py-2 fw-semibold rounded-3">
                            Ubah Dokumen
                        </a>
                        <form action="{{ route('dokumen.delete', $dokumen->id) }}" method="POST" id="show-delete-confirm">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn text-danger py-2 fw-semibold rounded-3" style="width: 100%">
                                Hapus Dokumen
                            </button>
                        </form>
                    </div>
                @else
                    @if ($mentioned)
                        <div class="d-flex flex-column gap-1">
                            @if (!$mentioned->accepted)
                                <a href="{{ route('dokumen.mention.accept', ['dokumen_id' => $dokumen->id, 'user_mentioned' => $userId]) }}"
                                    class="btn btn-success py-2 fw-semibold rounded-3">
                                    Terima Kiriman
                                </a>
                            @endif
                            <form
                                action="{{ route('dokumen.mention.delete', ['dokumen_id' => $dokumen->id, 'user_mentioned' => $userId]) }}"
                                method="POST" id="show-reject-confirm">
                                @method('delete')
                                @csrf
                                <button type="submit" class="btn text-danger py-2 fw-semibold rounded-3"
                                    style="width: 100%">
                                    {{ $mentioned->accepted ? 'Hapus Dari Arsip' : 'Tolak Kiriman' }}
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
                    <h2>Pembuat Arsip</h2>
                    <div class="divider-green"></div>
                </div>
                @if ($dokumen->jenis_user == 'dosen')
                    <div class="d-flex flex-column gap-1">
                        <div class="label">NIP</div>
                        <div class="value text-capitalize">{{ $dokumen->user_created }}</div>
                    </div>
                @endif
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nama</div>
                    <div class="value text-capitalize">
                        {{ data_get($dokumen, ($dokumen->jenis_user == 'plp' ? 'admin' : $dokumen->jenis_user) . '.nama') }}
                    </div>
                </div>
                @if ($dokumen->jenis_user == 'dosen')
                    @if (optional($dokumen->dosen)->role_id)
                        <div class="d-flex flex-column gap-1">
                            <div class="label">Jabatan</div>
                            <div class="value text-capitalize">{{ $dokumen->dosen->role->role_akses }}</div>
                        </div>
                    @endif
                @else
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Jabatan</div>
                        <div class="value text-capitalize">{{ $dokumen->admin->role->role_akses }}</div>
                    </div>
                @endif
            </div>
            @if ($dokumen->user_created === $userId)
                <div class="dokumen-card" style="margin-top: 16px">
                    <div>
                        <h2>Penerima Arsip</h2>
                        <div class="divider-green"></div>
                    </div>
                    <ul style="margin-bottom: 0">
                        @foreach ($dokumen->mentions as $mention)
                            <li style="margin-bottom: 4px">
                                {{ data_get($mention, $mention->jenis_user . '.nama') }}
                                @if ($mention->jenis_user == 'dosen')
                                    <span class="fw-semibold">({{ $mention->dosen->nama_singkat }})</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
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
        $('#show-delete-confirm').submit((e) => {
            const form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Dokumen "{{ $dokumen->nama }}"',
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
        $('#show-reject-confirm').submit((e) => {
            const form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Dokumen "{{ $dokumen->nama }}"',
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
    </script>
@endpush
