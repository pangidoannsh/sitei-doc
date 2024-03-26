@extends('doc.main-layout')

@php
    use Carbon\Carbon;
    $kategoris = ['pendidikan', 'penelitian', 'pengabdian', 'penunjang', 'KP/Skripsi', 'lainnya'];

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
                    $exp = explode('_', $mention->user_mentioned);
                    $builder = '';
                    switch ($exp[0]) {
                        case 's1ti':
                            $builder = 'Seluruh Teknik Informatika S1';
                            // array_push($penerima, 'Seluruh Teknik Informatika S1');
                            break;
                        case 's1te':
                            $builder = 'Seluruh Teknik Elektro S1';
                            // array_push($penerima, 'Seluruh Teknik Elektro S1');
                            break;
                        case 'd3te':
                            $builder = 'Seluruh Teknik Elektro D3';
                            // array_push($penerima, 'Seluruh Teknik Elektro D3');
                            break;
                        default:
                            break;
                    }
                    if ($exp[1] != 'all') {
                        $builder = $builder . " angkatan $exp[1]";
                    }
                    array_push($penerima, $builder);
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
    SITEI | Pengumuman
@endsection
@section('sub-title')
    Pengumuman
@endsection
@section('content')
    <a href="{{ route('pengumuman.create') }}"
        class="btn btn-success w-content mb-3 d-flex align-items-center justify-content-center fw-bold gap-2 rounded-2">
        <i class="fa-solid fa-plus"></i>
        Pengumuman
    </a>
    <div class="contariner card p-4">
        <ul class="breadcrumb col-lg-12">
            <li>
                <a href="{{ route('pengumuman') }}" class="px-1">
                    Pengumuman (<span>{{ $latestCount }}</span>)
                </a>
            </li>
            <span class="px-2">|</span>
            <li>
                <a href="#" class="breadcrumb-item active fw-bold text-success px-1">
                    Arsip (<span>{{ count($pengumumans) }}</span>)
                </a>
            </li>
        </ul>
        <div class="gap-3 filter d-none" style="height: 0">
            <label>
                <span class="fw-bold">
                    Kategori
                </span>
                <select id="kategoriFilter" class="custom-select form-control form-control-sm pr-4">
                    <option value="" selected>Semua</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori }}" class="text-capitalize">{{ $kategori }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                <span class="fw-bold">
                    Semester
                </span>
                <select id="semesterFilter" class="custom-select form-control form-control-sm pr-4">
                    <option value="" selected>Semua</option>
                    @foreach ($semesters as $semester)
                        <option value="{{ $semester->nama }}" class="text-capitalize">{{ $semester->nama }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <table class="table table-responsive-lg table-bordered table-striped" style="width:100%" id="datatables">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" scope="col">Nomor</th>
                    <th class="text-center" scope="col">Nama</th>
                    <th class="text-center" scope="col">Pengusul</th>
                    <th class="text-center" scope="col">Penerima</th>
                    <th class="text-center" scope="col">Tanggal Usulan</th>
                    <th class="text-center" scope="col">Batas Pengumuman</th>
                    <th class="text-center" scope="col">Jenis/Kategori</th>
                    <th class="text-center" scope="col">Keterangan</th>
                    <th class="text-center" scope="col">Semester</th>
                    <th class="text-center" scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengumumans->sortByDesc('created_at') as $pengumuman)
                    <tr>
                        {{-- Nomor --}}
                        <td class="text-center" style="overflow: hidden">
                            <div class="ellipsis-1 " style="max-width: 80px">
                                {{ $pengumuman->nomor_pengumuman ?? '-' }}
                            </div>
                        </td>
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
                        <td class="text-center text-capitalize">
                            {{ $pengumuman->semester ?? '-' }}
                        </td>
                        {{-- Aksi --}}
                        <td class="text-center" style="width: 56px">
                            <div class="row row-cols-2" style="width: 100%">
                                <div>
                                    <a class="badge btn btn-info p-1 rounded-lg" style="cursor:pointer;"
                                        title="Lihat detail" href="{{ route('pengumuman.detail', $pengumuman->id) }}">
                                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div>
                                    <button class="btnCopy badge btn btn-secondary p-1 rounded-lg" style="cursor:pointer;"
                                        title="Bagikan"
                                        data-slug="{{ route('pengumuman.detail.public', Crypt::encrypt($pengumuman->id)) }}">
                                        <i class="fa-solid fa-share-nodes"></i>
                                    </button>
                                </div>
                                @if ($pengumuman->user_created === $userId)
                                    <div>
                                        <a class="badge btn btn-warning p-1 rounded-lg text-white" style="cursor:pointer;"
                                            href="{{ route('pengumuman.edit', $pengumuman->id) }}" title="Edit dokumen">
                                            <i class="fa-solid fa-pen-to-square"></i>
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
@endpush()
