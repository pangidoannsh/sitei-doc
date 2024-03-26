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
            if ($index < $jumlahTampil) {
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
    Pengelola Distribusi Surat & Dokumen
    {{ str_replace(['TE', 'TI', 'Jurusan'], ['Teknik Elektro', 'Teknik Informatika', 'Jurusan Teknik Elektro'], str_replace('Ketua', '', optional(Auth::guard('dosen')->user())->role->role_akses)) }}
@endsection
@section('content')
    {{-- Arsip Dokumen --}}
    <div class="contariner card p-4">
        <ul class="breadcrumb col-lg-12">
            <li>
                <a href="{{ route('pengelola') }}" class="px-1">
                    Usulan Terbaru ({{ $countUsulan }})
                </a>
            </li>
            <span class="px-2">|</span>
            <li>
                <a href="{{ route('pengelola.pengumuman') }}" class="px-1">
                    Pengumuman (<span>{{ $countPengumuman }}</span>)
                </a>
            </li>
            <span class="px-2">|</span>
            <li>
                <a href="" class="breadcrumb-item active fw-bold text-success  px-1">
                    Arsip ({{ count($dokumens) + count($pengumumans) }})
                </a>
            </li>
        </ul>
        <div class="bg-light p-2 pt-3 mb-4">
            <h5>
                Arsip Dokumen
            </h5>
            <hr>
        </div>
        {{-- Filter --}}
        <div class="gap-3 filter d-none" style="height: 0">
            <label>
                <span class="fw-bold">
                    Status
                </span>
                <select id="statusFilter" class="custom-select form-control form-control-sm pr-4">
                    <option value="" selected>Semua</option>
                    <option value="dokumen">Dokumen</option>
                    <option value="surat">Surat</option>
                    <option value="surat cuti">Surat Cuti</option>
                    <option value="sertifikat">Sertifikat</option>
                </select>
            </label>
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
                    <th class="text-center" scope="col">Status</th>
                    <th class="text-center" scope="col">Tanggal Usulan</th>
                    <th class="text-center" scope="col">Jenis/Kategori</th>
                    <th class="text-center" scope="col">Keterangan</th>
                    <th class="text-center" scope="col">Semester</th>
                    <th class="text-center" scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dokumens->sortByDesc('created_at') as $dokumen)
                    <tr>
                        {{-- Nomor --}}
                        <td class="text-center" style="overflow: hidden;max-width: 80px">
                            <div class="ellipsis-1 text-capitalize">
                                {{ $dokumen->nomor_dokumen ?? ($dokumen->nomor_surat ?? '-') }}
                            </div>
                        </td>
                        {{-- Nama --}}
                        <td class="text-center" style="overflow: hidden">
                            <div class="ellipsis text-capitalize">
                                @if ($dokumen->jenisDokumen == 'surat_cuti')
                                    Pengajuan Cuti {{ $dokumen->jenis_cuti }}
                                @else
                                    {{ $dokumen->nama }}
                                @endif
                            </div>
                        </td>
                        {{-- Pengusul --}}
                        <td class="text-center" style="overflow: hidden">
                            <div class="ellipsis-2">
                                @if (data_get($dokumen, $dokumen->jenis_user . '.role_id'))
                                    {{ data_get($dokumen, $dokumen->jenis_user . '.role.role_akses') }}
                                @else
                                    {{ data_get($dokumen, $dokumen->jenis_user . '.nama') }}
                                @endif
                            </div>
                        </td>
                        {{-- Penerima --}}
                        <td class="text-center" style="overflow: hidden">
                            @switch($dokumen->jenisDokumen)
                                @case('pengumuman')
                                    @php
                                        $penerimas = displayPenerimaPengumuman($dokumen);
                                    @endphp
                                    <div class="ellipsis-2">
                                        @foreach ($penerimas as $penerima)
                                            <div>
                                                @if ($penerima == 'Seluruh Jurusan Teknik Elektro')
                                                    <span>{{ $penerima }}</span>
                                                @else
                                                    <span>{{ $loop->iteration }}.</span>
                                                    <span>{{ $penerima }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (count($penerimas) > 1)
                                        <a style="cursor:pointer;font-size: 12px" title="Lihat detail"
                                            href="{{ route('pengumuman.detail', $dokumen->id) }}">Lainnya...</a>
                                    @endif
                                @break

                                @case('dokumen')
                                    @if (count($dokumen->mentions) == 0)
                                        (Tidak mengirim ke siapapun)
                                    @endif
                                    <div class="d-flex flex-column gap-2">
                                        @foreach ($dokumen->mentions as $index => $mention)
                                            @if ($index < 2)
                                                @if ($mention->jenis_user == 'dosen')
                                                    <div>
                                                        <span>{{ $loop->iteration }}.</span>
                                                        <span>{{ $mention->dosen->nama_singkat }}</span>
                                                    </div>
                                                @else
                                                    <div>
                                                        <span>{{ $loop->iteration }}.</span>
                                                        <span>{{ $mention->admin->nama }}</span>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                        @if (count($dokumen->mentions) > 2)
                                            <a style="cursor:pointer;font-size: 12px" title="Lihat detail"
                                                href="{{ route('dokumen.detail', $dokumen->id) }}">Lainnya...</a>
                                        @endif
                                    </div>
                                @break

                                @case('sertifikat')
                                    @if (count($dokumen->penerimas) == 0)
                                        (Tidak mengirim ke siapapun)
                                    @endif
                                    <div class="d-flex flex-column gap-2" style="overflow: hidden">
                                        @foreach ($dokumen->penerimas as $index => $penerima)
                                            @if ($index < 2)
                                                @if ($penerima->user_penerima)
                                                    <div class="ellipsis-2">
                                                        <span>{{ $loop->iteration }}.</span>
                                                        <span>{{ data_get($penerima, $penerima->jenis_penerima . '.nama') }}</span>
                                                    </div>
                                                @else
                                                    <div class="ellipsis-2">
                                                        <span>{{ $loop->iteration }}.</span>
                                                        <span>{{ $penerima->nama_penerima }}</span>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                @break

                                @case('surat_cuti')
                                    Ketua Jurusan
                                @break

                                @case('surat')
                                    {{ $dokumen->penerima->role_akses }}
                                @break

                                @default
                            @endswitch
                        </td>
                        {{-- Status --}}
                        <td
                            class="text-center text-uppercase 
                        @switch($dokumen->jenisDokumen)
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
                            @foreach (explode('_', $dokumen->jenisDokumen) as $name)
                                {{ $name }}
                            @endforeach
                        </td>
                        {{-- Tanggal Usulan --}}
                        <td class="text-center">{{ Carbon::parse($dokumen->created_at)->translatedFormat('l, d F') }}
                        </td>
                        {{-- Jenis/Kategori --}}
                        <td class="text-center text-capitalize">
                            @switch($dokumen->jenisDokumen)
                                @case('pengumuman')
                                @case('dokumen')
                                    {{ $dokumen->kategori }}
                                @break

                                @case('surat_cuti')
                                    Cuti {{ $dokumen->jenis_cuti }}
                                @break

                                @default
                                    -
                            @endswitch
                        </td>
                        {{-- Isi/Keterangan --}}
                        <td class="text-center" style="overflow: hidden">
                            <div class="ellipsis">
                                @switch($dokumen->jenisDokumen)
                                    @case('pengumuman')
                                        {{ $dokumen->isi }}
                                    @break

                                    @case('dokumen')
                                        {{ $dokumen->keterangan }}
                                    @break

                                    @case('surat')
                                        {{ $dokumen->keterangan_status }}
                                    @break

                                    @case('surat_cuti')
                                        {{ getKeteranganCuti($dokumen->status) }}
                                    @break

                                    @case('sertifikat')
                                        Dalam Proses Pembuatan Oleh Staf Admin
                                    @break

                                    @default
                                @endswitch
                            </div>
                        </td>
                        {{-- Semester --}}
                        <td class="text-center text-capitalize">
                            {{ $dokumen->semester ?? '-' }}
                        </td>
                        {{-- Aksi --}}
                        <td class="text-center" style="width: max-content">
                            <div class="d-flex gap-lg-3 gap-2 justify-content-center" style="width: 100%">
                                {{-- Button Detail --}}
                                <div>
                                    <a class="badge btn btn-info p-1 rounded-lg" style="cursor:pointer;"
                                        title="Lihat detail"
                                        href="@switch($dokumen->jenis_dokumen)
                                            @case('pengumuman')
                                            {{ route('pengumuman.detail', $dokumen->id) }}
                                                @break
                                            @case('dokumen')
                                            {{ route('dokumen.detail', $dokumen->id) }}
                                                @break
                                            @case('surat_cuti')
                                            {{ route('suratcuti.detail', $dokumen->id) }}
                                                @break
                                            @case('surat')
                                            {{ route('surat.detail', $dokumen->id) }}
                                                @break
                                            @case('sertifikat')
                                            {{ route('sertif.detail', $dokumen->id) }}
                                                @break
                                            @default
                                                #
                                        @endswitch">
                                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                                    </a>
                                </div>
                                {{-- Button Share --}}
                                <div>
                                    <button class="btnCopy badge btn btn-secondary p-1 rounded-lg" style="cursor:pointer;"
                                        title="Bagikan"
                                        data-slug="@switch($dokumen->jenisDokumen)
                                            @case('surat_cuti')
                                            {{ route('suratcuti.detail.public', Crypt::encrypt($dokumen->id)) }}
                                            @break
                                            @case('sertifikat')
                                            {{ route('sertif.detail', $dokumen->id) }}
                                                @break
                                            @default
                                            {{ route($dokumen->jenisDokumen . '.detail.public', Crypt::encrypt($dokumen->id)) }}
                                        @endswitch">
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
    {{-- Arsip Pengumuman --}}
    <div class="contariner card p-4">
        <div class="bg-light p-2 pt-3 mb-4">
            <h5>
                Arsip Pengumuman
            </h5>
            <hr>
        </div>
        {{-- Filter --}}
        <div class="gap-3 filter d-none" style="height: 0">
            <label>
                <span class="fw-bold">
                    Kategori
                </span>
                <select id="kategoriFilter2" class="custom-select form-control form-control-sm pr-4">
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
                <select id="semesterFilter2" class="custom-select form-control form-control-sm pr-4">
                    <option value="" selected>Semua</option>
                    @foreach ($semesters as $semester)
                        <option value="{{ $semester->nama }}" class="text-capitalize">{{ $semester->nama }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <table class="table table-responsive-lg table-bordered table-striped" style="width:100%" id="datatables2">
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
                        <td class="text-center" style="overflow: hidden;max-width: 80px">
                            <div class="ellipsis-1 text-capitalize">
                                @if ($dokumen->jenisDokumen == 'dokumen' || $dokumen->jenisDokumen == 'surat')
                                    {{ $dokumen->nomor_dokumen ?? $dokumen->nomor_surat }}
                                @else
                                    -
                                @endif
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
                        <td class="text-center" style="width: max-content">
                            <div class="d-flex gap-lg-3 gap-2 justify-content-center" style="width: 100%">
                                <div>
                                    <a class="badge btn btn-info p-1 rounded-lg" style="cursor:pointer;"
                                        title="Lihat detail" href="{{ route('pengumuman.detail', $pengumuman->id) }}">
                                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                                    </a>
                                </div>
                                {{-- Button Share --}}
                                <div>
                                    <button class="btnCopy badge btn btn-secondary p-1 rounded-lg" style="cursor:pointer;"
                                        title="Bagikan"
                                        data-slug="{{ route('pengumuman.detail.public', Crypt::encrypt($dokumen->id)) }}">
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
