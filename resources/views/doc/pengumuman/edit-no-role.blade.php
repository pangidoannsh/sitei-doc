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
    <div class="">
        <h2 class="text-center fw-semibold ">Ubah Pengumuman</h2>

        <form action="{{ route('pengumuman.update', $data->id) }}" method="POST" class="d-flex flex-column gap-3"
            style="position: relative;padding-bottom: 200px" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div>
                <label for="nama" class="fw-semibold">Nama Pengumuman<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror rounded-3 py-4" name="nama"
                    placeholder="Contoh: Pengumuman..." id="nama" value="{{ $data->nama }}">
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
                                {{ $data->kategori == $kategori ? 'selected' : '' }}>{{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="isi" class="fw-semibold">Isi Pengumuman</label>
                <textarea class="form-control rounded-3 py-4" placeholder="Isi Pengumuman" name="isi" id="isi" cols="3">{{ $data->isi }}</textarea>
            </div>
            <div id="current-dokumen">
                <label class="fw-semibold">Lampiran</label>
                <div class="d-flex gap-2 align-items-center">
                    @if ($data->url_dokumen != null)
                        <a href="@if ($data->is_local_file) {{ asset('storage/' . $data->url_dokumen) }}
                        @else
                        {{ $data->url_dokumen }} @endif"
                            target="_blank" class="btn btn-outline-primary">
                            Lampiran saat ini
                        </a>
                    @else
                        <div class="text-secondary">
                            (belum ada dokumen dilampirkan)
                        </div>
                    @endif
                    <button class="btn text-warning" type="button" id="btn-edit-dokumen" title="ubah dokumen">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
            </div>
            <div class="gap-4 align-items-center d-none bg-white p-4 pt-5 rounded-3 position-relative" id="input-dokumen">
                <button type="button" id="close-button-input" class="btn text-secondary position-absolute"
                    title="batal ubah link dokumen" style="right: 4px;top:0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="w-100">
                    <label for="dokumen" class="fw-semibold">Lampiran</label>
                    <input type="file" class="form-control rounded-3 @error('dokumen') is-invalid @enderror"
                        name="dokumen" id="dokumen">
                    @error('dokumen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="or-divider">atau</div>
                <div class="w-100">
                    <label for="url_dokumen" class="fw-semibold">Tempel URL Lampiran</label>
                    <input type="url" class="form-control rounded-3"
                        value="@if (!$data->is_local_file) {{ $data->url_dokumen }} @endif" name="url_dokumen"
                        placeholder="https://drive.google.com/..." id="url_dokumen">
                </div>
            </div>
            <div>
                <label for="tgl_batas_pengumuman" class="fw-semibold">Tanggal Batas Pengumuman<span
                        class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tgl_dokumen') is-invalid @enderror rounded-3 py-4"
                    name="tgl_batas_pengumuman" id="tgl_batas_pengumuman" value="{{ $data->tgl_batas_pengumuman }}">
                @error('tgl_batas_pengumuman')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div class="d-flex flex-lg-row flex-column gap-3 mt-2">
                <div style="width: fit-content">
                    <div class="fw-semibold" style="translate: 0 4px">
                        Diumumkan Kepada:
                    </div>
                </div>
                <div class="w-100">
                    {{-- Mahasiswa --}}
                    <div>
                        <div class="form-check mb-3" style="padding: 0 24px">
                            <input class="form-check-input" type="checkbox" value="all_mahasiswa" id="all_mahasiswa"
                                name="select_all_mahasiswa">
                            <label class="form-check-label" for="all_mahasiswa">
                                Semua Mahasiswa
                            </label>
                        </div>
                        <div class="breadcrumb col-lg-12 mb-4 d-flex position-relative" style="padding:12px 0;">
                            <div id="borderActive" class="border-active" style="width: 160px"></div>
                            <div id="btnD3TE" style="min-width: 160px;cursor: pointer;"
                                class="text-center active fw-bold text-success">
                                D3 Teknik Elektro
                            </div>
                            <div id="btnS1TE" style="min-width: 160px;cursor: pointer;" class="text-center">
                                S1 Teknik Elektro
                            </div>
                            <div id="btnS1TI" style="min-width: 160px;cursor: pointer;" class="text-center">
                                S1 Teknik Informatika
                            </div>
                        </div>
                        {{-- D3 TE --}}
                        <div id="d3TE" style="min-height: 460px">
                            <div class="form-check mb-3" style="padding: 0 24px">
                                <input class="form-check-input prodi-selector" type="checkbox" value="all_d3te"
                                    id="all_1" name="select_all_d3te">
                                <label class="form-check-label" for="all_1">
                                    Semua Mahasiswa D3 Teknik Elektro
                                </label>
                            </div>
                            <hr>
                            <div id="d3TECheckList" class="row row-cols-4">
                                @if (isset($mahasiswas['1']))
                                    @foreach ($mahasiswas['1']->sortKeys() as $angkatan => $angkatans)
                                        <div class="col">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input angkatan-selector" type="checkbox"
                                                    value="{{ $angkatan }}" id="1_{{ $angkatan }}"
                                                    name="d3te_angkatan[]">
                                                <label class="form-check-label" for="1_{{ $angkatan }}">
                                                    {{ $angkatan }}
                                                </label>
                                            </div>
                                            <hr>
                                            <div id="check_1_{{ $angkatan }}">
                                                @foreach ($angkatans->sortKeys() as $mahasiswa)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input mahasiswa-selector d3te-selector "
                                                            type="checkbox" value="{{ $mahasiswa->nim }}"
                                                            id="1-{{ $angkatan }}-{{ $mahasiswa->nim }}"
                                                            name="d3te[]"
                                                            {{ in_array($mahasiswa->nim, $data->mentions->pluck('user_mentioned')->toArray()) ? 'checked' : '' }}>
                                                        {{-- name="d3te[{{ $angkatan }}][]"> --}}
                                                        <label class="form-check-label"
                                                            for="1-{{ $angkatan }}-{{ $mahasiswa->nim }}">
                                                            {{ $mahasiswa->nama }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        {{-- S1 TE --}}
                        <div id="s1TE" class="d-none" style="min-height: 460px">
                            <div class="form-check mb-3" style="padding: 0 24px">
                                <input class="form-check-input prodi-selector" type="checkbox" value="all_s1te"
                                    id="all_2" name="select_all_s1te">
                                <label class="form-check-label" for="all_2">
                                    Semua Mahasiswa S1 Teknik Elektro
                                </label>
                            </div>
                            <div class="d-flex gap-2 align-items-center mb-3">
                            </div>
                            <hr>
                            <div id="s1TECheckList" class="row row-cols-4">
                                @if (isset($mahasiswas['2']))
                                    @foreach ($mahasiswas['2']->sortKeys() as $angkatan => $angkatans)
                                        <div class="col">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input angkatan-selector" type="checkbox"
                                                    value="{{ $angkatan }}" id="2_{{ $angkatan }}"
                                                    name="s1te_angkatan[]">
                                                <label class="form-check-label" for="2_{{ $angkatan }}">
                                                    {{ $angkatan }}
                                                </label>
                                            </div>
                                            <hr>
                                            <div id="check_2_{{ $angkatan }}">
                                                @foreach ($angkatans->sortBy('nama') as $mahasiswa)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input mahasiswa-selector s1te-selector"
                                                            type="checkbox" value="{{ $mahasiswa->nim }}"
                                                            id="2-{{ $angkatan }}-{{ $mahasiswa->nim }}"
                                                            name="s1te[]"
                                                            {{ in_array($mahasiswa->nim, $data->mentions->pluck('user_mentioned')->toArray()) ? 'checked' : '' }}>
                                                        {{-- name="s1te[{{ $angkatan }}][]"> --}}
                                                        <label class="form-check-label"
                                                            for="2-{{ $angkatan }}-{{ $mahasiswa->nim }}">
                                                            {{ $mahasiswa->nama }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        {{-- S1 TI --}}
                        <div id="s1TI" class="d-none" style="min-height: 460px">
                            <div class="form-check mb-3" style="padding: 0 24px">
                                <input class="form-check-input prodi-selector" type="checkbox" value="all_s1ti"
                                    id="all_3" name="select_all_s1ti">
                                <label class="form-check-label" for="all_3">
                                    Semua Mahasiswa S1 Teknik Informatika
                                </label>
                            </div>
                            <div class="d-flex gap-2 align-items-center mb-3">
                            </div>
                            <hr>
                            <div id="s1TICheckList" class="row row-cols-4">
                                @if (isset($mahasiswas['3']))
                                    @foreach ($mahasiswas['3']->sortKeys() as $angkatan => $angkatans)
                                        <div class="col">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input angkatan-selector" type="checkbox"
                                                    value="{{ $angkatan }}" id="3_{{ $angkatan }}"
                                                    name="s1ti_angkatan[]">
                                                <label class="form-check-label" for="3_{{ $angkatan }}">
                                                    {{ $angkatan }}
                                                </label>
                                            </div>
                                            <hr>
                                            <div id="check_3_{{ $angkatan }}">
                                                @foreach ($angkatans->sortKeys() as $mahasiswa)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input mahasiswa-selector s1ti-selector "
                                                            type="checkbox" value="{{ $mahasiswa->nim }}"
                                                            id="3-{{ $angkatan }}-{{ $mahasiswa->nim }}"
                                                            name="s1ti[]"
                                                            {{ in_array($mahasiswa->nim, $data->mentions->pluck('user_mentioned')->toArray()) ? 'checked' : '' }}>
                                                        {{-- name="s1ti[{{ $angkatan }}][]"> --}}
                                                        <label class="form-check-label"
                                                            for="3-{{ $angkatan }}-{{ $mahasiswa->nim }}">
                                                            {{ $mahasiswa->nama }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-submit">
                <button type="submit" class="btn btn-success">Ubah Pengumuman</button>
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
    <script>
        const borderActive = $("#borderActive")
        const btnD3TEShow = $("#btnD3TE")
        const btnS1TEShow = $("#btnS1TE")
        const btnS1TIShow = $("#btnS1TI")

        const d3TE = $("#d3TE")
        const s1TE = $("#s1TE")
        const s1TI = $("#s1TI")

        function setDefaultD3TEDisplay() {
            btnD3TEShow.removeClass("active fw-bold text-success")
            d3TE.addClass("d-none")
        }

        function setActiveD3TEDisplay() {
            btnD3TEShow.addClass("active fw-bold text-success")
            d3TE.removeClass("d-none")
            borderActive.css({
                "translate": "0 0"
            })
        }

        function setDefaultS1TEDisplay() {
            btnS1TEShow.removeClass("active fw-bold text-success")
            s1TE.addClass("d-none")
        }

        function setActiveS1TEDisplay() {
            btnS1TEShow.addClass("active fw-bold text-success")
            s1TE.removeClass("d-none")
            borderActive.css({
                "translate": "100% 0"
            })
        }

        function setDefaultS1TIDisplay() {
            btnS1TIShow.removeClass("active fw-bold text-success")
            s1TI.addClass("d-none")
        }

        function setActiveS1TIDisplay() {
            btnS1TIShow.addClass("active fw-bold text-success")
            s1TI.removeClass("d-none")
            borderActive.css({
                "translate": "200% 0"
            })
        }

        btnS1TEShow.on("click", () => {
            setDefaultD3TEDisplay()
            setDefaultS1TIDisplay()
            //active
            setActiveS1TEDisplay()
        })
        btnD3TEShow.on("click", () => {
            setDefaultS1TEDisplay()
            setDefaultS1TIDisplay()
            //active
            setActiveD3TEDisplay()
        })
        btnS1TIShow.on("click", () => {
            setDefaultD3TEDisplay()
            setDefaultS1TEDisplay()
            //active
            setActiveS1TIDisplay()
        })
    </script>

    <script>
        const selectAllMhs = $("#all_mahasiswa")
        const selectAllD3TE = $(`#all_1`)
        const selectAllS1TE = $(`#all_2`)
        const selectAllS1TI = $(`#all_3`)

        // untuk handle onChange prodi
        selectAllD3TE.on("change", e => {
            const isChecked = e.target.checked
            $("#d3TECheckList .angkatan-selector").prop("checked", isChecked)
            $("#d3TECheckList .mahasiswa-selector").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
            }
        })
        // Untuk handle onChange tahun angkatan
        $("#d3TECheckList .angkatan-selector").on("change", e => {
            const isChecked = e.target.checked
            const id = e.target.id
            $(`#check_${id} .mahasiswa-selector`).prop("checked", isChecked)
            if (!isChecked) {
                selectAllD3TE.prop("checked", false)
                selectAllMhs.prop("checked", false)
            }
        })

        selectAllS1TE.on("change", e => {
            const isChecked = e.target.checked
            $("#s1TECheckList .angkatan-selector").prop("checked", isChecked)
            $("#s1TECheckList .mahasiswa-selector").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
            }
        })
        // Untuk handle onChange tahun angkatan
        $("#s1TECheckList .angkatan-selector").on("change", e => {
            const isChecked = e.target.checked
            const id = e.target.id
            $(`#check_${id} .mahasiswa-selector`).prop("checked", isChecked)
            if (!isChecked) {
                selectAllS1TE.prop("checked", false)
                selectAllMhs.prop("checked", false)
            }
        })

        selectAllS1TI.on("change", e => {
            const isChecked = e.target.checked
            $("#s1TICheckList .angkatan-selector").prop("checked", isChecked)
            $("#s1TICheckList .mahasiswa-selector").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
            }
        })
        // Untuk handle onChange tahun angkatan
        $("#s1TICheckList .angkatan-selector").on("change", e => {
            const isChecked = e.target.checked
            const id = e.target.id
            $(`#check_${id} .mahasiswa-selector`).prop("checked", isChecked)
            if (!isChecked) {
                selectAllS1TI.prop("checked", false)
            }
        })
        // Ketika user check semua mahasiswa
        selectAllMhs.on('change', e => {
            const isChecked = e.target.checked
            $(".mahasiswa-selector").prop('checked', isChecked)
            $(".prodi-selector").prop('checked', isChecked)
            $(".angkatan-selector").prop('checked', isChecked)
        })

        //ketika user klik daftar mahasiswa
        $(".mahasiswa-selector").on('change', e => {
            const isChecked = e.target.checked
            const idElement = e.target.id
            if (!isChecked) {
                const [prodiID, angkatan, nim] = idElement.split("-")
                $(`#all_${prodiID}`).prop("checked", false)
                $(`#${prodiID}_${angkatan}`).prop("checked", false)
                selectAllMhs.prop("checked", false)
            }
        })
    </script>
    <script>
        @if ($data->for_all_mahasiswa)
            selectAllMhs.prop('checked', true)
            $(".mahasiswa-selector").prop('checked', true)
            $("#s1TICheckList .angkatan-selector").prop('checked', true)
            $("#s1TECheckList .angkatan-selector").prop('checked', true)
            $("#d3TECheckList .angkatan-selector").prop('checked', true)
            selectAllD3TE.prop('checked', true)
            selectAllS1TE.prop('checked', true)
            selectAllS1TI.prop('checked', true)
        @endif
        @if (in_array('s1te_all', $data->mentions->pluck('user_mentioned')->toArray()))
            selectAllD3TE.prop('checked', true)
            $("#d3TECheckList .angkatan-selector").prop('checked', true)
            $("#d3TECheckList .mahasiswa-selector").prop('checked', true)
        @endif
        @if (in_array('s1te_all', $data->mentions->pluck('user_mentioned')->toArray()))
            selectAllS1TE.prop('checked', true)
            $("#s1TECheckList .angkatan-selector").prop('checked', true)
            $("#s1TECheckList .mahasiswa-selector").prop('checked', true)
        @endif
        @if (in_array('s1ti_all', $data->mentions->pluck('user_mentioned')->toArray()))
            selectAllS1TI.prop('checked', true)
            $("#s1TICheckList .angkatan-selector").prop('checked', true)
            $("#s1TICheckList .mahasiswa-selector").prop('checked', true)
        @endif
    </script>
@endpush
