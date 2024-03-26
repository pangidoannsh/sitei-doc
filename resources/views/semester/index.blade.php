@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Data Semester
@endsection
@section('sub-title')
    Semester
@endsection
@section('content')
    <a class="btn btn-success w-content mb-3 d-flex align-items-center justify-content-center fw-bold gap-2 rounded-2"
        href="{{ route('semester.create') }}">
        <i class="fa-solid fa-plus"></i>
        Semester
    </a>
    <div class="contariner card p-4">
        <table class="table table-responsive-lg table-bordered table-striped" style="width:100%" id="datatables">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" scope="col">Semester</th>
                    <th class="text-center" scope="col">Tahun Ajaran</th>
                    <th class="text-center" scope="col">Tanggal Mulai</th>
                    <th class="text-center" scope="col">Tanggal Selesai</th>
                    <th class="text-center" scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($semesters as $semester)
                    <tr>
                        <td class="text-center">
                            {{ $semester->semester }}
                        </td>
                        <td class="text-center">
                            {{ $semester->tahun_ajaran }}
                        </td>
                        <td class="text-center">
                            {{ Carbon::parse($semester->tanggal_mulai)->translatedFormat('d F Y') }}
                        </td>
                        <td class="text-center">
                            {{ Carbon::parse($semester->tanggal_selesai)->translatedFormat('d F Y') }}
                        </td>
                        <td class="text-center" style="width: max-content">
                            <div class="d-flex gap-lg-3 gap-2 justify-content-center" style="width: 100%">
                                <div>
                                    <a class="badge btn btn-warning p-1 rounded-lg text-white" style="cursor:pointer;"
                                        href="{{ route('semester.edit', $semester->id) }}" title="Edit Semester">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </a>
                                </div>
                                {{-- Button Delete --}}
                                <form class="show-delete-confirm" method="POST"
                                    action="{{ route('semester.delete', $semester->id) }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="badge btn btn-danger p-1 rounded-lg"
                                        style="cursor:pointer;" title="Hapus Semester">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
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
        $('.show-delete-confirm').submit((e) => {
            const form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Semester',
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
