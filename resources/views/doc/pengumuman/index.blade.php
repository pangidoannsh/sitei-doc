@extends('doc.main-layout')

@php
    use Carbon\Carbon;
    function getKeteranganCuti($status)
    {
        switch ($status) {
            case 'proses':
                return 'Sedang Dalam Pengajuan';
                break;
            case 'ditolak':
                return 'Pengajuan Ditolak';
                break;
            case 'diterima':
                return 'Pengajuan Diterima';
                break;
            default:
                break;
        }
    }
    function displayPenerimaPengumuman($data)
    {
        $jumlahTampil = 1;
        $penerima = [];
        if ($data->for_all_dosen && $data->for_all_staf && $data->for_all_mahasiswa) {
            array_push($penerima, 'Seluruh Jurusan Teknik Elektro');
            return $penerima;
        }
        if ($data->for_all_dosen) {
            array_push($penerima, 'Seluruh Dosen');
            $jumlahTampil--;
        }
        if ($data->for_all_staf) {
            array_push($penerima, 'Seluruh Staf');
            $jumlahTampil--;
        }
        if ($data->for_all_mahasiswa) {
            array_push($penerima, 'Seluruh Mahasiswa');
        }
        foreach ($data->mentions as $index => $mention) {
            if ($index <= $jumlahTampil) {
                if (!$mention->dosen && !$mention->admin && !$mention->mahasiswa) {
                    switch ($mention->user_mentioned) {
                        case 's1ti_all':
                            array_push($penerima, 'Seluruh Teknik Informatika S1');
                            break;
                        case 's1te_all':
                            array_push($penerima, 'Seluruh Teknik Elektro S1');
                            break;
                        case 'd3te_all':
                            array_push($penerima, 'Seluruh Teknik Elektro D3');
                            break;

                        default:
                            break;
                    }
                } else {
                    if ($mention->jenis_user == 'dosen') {
                        array_push($penerima, $mention->dosen->nama_singkat);
                    } else {
                        array_push($penerima, data_get($mention, $mention->jenis_user . '.nama'));
                    }
                }
            }
        }
        return $penerima;
    }
@endphp

@section('title')
    SITEI | Distribusi Surat & Dokumen
@endsection
@section('sub-title')
    Distribusi Surat & Dokumen
@endsection
@section('content')
    @include('doc.components.add-usulan')
    <div class="contariner card p-4">
        <ul class="breadcrumb col-lg-12">
            <li>
                <a href="{{ route('doc.index') }}" class="px-1">
                    Usulan Terbaru
                </a>
            </li>
            <span class="px-2">|</span>
            <li>
                <a href="#" class="breadcrumb-item active fw-bold text-success px-1">
                    Pengumuman (<span>{{ count($pengumumans) }}</span>)
                </a>
            </li>
            <span class="px-2">|</span>
            <li>
                <a href="{{ route('doc.arsip') }}" class="px-1">
                    Arsip
                </a>
            </li>
            @if (optional(Auth::guard('web')->user())->role_id == 1 || optional(Auth::guard('dosen')->user())->role_id == 5)
                <span class="px-2">|</span>
                <li>
                    <a href="{{ route('arsip.jurusan') }}" class="px-1">
                        Arsip Jurusan
                    </a>
                </li>
            @elseif (in_array(optional(Auth::guard('web')->user())->role_id, [2, 3, 4]) ||
                    in_array(optional(Auth::guard('dosen')->user())->role_id, [6, 7, 8]))
                <span class="px-2">|</span>
                <li>
                    <a href="{{ route('arsip.prodi') }}" class="px-1">
                        Arsip Prodi
                    </a>
                </li>
            @endif
        </ul>

        <table class="table table-responsive-lg table-bordered table-striped" style="width:100%" id="datatables">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" scope="col">Nama</th>
                    <th class="text-center" scope="col">Pengusul</th>
                    <th class="text-center" scope="col">Penerima</th>
                    {{-- <th class="text-center" scope="col">Status</th> --}}
                    <th class="text-center" scope="col">Tanggal Usulan</th>
                    <th class="text-center" scope="col">Batas Pengumuman</th>
                    <th class="text-center" scope="col">Jenis/Kategori</th>
                    <th class="text-center" scope="col">Keterangan</th>
                    {{-- <th class="text-center" scope="col">Semester</th> --}}
                    <th class="text-center" scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengumumans->sortByDesc('created_at') as $pengumuman)
                    <tr>
                        {{-- Nama --}}
                        <td class="text-center" style="overflow: hidden">
                            <div class="ellipsis">
                                @if ($pengumuman->jenisDokumen == 'surat_cuti')
                                    Cuti Pengajuan Cuti {{ $pengumuman->jenis_cuti }}
                                @else
                                    {{ $pengumuman->nama }}
                                @endif
                            </div>
                        </td>
                        {{-- Pengusul --}}
                        <td class="text-center">
                            @if (data_get($pengumuman, $pengumuman->jenis_user . '.role_id'))
                                {{ data_get($pengumuman, $pengumuman->jenis_user . '.role.role_akses') }}
                                ({{ optional($pengumuman->dosen)->nama_singkat }})
                            @else
                                {{ data_get($pengumuman, $pengumuman->jenis_user . '.nama') }}
                            @endif
                        </td>
                        {{-- Penerima --}}
                        <td class="text-center" style="overflow: hidden">
                            @php
                                $penerimas = displayPenerimaPengumuman($pengumuman);
                            @endphp
                            <div class="ellipsis">
                                @if (count($penerimas) == 0)
                                    <div>(Tidak Ada)</div>
                                @endif
                                @foreach ($penerimas as $penerima)
                                    <div>
                                        @if (count($penerimas) == 1)
                                            <span>{{ $penerima }}</span>
                                        @else
                                            <span>{{ $loop->iteration }}.</span>
                                            <span>{{ $penerima }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        {{-- Status --}}
                        {{-- <td
                            class="text-center text-uppercase 
                        @switch($pengumuman->jenisDokumen)
                            @case('pengumuman')
                                bg-status-pengumuman
                            @break

                            @case('dokumen')
                               bg-status-dokumen
                            @break
                            @case('surat_cuti')
                                bg-status-suratcuti
                            @break

                            @default
                        @endswitch">
                            @foreach (explode('_', $pengumuman->jenisDokumen) as $name)
                                {{ $name }}
                            @endforeach
                        </td> --}}
                        {{-- Tanggal Usulan --}}
                        <td class="text-center" style="overflow: hidden">
                            <div class="ellipsis-2">
                                {{ Carbon::parse($pengumuman->created_at)->translatedFormat('l, d F Y') }}
                            </div>
                        </td>
                        {{-- Batas Pengumuman --}}
                        <td class="text-center">
                            {{ Carbon::parse($pengumuman->tgl_batas_pengumuman)->translatedFormat('l, d F Y') }}
                        </td>
                        {{-- Jenis/Kategori --}}
                        <td class="text-center text-capitalize">
                            {{ $pengumuman->kategori }}
                        </td>
                        {{-- Isi/Keterangan --}}
                        <td class="text-center" style="overflow: hidden">
                            <div class="ellipsis-2">
                                {{ $pengumuman->isi }}
                            </div>
                        </td>
                        {{-- Semester --}}
                        {{-- <td class="text-center text-capitalize">
                            @if ($pengumuman->jenisDokumen == 'dokumen')
                                {{ $pengumuman->semester }}
                            @endif
                        </td> --}}
                        {{-- Aksi --}}
                        <td class="text-center" style="width: max-content">
                            <div class="d-flex gap-lg-3 gap-2 justify-content-center" style="width: 100%">
                                <div>
                                    <a class="badge btn btn-info p-1 rounded-lg" style="cursor:pointer;"
                                        title="Lihat detail" href="{{ route('pengumuman.detail', $pengumuman->id) }}">
                                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                                    </a>
                                </div>
                                @if ($pengumuman->dosen->nip === $userId)
                                    <div>
                                        <a class="badge btn btn-warning p-1 rounded-lg text-white" style="cursor:pointer;"
                                            href="{{ route('pengumuman.edit', $pengumuman->id) }}" title="Edit dokumen">
                                            <i class="fa-solid fa-file-pen"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <form class="show-delete-confirm" method="POST"
                                            action="{{ route('pengumuman.delete', $pengumuman->id) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="badge btn btn-danger p-1 rounded-lg"
                                                style="cursor:pointer;" title="hapus usulan">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection

@section('footer')
    <section class="bg-dark p-1">
        <div class="container d-flex justify-content-center">
            <p class="developer">Dikembangkan oleh Prodi Teknik Informatika UNRI
                (<a class="text-success fw-bold" href="https://pangidoannsh.vercel.app" target="_blank">
                    Pangidoan Nugroho Syahputra Harahap
                </a>)
            </p>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 2000);
    </script>
@endpush()

@push('scripts')
    <script>
        const swal = $('.swal').data('swal');
        if (swal) {
            Swal.fire({
                title: 'Berhasil',
                text: swal,
                confirmButtonColor: '#28A745',
                icon: 'success'
            })
        }
    </script>
    <script>
        $('.show-delete-confirm').submit((e) => {
            const form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Usulan',
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
        $('.show-reject-dokumen').submit((e) => {
            const form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Tolak Dokumen',
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
@endpush()
