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
        <h2 class="text-center fw-semibold ">Pembuatan Sertifikat</h2>

        <form action="{{ route('sertif.store') }}" method="POST" class="d-flex flex-column gap-3"
            style="position: relative;padding-bottom: 200px" enctype="multipart/form-data">
            @method('post')
            @csrf
            <div>
                <label for="nama" class="fw-semibold">Nama Sertifikat<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror rounded-3 py-4" name="nama"
                    placeholder="Contoh: Sertifikat..." id="nama" value="{{ old('nama') }}">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div>
                <label for="jenis" class="fw-semibold">Jenis Sertifikat<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="jenis" id="jenis"
                        class="text-secondary text-capitalize rounded-3 text-capitalize @error('jenis') border border-danger @enderror">
                        <option value="" disabled selected>Pilih Jenis Sertifikat</option>
                        <option value="pendidikan">Pendidikan</option>
                    </select>
                    @error('jenis')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="tanggal" class="fw-semibold">Tanggal Sertifikat<span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tgl_dokumen') is-invalid @enderror rounded-3 py-4"
                    name="tanggal" id="tanggal" value="{{ old('tanggal') }}">
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div class="d-flex flex-lg-row flex-column gap-3 mt-2">
                <div style="width: fit-content">
                    <div class="fw-semibold" style="translate: 0 4px">
                        Diberikan Kepada:<span class="text-danger">*</span>
                    </div>
                </div>
                <div class="w-100">
                    @error('penerima')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror

                    @error('mahasiswa')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                    <div class="fw-semibold mb-3">Isi Nama Penerima</div>
                    <div>
                        <div class="d-flex justify-content-center" id="btnAddPenerimaContainer">
                            <button id="btnAddPenerima" type="button"
                                class="text-secondary mt-2 btn-text text-center success d-flex align-items-center gap-1">
                                <div><i class="fa-solid fa-plus"></i></div>
                                <div>Penerima</div>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <div class="divider"></div>
                        <div style="font-size: 14px" class="text-secondary text-center my-3">atau</div>
                        <div class="divider"></div>
                    </div>
                    {{-- Mahasiswa --}}
                    <div>
                        <div class="fw-semibold mb-3">Pilih Penerima</div>
                        <div class="form-check mb-3" style="padding: 0 24px">
                            <input class="form-check-input" type="checkbox" value="all_mahasiswa" id="all_mahasiswa"
                                name="select_all_mahasiswa">
                            <label class="form-check-label" for="all_mahasiswa">
                                Semua Mahasiswa
                            </label>
                        </div>
                        {{-- Tab --}}
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
                            @if (isset($mahasiswas['1']))
                                <div id="d3TECheckList" class="row row-cols-4">
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
                                                            name="mahasiswa[]">
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
                                </div>
                            @else
                                <span class="text-secondary fw-medium">(Data Mahasiswa Tidak Ditemukan)</span>
                            @endif
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
                            @if (isset($mahasiswas['2']))
                                <div id="s1TECheckList" class="row row-cols-4">
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
                                                            name="mahasiswa[]">
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
                                </div>
                            @else
                                <span class="text-secondary fw-medium">(Data Mahasiswa Tidak Ditemukan)</span>
                            @endif
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
                            @if (isset($mahasiswas['3']))
                                <div id="s1TICheckList" class="row row-cols-4">
                                    @foreach ($mahasiswas['3']->sortKeys() as $angkatan => $angkatans)
                                        <div class="col">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input angkatan-selector" type="checkbox"
                                                    value="{{ $angkatan }}" id="3_{{ $angkatan }}"
                                                    name="s1ti_angkatan[]">
                                                <div class="form-check-label" for="3_{{ $angkatan }}">
                                                    {{ $angkatan }}
                                                </div>
                                            </div>
                                            <hr>
                                            <div id="check_3_{{ $angkatan }}">
                                                @foreach ($angkatans->sortKeys() as $mahasiswa)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input mahasiswa-selector s1ti-selector "
                                                            type="checkbox" value="{{ $mahasiswa->nim }}"
                                                            id="3-{{ $angkatan }}-{{ $mahasiswa->nim }}"
                                                            name="mahasiswa[]">
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
                                </div>
                            @else
                                <span class="text-secondary fw-medium">(Data Mahasiswa Tidak Ditemukan)</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-submit">
                <button type="submit" class="btn btn-success">Buat Sertifikat</button>
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
        const btnAddPenerima = $("#btnAddPenerima");
        const btnAddPenerimaContainer = $("#btnAddPenerimaContainer");
        let inputCount = 0;

        function handleDeleteInput(element) {
            element.remove()
        }

        function createNewInputPenerima(value) {
            var input = $(`
                        <div class="d-flex align-items-center gap-1 mt-2">
                            <input type="text" class="form-control rounded-3 input-penerima" name="penerima[]"
                                placeholder="Nama Penerima" value="${value??""}">
                        </div>
            `)
            var btnDelete = $(`
                            <button type="button" class="text-secondary btn-text">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
            `)
            btnDelete.on("click", () => handleDeleteInput(input))
            input.append(btnDelete)
            input.insertBefore(btnAddPenerimaContainer)
        }

        btnAddPenerima.on("click", () => {
            createNewInputPenerima()
        })
        @if (old('penerima'))
            @foreach (old('penerima') as $penerima)
                createNewInputPenerima("{{ $penerima }}")
            @endforeach
        @else
            createNewInputPenerima()
        @endif
    </script>
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
@endpush
