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
    SITEI | Pengelola Pengumuman
@endsection
@section('sub-title')
    Pengelola Pengumuman
    {{ str_replace(['TE', 'TI', 'Jurusan'], ['Teknik Elektro', 'Teknik Informatika', 'Jurusan Teknik Elektro'], str_replace('Ketua', '', optional(Auth::guard('dosen')->user())->role->role_akses)) }}
@endsection
@section('content')
    @if (Auth::guard('web')->check() || Auth::guard('dosen')->check())
        <a href="{{ route('pengumuman.create') }}"
            class="btn btn-success w-content mb-3 d-flex align-items-center justify-content-center fw-bold gap-2 rounded-2">
            <i class="fa-solid fa-plus"></i>
            Pengumuman
        </a>
    @endif
    <div class="contariner card p-4">
        <ul class="breadcrumb col-lg-12">
            <li>
                <a href="#" class="breadcrumb-item active fw-bold text-success px-1">
                    Terbaru (<span>{{ count($pengumumans) }}</span>)
                </a>
            </li>
            <span class="px-2">|</span>
            <li>
                <a href="{{ route('pengumuman.pengelola.arsip') }}" class="px-1">
                    Arsip (<span>{{ $countArsip }}</span>)
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
                    <th class="text-center" scope="col">Kategori</th>
                    <th class="text-center" scope="col">Keterangan</th>
                    <th class="text-center" scope="col">Semester</th>
                    <th class="text-center" scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengumumans->sortByDesc('created_at') as $pengumuman)
                    <tr>

                        {{-- Nomor Pengumuman --}}
                        <td class="text-center" style="overflow: hidden">
                            <div class="ellipsis">
                                {{ $pengumuman->nomor_pengumuman ?? '-' }}
                            </div>
                        </td>
                        {{-- Nama --}}
                        <td class="text-center" style="overflow: hidden">
                            <div class="ellipsis">
                                {{ $pengumuman->nama }}
                            </div>
                        </td>
                        {{-- Pengusul --}}
                        <td class="text-center">
                            @if (data_get($pengumuman, $pengumuman->jenis_user . '.role_id'))
                                {{ data_get($pengumuman, $pengumuman->jenis_user . '.role.role_akses') }}
                                @if ($pengumuman->jenis_user == 'dosen')
                                    ({{ $pengumuman->dosen->nama_singkat }})
                                @endif
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
                        <td class="text-center" style="width: max-content">
                            <div class="d-flex gap-lg-3 gap-2 justify-content-center" style="width: 100%">
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
