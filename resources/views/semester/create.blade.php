@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Data Semester
@endsection

@section('content')
    <div class="">
        <h2 class="text-center fw-semibold ">Tambah Semester Baru</h2>

        <form action="{{ route('semester.store') }}" method="POST" class="d-flex flex-column gap-2 mt-3">
            @csrf
            @method('post')
            <div>
                <label for="semester">Semester</label>
                <div class="input-group">
                    <select name="semester" id="semester"
                        class="text-secondary form-select text-capitalize rounded-3 text-capitalize">
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="tahunAjaran">Tahun Ajaran</label>
                <input name="tahun_ajaran" id="tahunAjaran" type="text" class="form-control rounded-3 py-4"
                    placeholder="Contoh: 2017/2018" required>
            </div>
            <div class="row">
                <div class="col-lg-6 col-12">
                    <label for="tglMulai">Tanggal Mulai</label>
                    <input name="tanggal_mulai" id="tglMulai" type="date" class="form-control rounded-3 py-4" required>
                </div>
                <div class="col-lg-6 col-12">
                    <label for="tglSelesai">Tanggal Selesai</label>
                    <input name="tanggal_selesai" id="tglSelesai" type="date" class="form-control rounded-3 py-4"
                        required>
                </div>
            </div>
            <div class="footer-submit">
                <button type="submit" class="btn btn-success ">Tambah</button>
                <a type="button" class="btn btn-outline-success" href={{ url()->previous() }}>Kembali</a>
            </div>
        </form>

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
