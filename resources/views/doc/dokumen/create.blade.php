@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Distribusi Surat & Dokumen
@endsection

@section('content')
    <div class="">
        <h2 class="text-center fw-semibold ">Dokumen Baru</h2>

        <form action="{{ route('dokumen.store') }}" method="POST" class="d-flex flex-column gap-3"
            enctype="multipart/form-data">
            @method('post')
            @csrf
            <div>
                <label for="nama" class="fw-semibold">Nama Dokumen<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror rounded-3 py-4" name="nama"
                    placeholder="Contoh: Jurnal..." id="nama" value="{{ old('nama') }}">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div>
                <label for="kategori" class="fw-semibold">Kategori<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="kategori" id="kategori"
                        class="text-secondary text-capitalize rounded-3 text-capitalize @error('kategori') border border-danger @enderror">
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori }}" class="text-capitalize"
                                {{ old('kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="semester" class="fw-semibold">Semester<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="semester" id="semester"
                        class="text-secondary text-capitalize rounded-3 text-capitalize @error('semester') border border-danger @enderror">
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester->nama }}" class="text-capitalize"
                                {{ old('semester') == $semester->nama || $semesters->last()->nama == $semester->nama ? 'selected' : '' }}>
                                {{ $semester->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('semester')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="keterangan" class="fw-semibold">Keterangan</label>
                <textarea class="form-control rounded-3 py-4" placeholder="Keterangan" name="keterangan" id="keterangan" cols="3">{{ old('keterangan') }}</textarea>
            </div>
            <div class="d-flex gap-4 align-items-center">
                <div class="w-100">
                    <label for="dokumen" class="fw-semibold">Dokumen></label>
                    <input type="file" class="form-control rounded-3 @error('dokumen') is-invalid @enderror"
                        name="dokumen" id="dokumen">
                    @error('dokumen')
                        <div class="invalid-feedback">{{ $message }} </div>
                    @enderror
                </div>
                <div class="or-divider">atau</div>
                <div class="w-100">
                    <label for="url_dokumen" class="fw-semibold">Tempel URL Dokumen</label>
                    <input type="url" class="form-control rounded-3" value="{{ old('url_dokumen') }}"
                        name="url_dokumen" placeholder="Contoh: https://drive.google.com/..." id="url_dokumen">
                </div>
            </div>
            <div>
                <label for="tanggal" class="fw-semibold">Tanggal Dokumen</label>
                <input type="date" class="form-control @error('tgl_dokumen') is-invalid @enderror rounded-3 py-4"
                    name="tgl_dokumen" id="tanggal" value="{{ old('tgl_dokumen') }}">
                @error('tgl_dokumen')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div class="d-flex flex-lg-row flex-column gap-3 mt-2">
                <div style="width: fit-content">
                    <div class="fw-semibold" style="translate: 0 4px">
                        Dibagikan Kepada:
                    </div>
                </div>
                <div class="w-100">
                    <div class="form-check mb-3" style="padding: 0 24px">
                        <input class="form-check-input" type="checkbox" value="all" id="all" name="select_all">
                        <label class="form-check-label" for="all">
                            Kirim Ke Semua
                        </label>
                    </div>
                    <div class="breadcrumb col-lg-12 mb-4 d-flex position-relative" style="padding:12px 0;">
                        <div id="borderActive" class="border-active"></div>
                        <div id="btn-dosen" style="min-width: 80px;cursor: pointer;"
                            class="text-center active fw-bold text-success">
                            Dosen
                        </div>
                        {{-- <span class=">|</span> --}}
                        <div id="btn-staf" style="min-width: 80px;cursor: pointer;" class="text-center">
                            Staff
                        </div>
                        {{-- <span class=">|</span> --}}
                        <div id="btn-mahasiswa" style="min-width: 80px;cursor: pointer;" class="text-center">
                            Mahasiswa
                        </div>
                    </div>
                    {{-- Dosen --}}
                    <div id="dosenCheckList" style="min-height: 70vh">
                        <div class="form-check mb-3" style="padding: 0 24px">
                            <input class="form-check-input" type="checkbox" value="all_dosen" id="all_dosen"
                                name="select_all_dosen">
                            <label class="form-check-label" for="all_dosen">
                                Semua Dosen Teknik Elektro
                            </label>
                        </div>
                        <hr>
                        <div class="row row-cols-2 px-3" style="row-gap: 8px">
                            <div class="col d-flex flex-column gap-2">
                                @php
                                    $totalDosen = count($dosens);
                                    $halfIndex = ceil($totalDosen / 2);
                                @endphp

                                @foreach ($dosens as $key => $dosen)
                                    @if ($key < $halfIndex)
                                        <div class="form-check">
                                            <input class="form-check-input dosen-selector" type="checkbox"
                                                value={{ $dosen->nip }} id="select-{{ $dosen->nip }}"
                                                name="dosen[]">
                                            <label class="form-check-label" for="select-{{ $dosen->nip }}">
                                                {{ $dosen->nama }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="col d-flex flex-column gap-2">
                                @foreach ($dosens as $key => $dosen)
                                    @if ($key >= $halfIndex)
                                        <div class="form-check">
                                            <input class="form-check-input dosen-selector" type="checkbox"
                                                value={{ $dosen->nip }} id="select-{{ $dosen->nip }}"
                                                name="dosen[]">
                                            <label class="form-check-label" for="select-{{ $dosen->nip }}">
                                                {{ $dosen->nama }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- Staff --}}
                    <div id="stafCheckList" class="d-none" style="min-height: 460px">
                        <div class="form-check mb-3" style="padding: 0 24px">
                            <input class="form-check-input" type="checkbox" value="all_staf" id="all_staf"
                                name="select_all_staf">
                            <label class="form-check-label" for="all_staf">
                                Semua Staf Administrasi Teknik Elektro
                            </label>
                        </div>
                        <hr>
                        <div class="row row-cols-2 px-3" style="row-gap: 8px">
                            @foreach ($staffs as $staff)
                                <div class="form-check">
                                    <input class="form-check-input staf-selector" type="checkbox"
                                        value={{ $staff->username }} id="select-{{ $staff->username }}" name="staf[]">
                                    <label class="form-check-label" for="select-{{ $staff->username }}">
                                        {{ $staff->nama }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-submit">
                <button type="submit" class="btn btn-success">Buat Dokumen</button>
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

@push('scripts')
    {{-- Tab Handle Dosen/Staf --}}
    <script>
        const borderActive = $("#borderActive")
        const btnShowDosen = $("#btn-dosen")
        const btnShowStaf = $("#btn-staf")
        const btnShowMahasiswa = $("#btn-mahasiswa")

        const dosenCheckList = $("#dosenCheckList")
        const stafCheckList = $("#stafCheckList")
        const mahasiswaCheckList = $("#mahasiswaCheckList")

        function setDefaultDosenDisplay() {
            btnShowDosen.removeClass("active fw-bold text-success")
            dosenCheckList.addClass("d-none")
        }

        function setActiveDosenDisplay() {
            btnShowDosen.addClass("active fw-bold text-success")
            dosenCheckList.removeClass("d-none")
            borderActive.css({
                "translate": "0 0"
            })
        }

        function setDefaultStafDisplay() {
            btnShowStaf.removeClass("active fw-bold text-success")
            stafCheckList.addClass("d-none")
        }

        function setActiveStafDisplay() {
            btnShowStaf.addClass("active fw-bold text-success")
            stafCheckList.removeClass("d-none")
            borderActive.css({
                "translate": "100% 0"
            })
        }

        function setDefaultMahasiswaDisplay() {
            btnShowMahasiswa.removeClass("active fw-bold text-success")
            mahasiswaCheckList.addClass("d-none")
        }

        function setActiveMahasiswaDisplay() {
            btnShowMahasiswa.addClass("active fw-bold text-success")
            mahasiswaCheckList.removeClass("d-none")
            borderActive.css({
                "translate": "200% 0"
            })
        }

        btnShowStaf.on("click", () => {
            setDefaultDosenDisplay()
            setDefaultMahasiswaDisplay()
            //active
            setActiveStafDisplay()
        })
        btnShowDosen.on("click", () => {
            setDefaultStafDisplay()
            setDefaultMahasiswaDisplay()
            //active
            setActiveDosenDisplay()
        })
        btnShowMahasiswa.on("click", () => {
            setDefaultDosenDisplay()
            setDefaultStafDisplay()
            //active
            setActiveMahasiswaDisplay()
        })
    </script>
@endpush
