@extends('doc.main-layout')

@php
    use Carbon\Carbon;

    function generateYears()
    {
        $currentYear = Carbon::now()->year;
        $yearsArray = [];

        for ($i = 0; $i < 7; $i++) {
            $yearsArray[] = $currentYear - $i;
        }

        return $yearsArray;
    }
    $angkatans = generateYears();
@endphp

@section('title')
    SITEI | Distribusi Surat & Dokumen
@endsection

{{-- @section('sub-title')
    Buat Usulan
@endsection --}}

@section('content')
    <section>
        <h2 class="text-center fw-semibold ">Penyelesaian Sertifikat</h2>

        <form action="{{ route('sertif.completion', $data->id) }}" method="POST" class="d-flex flex-column gap-3"
            style="position: relative;padding-bottom: 200px" enctype="multipart/form-data">
            @method('post')
            @csrf
            <div>
                <label for="isi" class="fw-semibold">Isi Sertifikat</label>
                <textarea class="form-control rounded-3 py-4" placeholder="Isi Sertifikat" name="isi" id="isi" cols="3"
                    required>{{ old('isi') ?? $data->isi }}</textarea>
            </div>
            <div class="row row-cols-2" style="row-gap: 8px">
                @foreach ($data->penerimas as $penerima)
                    <div style="overflow: hidden">
                        <label for="nomor_sertif_{{ $penerima->id }}" class="fw-semibold ellipsis-1">
                            Nomor Sertifikat
                            <span class="text-secondary" style="font-size: 14px">
                                ({{ data_get($penerima, $penerima->jenis_penerima . '.nama') ?? $penerima->nama_penerima }})
                            </span>
                        </label>
                        <input type="text" class="form-control rounded-3 py-4" id="nomor_sertif_{{ $penerima->id }}"
                            name="nomor_sertif[{{ $penerima->id }}]">
                    </div>
                @endforeach
            </div>
            <div class="footer-submit">
                <button type="submit" class="btn btn-success">Selesaikan Sertifikat</button>
                <a type="button" class="btn btn-outline-success" href={{ url()->previous() }}>Kembali</a>
            </div>
        </form>
    </section>
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
@endpush
