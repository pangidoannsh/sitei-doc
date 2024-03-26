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
        $jumlahTampil = 2;
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
                if ($mention->jenis_user == 'dosen') {
                    array_push($penerima, $mention->dosen->nama_singkat);
                } else {
                    array_push($penerima, data_get($mention, $mention->jenis_user . '.nama'));
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
                    Usulan (<span>{{ $countUsulan }}</span>)
                </a>
            </li>
            <span class="px-2">|</span>
            <li>
                <a href="#" class="breadcrumb-item active fw-bold text-success px-1">
                    Arsip (<span>{{ count($dokumens) }}</span>)
                </a>
            </li>
        </ul>
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
                        <td class="text-center" style="overflow: hidden"
                            @if ($dokumen->nomor_dokumen || $dokumen->nomor_surat) title="{{ $dokumen->nomor_dokumen ?? $dokumen->nomor_surat }}" @endif>
                            <div class="ellipsis-1 text-capitalize" style="max-width: 80px">
                                @if ($dokumen->jenisDokumen == 'dokumen' || $dokumen->jenisDokumen == 'surat')
                                    {{ $dokumen->nomor_dokumen ?? ($dokumen->nomor_surat ?? '-') }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        {{-- Nama --}}
                        <td class="text-center" style="overflow: hidden">
                            <div class="ellipsis">
                                @if ($dokumen->jenisDokumen == 'surat_cuti')
                                    Pengajuan Cuti {{ $dokumen->jenis_cuti }}
                                @else
                                    {{ $dokumen->nama }}
                                @endif
                            </div>
                        </td>
                        {{-- Pengusul --}}
                        <td class="text-center">
                            @if (data_get($dokumen, $dokumen->jenis_user . '.role_id'))
                                {{ data_get($dokumen, $dokumen->jenis_user . '.role.role_akses') }}
                                {{-- ({{ optional($dokumen->dosen)->nama_singkat }}) --}}
                            @else
                                @if ($dokumen->jenis_user == 'dosen')
                                    {{ $dokumen->dosen->nama_singkat }}
                                @else
                                    {{ data_get($dokumen, $dokumen->jenis_user . '.nama') }}
                                @endif
                            @endif
                        </td>
                        {{-- Penerima --}}
                        <td class="text-center">
                            @switch($dokumen->jenisDokumen)
                                @case('pengumuman')
                                    @php
                                        $penerimas = displayPenerimaPengumuman($dokumen);
                                    @endphp
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
                                @break

                                @case('dokumen')
                                    @if (count($dokumen->mentions) == 0)
                                        (Tidak mengirim ke siapapun)
                                    @endif
                                    <div class="d-flex flex-column gap-2">
                                        @foreach ($dokumen->mentions as $index => $mention)
                                            @if ($index < 2)
                                                <div>
                                                    <span>{{ $loop->iteration }}.</span>
                                                    <span>
                                                        @if ($mention->jenis_user == 'dosen')
                                                            {{ $mention->dosen->nama_singkat }}
                                                        @else
                                                            {{ $mention->admin->nama }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                        @if (count($dokumen->mentions) > 2)
                                            <a class="" style="cursor:pointer;font-size: 12px" title="Lihat detail"
                                                href="{{ route('dokumen.detail', $dokumen->id) }}">Lainnya...</a>
                                        @endif
                                    </div>
                                @break

                                @case('surat_cuti')
                                    Ketua Jurusan
                                @break

                                @case('surat')
                                    Staff Administrasi
                                @break

                                @case('sertifikat')
                                    @if (count($dokumen->penerimas) == 0)
                                        (Tidak mengirim ke siapapun)
                                    @endif
                                    <div class="d-flex flex-column gap-2" style="overflow: hidden">
                                        @if ($jenis_user == 'mahasiswa')
                                            <div class="ellipsis-2">
                                                {{ $dokumen->penerimas->firstWhere('user_penerima', $user_id)->mahasiswa->nama }}
                                            </div>
                                        @else
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
                                        @endif
                                    </div>
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
                        <td class="text-center">{{ Carbon::parse($dokumen->created_at)->translatedFormat('l, d F Y') }}
                        </td>
                        {{-- Jenis/Kategori --}}
                        <td class="text-center text-capitalize">
                            @switch($dokumen->jenisDokumen)
                                @case('pengumuman')
                                    {{ $dokumen->isi }}
                                @break

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
                                        Sertifikat Selesai
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
                        <td class="text-center" style="width: 56px">
                            <div class="row row-cols-2" style="width: 100%">
                                @switch($dokumen->jenisDokumen)
                                    @case('pengumuman')
                                        {{-- Button Detail --}}
                                        <div>
                                            <a class="badge btn btn-info p-1 rounded-lg" style="cursor:pointer;"
                                                title="Lihat detail" href="{{ route('pengumuman.detail', $dokumen->id) }}">
                                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        {{-- Button Share --}}
                                        <div>
                                            <button class="btnCopy badge btn btn-secondary p-1 rounded-lg" style="cursor:pointer;"
                                                title="Bagikan"
                                                data-slug="{{ route($dokumen->jenisDokumen . '.detail.public', Crypt::encrypt($dokumen->id)) }}">
                                                <i class="fa-solid fa-share-nodes"></i>
                                            </button>
                                        </div>
                                        {{-- Button Edit --}}
                                        @if ($dokumen->user_created === $user_id)
                                            <div>
                                                <a class="badge btn btn-warning p-1 rounded-lg text-white" style="cursor:pointer;"
                                                    href="{{ route('pengumuman.edit', $dokumen->id) }}" title="Edit pengumuman">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            </div>
                                            {{-- Button Delete --}}
                                            <form class="show-delete-confirm" method="POST"
                                                action="{{ route('pengumuman.delete', $dokumen->id) }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="badge btn btn-danger p-1 rounded-lg"
                                                    style="cursor:pointer;" title="Hapus pengumuman">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @break

                                    @case('dokumen')
                                        {{-- Button Detail --}}
                                        <div>
                                            <a class="badge btn btn-info p-1 rounded-lg" style="cursor:pointer;"
                                                title="Lihat detail" href="{{ route('dokumen.detail', $dokumen->id) }}">
                                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        {{-- Button Share --}}
                                        <div>
                                            <button class="btnCopy badge btn btn-secondary p-1 rounded-lg" style="cursor:pointer;"
                                                title="Bagikan"
                                                data-slug="{{ route($dokumen->jenisDokumen . '.detail.public', Crypt::encrypt($dokumen->id)) }}">
                                                <i class="fa-solid fa-share-nodes"></i>
                                            </button>
                                        </div>
                                        @if ($dokumen->user_created === $user_id)
                                            {{-- Button Edit --}}
                                            <div>
                                                <a class="badge btn btn-warning p-1 rounded-lg text-white" style="cursor:pointer;"
                                                    href="{{ route('dokumen.edit', $dokumen->id) }}" title="Edit dokumen">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            </div>
                                        @else
                                            @if (
                                                !data_get($dokumen, $dokumen->jenis_user . '.role_id') &&
                                                    in_array($user_id, $dokumen->mentions->pluck('user_mentioned')->toArray()))
                                                {{-- Button Tolak Kiriman Dokumen --}}
                                                <form method="POST" class="show-reject-dokumen"
                                                    action="{{ route('dokumen.mention.delete', ['dokumen_id' => $dokumen->id, 'user_mentioned' => $user_id]) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="badge btn btn-danger p-1 rounded-lg"
                                                        style="cursor:pointer;" title="Tolak Kiriman">
                                                        <i class="fas fa-times-circle" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        @if ($dokumen->user_created === $user_id || optional(Auth::guard('web')->user())->role_id == 1)
                                            {{-- Button Delete --}}
                                            <div>
                                                <form class="show-delete-confirm" method="POST"
                                                    action="{{ route('dokumen.delete', $dokumen->id) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="badge btn btn-danger p-1 rounded-lg"
                                                        style="cursor:pointer;" title="Hapus Dokumen">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @break

                                    @case('surat_cuti')
                                        {{-- Button Detail --}}
                                        <div>
                                            <a class="badge btn btn-info p-1 rounded-lg" style="cursor:pointer;"
                                                title="Lihat detail" href="{{ route('suratcuti.detail', $dokumen->id) }}">
                                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        {{-- Button Share --}}
                                        <div>
                                            <button class="btnCopy badge btn btn-secondary p-1 rounded-lg" style="cursor:pointer;"
                                                title="Bagikan"
                                                data-slug="{{ route('suratcuti.detail.public', Crypt::encrypt($dokumen->id)) }}">
                                                <i class="fa-solid fa-share-nodes"></i>
                                            </button>
                                        </div>
                                        @if ($dokumen->user_created === $user_id)
                                            @if ($dokumen->status == 'ditolak')
                                                {{-- Button Delete Surat Cuti --}}
                                                <form class="show-delete-confirm" method="POST"
                                                    action="{{ route('suratcuti.delete', $dokumen->id) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="badge btn btn-danger p-1 rounded-lg"
                                                        style="cursor:pointer;" title="hapus usulan">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        @if (optional(Auth::guard('dosen')->user())->role_id == 5)
                                            @if ($dokumen->status == 'proses')
                                                {{-- Button Approval Surat Cuti --}}
                                                <div>
                                                    <a title="Setujui Cuti" href="{{ route('suratcuti.approve', $dokumen->id) }}"
                                                        class="badge btn btn-success p-1 rounded-lg" style="cursor:pointer;">
                                                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                {{-- Button Reject Surat Cuti --}}
                                                <form action="{{ route('suratcuti.reject', $dokumen->id) }}" method="POST"
                                                    class="show-reject-suratcuti">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" title="Tolak Pengajuan"
                                                        class="badge btn btn-danger p-1 rounded-lg" style="cursor:pointer;">
                                                        <i class="fas fa-times-circle" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @break

                                    @case('surat')
                                        {{-- Button Detail --}}
                                        <div>
                                            <a class="badge btn btn-info p-1 rounded-lg" style="cursor:pointer;"
                                                title="Lihat Detail" href="{{ route('surat.detail', $dokumen->id) }}">
                                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        {{-- Button Share --}}
                                        <div>
                                            <button class="btnCopy badge btn btn-secondary p-1 rounded-lg" style="cursor:pointer;"
                                                title="Bagikan"
                                                data-slug="{{ route($dokumen->jenisDokumen . '.detail.public', Crypt::encrypt($dokumen->id)) }}">
                                                <i class="fa-solid fa-share-nodes"></i>
                                            </button>
                                        </div>
                                        @if ($dokumen->user_created == $user_id && $dokumen->status == 'ditolak')
                                            {{-- Button Delete --}}
                                            <form class="show-delete-surat" method="POST"
                                                action="{{ route('surat.delete', $dokumen->id) }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="badge btn btn-danger p-1 rounded-lg"
                                                    style="cursor:pointer;" title="Hapus Surat">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        {{-- @if ($jenis_user == 'admin' && $dokumen->role_handler == Auth::guard('web')->user()->role_id && $dokumen->status == 'staf_prodi')
                                            <div>
                                                <a title="Setujui Pengajuan"
                                                    href="{{ route('surat.acc.stafprodi', $dokumen->id) }}"
                                                    class="badge btn btn-success p-1 rounded-lg" style="cursor:pointer;">
                                                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        @endif --}}
                                    @break

                                    @case('sertifikat')
                                        {{-- Button Detail --}}
                                        <div>
                                            <a class="badge btn btn-info p-1 rounded-lg" style="cursor:pointer;"
                                                title="Lihat Detail"
                                                href="@if ($dokumen->penerima_id == $user_id) {{ route('sertif.penerima', $dokumen->slug) }}
                                                @else {{ route('sertif.detail', $dokumen->id) }} @endif">
                                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        {{-- Button Share --}}
                                        <div>
                                            <button class="btnCopy badge btn btn-secondary p-1 rounded-lg" style="cursor:pointer;"
                                                title="Bagikan"
                                                data-slug="@if ($dokumen->user_created == $user_id || $jenis_user == 'admin') {{ route('sertif.detail', $dokumen->id) }}
                                                @else
                                                {{ route('sertif.penerima', $dokumen->slug) }} @endif">
                                                <i class="fa-solid fa-share-nodes"></i>
                                            </button>
                                        </div>
                                        @if ($dokumen->user_created === $user_id && $dokumen->status == 'ditolak')
                                            {{-- Button Edit --}}
                                            <div>
                                                <a class="badge btn btn-warning p-1 rounded-lg text-white" style="cursor:pointer;"
                                                    href="{{ route('sertif.edit', ['id' => $dokumen->id]) }}"
                                                    title="Edit Sertifikat">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            </div>
                                            {{-- Button Delete --}}
                                            <form class="show-delete-sertif" method="POST"
                                                action="{{ route('sertif.delete', $dokumen->id) }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="badge btn btn-danger p-1 rounded-lg"
                                                    style="cursor:pointer;" title="Hapus Sertifikat">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @break

                                    @default
                                @endswitch
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
        // Delet Surat Cuti
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
        // Delet Pengajuan Surat
        $('.show-delete-surat').submit((e) => {
            const form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Pengajuan',
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
