@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Distribusi Dokuen & Surat
@endsection

@section('sub-title')
    Detail Pengumuman
@endsection

@section('content')
    <section class="row">
        <div class="col-lg-8">
            <div class="dokumen-card">
                <div>
                    <h2>{{ $data->nama }}</h2>
                    <div class="divider-green"></div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Kategori</div>
                    <div class="value text-capitalize">{{ $data->kategori }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Isi Pengumuman</div>
                    <div class="value">{{ $data->isi }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Batas Pengumuman</div>
                    <div class="value">{{ Carbon::parse($data->tgl_batas_pengumuman)->translatedFormat('l, d F Y') }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Lampiran</div>
                    @if ($data->url_dokumen)
                        <a href="@if ($data->is_local_file) {{ asset('storage/' . $data->url_dokumen) }}
                        @else
                            {{ $data->url_dokumen }} @endif"
                            target="_blank" class="btn btn-outline-success px-5 rounded-3" style="width:max-content">
                            Lihat Lampiran
                        </a>
                    @else
                        <div class="value">(Tidak ada file terlampir)</div>
                    @endif
                </div>
                @if ($data->user_created == $userId)
                    <hr>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('pengumuman.edit', $data->id) }}"
                            class="btn btn-success py-2 fw-semibold rounded-3">
                            Ubah Pengumuman
                        </a>
                        <form action="{{ route('pengumuman.delete', $data->id) }}" method="POST" id="show-delete-confirm"
                            class="d-flex ">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn text-danger py-2 fw-semibold rounded-3" style="width: 100%">
                                Hapus Pengumuman
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            {{-- <a href="{{ url()->previous() }}" class="btn btn-outline-success mt-4 py-1 fw-semibold"><i
                    class="fa-solid fa-arrow-left"></i>
                Kembali</a> --}}
        </div>
        <div class="col-lg-4">
            <div class="dokumen-card">
                <div>
                    <h2>Pembuat Pengumuman</h2>
                    <div class="divider-green"></div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <div class="label">Nama</div>
                    <div class="value text-capitalize">{{ data_get($data, $data->jenis_user . '.nama') }}</div>
                </div>
                @if (data_get($data, $data->jenis_user . '.role_id'))
                    <div class="d-flex flex-column gap-1">
                        <div class="label">Jabatan</div>
                        <div class="value text-capitalize">
                            {{ data_get($data, $data->jenis_user . '.role.role_akses') }}</div>
                    </div>
                @endif
            </div>
            <div class="dokumen-card" style="margin-top: 16px">
                <div>
                    <h2>Penerima Pengumuman</h2>
                    <div class="divider-green"></div>
                </div>
                @if ($data->for_all_dosen && $data->for_all_staf && $data->for_all_mahasiswa)
                    <div class="fw-medium" style="font-size: 16px; color: #424242">Seluruh Jurusan Teknik Elektro</div>
                @else
                    @if (count($data->mentions) > 0 || $data->for_all_dosen || $data->for_all_staf || $data->for_all_mahasiswa)
                        <ul style="margin-bottom: 0">
                            @if ($data->for_all_dosen)
                                <li style="margin-bottom: 4px">Seluruh Dosen</li>
                            @endif
                            @if ($data->for_all_staf)
                                <li style="margin-bottom: 4px">Seluruh Staf</li>
                            @endif
                            @if ($data->for_all_mahasiswa)
                                <li style="margin-bottom: 4px">Seluruh Mahasiswa</li>
                            @endif
                            @foreach ($data->mentions as $mention)
                                <li style="margin-bottom: 4px">
                                    @if (!$mention->dosen && !$mention->admin && !$mention->mahasiswa)
                                        @switch($mention->user_mentioned)
                                            @case('s1ti_all')
                                                Seluruh Mahasiswa Teknik Informatika S1
                                            @break

                                            @case('s1te_all')
                                                Seluruh Mahasiswa Teknik Elektro S1
                                            @break

                                            @case('d3te_all')
                                                Seluruh Mahasiswa Teknik Elektro D3
                                            @break

                                            @default
                                        @endswitch
                                    @else
                                        {{ data_get($mention, $mention->jenis_user . '.nama') }}
                                        @switch($mention->jenis_user)
                                            @case('dosen')
                                                <span class="fw-semibold">({{ $mention->dosen->nama_singkat }})</span>
                                            @break

                                            @case('mahasiswa')
                                                <span class="fw-semibold">({{ $mention->mahasiswa->angkatan }})</span>
                                            @break

                                            @default
                                        @endswitch
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </div>
        </div>
    </section>
@endsection

@section('footer')
    <section class="bg-dark p-1">
        <div class="container">
            <p class="developer">Dikembangkan oleh Prodi Teknik Informatika UNRI <a class="text-success fw-bold"
                    formtarget="_blank" target="_blank" href="https://pangidoannsh.vercel.app">( Pangidoan Nugroho Syahputra
                    Harahap )</a></p>
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
                confirmButtonText: 'Hapus',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.currentTarget.submit()
                }
            })
        })
    </script>
@endpush
