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
        <h2 class="text-center fw-semibold ">Pengumuman Baru</h2>

        <form action="{{ route('pengumuman.store') }}" method="POST" class="d-flex flex-column gap-3"
            style="position: relative;padding-bottom: 200px" enctype="multipart/form-data">
            @method('post')
            @csrf
            <div>
                <label for="nama" class="fw-semibold">Nama Pengumuman<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror rounded-3 py-4" name="nama"
                    placeholder="Contoh: Pengumuman..." id="nama" value="{{ old('nama') }}">
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
                <label for="isi" class="fw-semibold">Isi Pengumuman</label>
                <textarea class="form-control rounded-3 py-4" placeholder="Isi Pengumuman" name="isi" id="isi" cols="3">{{ old('isi') }}</textarea>
            </div>
            <div class="d-flex gap-4 align-items-center">
                <div class="w-100">
                    <label for="dokumen" class="fw-semibold">Lampiran<span
                            style="font-size: 11px">(max:10MB)</span></label>
                    <input type="file" class="form-control rounded-3 @error('dokumen') is-invalid @enderror"
                        name="dokumen" id="dokumen">
                    @error('dokumen')
                        <div class="invalid-feedback">{{ $message }} </div>
                    @enderror
                </div>
                <div class="or-divider">atau</div>
                <div class="w-100">
                    <label for="url_dokumen" class="fw-semibold">Tempel URL Lampiran</label>
                    <input type="url" class="form-control rounded-3" value="{{ old('url_dokumen') }}" name="url_dokumen"
                        placeholder="Contoh: https://drive.google.com/..." id="url_dokumen">
                </div>
            </div>
            <div>
                <label for="tgl_batas_pengumuman" class="fw-semibold">Tanggal Batas Pengumuman<span
                        class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tgl_dokumen') is-invalid @enderror rounded-3 py-4"
                    name="tgl_batas_pengumuman" id="tgl_batas_pengumuman" value="{{ old('tgl_batas_pengumuman') }}">
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
                            <div class="d-flex align-items-center mb-3 " style="position: relative;left: 20px">
                                {{-- <input class="form-check-input" type="checkbox" value="all_angkatan" id="all_angkatan"
                                    name="select_all_angkatan"> --}}
                                {{-- <div class="input-group ms-3" style="width: max-content">
                                    <select id="selectAngkatanD3TE" style="padding: 8px 12px;"
                                        class="text-secondary text-capitalize rounded-3 text-capitalize">
                                        <option value="all" selected>Semua Angkatan</option>
                                        @foreach ($angkatans as $angkatan)
                                            <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                {{-- <button id="btnCariD3TE" type="button" class="btn btn-success rounded-2 py-2 px-4">Cari</button> --}}
                            </div>
                            <hr>
                            <div id="d3TECheckList" class="row row-cols-4">

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
                                {{-- <div class="input-group" style="width: max-content">
                                    <select id="selectAngkatanS1TE" style="padding: 8px 12px;"
                                        class="text-secondary text-capitalize rounded-3 text-capitalize">
                                        <option value="all" selected>Semua Angkatan</option>
                                        @foreach ($angkatans as $angkatan)
                                            <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                {{-- <button id="btnCariS1TE" type="button" class="btn btn-success rounded-2 py-2 px-4">Cari</button> --}}
                            </div>
                            <hr>
                            <div id="s1TECheckList" class="row row-cols-4">

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
                                {{-- <div class="input-group" style="width: max-content">
                                    <select id="selectAngkatanS1TI" style="padding: 8px 12px;"
                                        class="text-secondary text-capitalize rounded-3 text-capitalize">
                                        <option value="all" selected>Semua Angkatan</option>
                                        @foreach ($angkatans as $angkatan)
                                            <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                {{-- <button id="btnCariS1TI" type="button" class="btn btn-success rounded-2 py-2 px-4">Cari</button> --}}
                            </div>
                            <hr>
                            <div id="s1TICheckList" class="row row-cols-4">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-submit">
                <button type="submit" class="btn btn-success">Buat Pengumuman</button>
                <a type="button" class="btn btn-outline-success" href={{ route('doc.index') }}>Kembali</a>
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
            if (!isFetching.s1te) {
                getMahasiswa(2, s1TECheckList)
                isFetching.s1te = true
            }

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
            if (!isFetching.s1ti) {
                getMahasiswa(3, s1TICheckList)
                isFetching.s1ti = true
            }

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

        selectAllD3TE.on("change", e => {
            const isChecked = e.target.checked
            $(".selector-1").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
            }
        })
        selectAllS1TE.on("change", e => {
            const isChecked = e.target.checked
            $(".selector-2").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
            }
        })
        selectAllS1TI.on("change", e => {
            const isChecked = e.target.checked
            $(".selector-3").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
            }
        })
        // Ketika user check semua mahasiswa
        selectAllMhs.on('change', e => {
            const isChecked = e.target.checked
            $(".mahasiswa-selector").prop('checked', isChecked)
            $(".prodi-selector").prop('checked', isChecked)
        })

        //ketika user klik daftar mahasiswa
        $(".mahasiswa-selector").on('change', e => {
            const isChecked = e.target.checked
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
            }
        })
    </script>
    {{-- <script>
        const prodiList = @json($prodis).map(data => ({
            id: data.id.toString(),
            nama: data.nama
        }))

        const btnCariMahasiswa = $("#btnCariMahasiswa")
        const selectProdi = $("#selectProdi")
        const selectAngkatan = $("#selectAngkatan")
        const groupCheckList = $("#groupCheckList")
        const mahasiswaContainer = $("#mahasiswaContainer")

        function CreateCheckBoxGroup(label, value, id) {
            var checkBox = `
                <div class="form-check mb-3 group-mahasiswa" style="padding: 0 24px">
                    <input class="form-check-input" type="checkbox" value="${value}" id="${id}" name="group[]">
                    <label class="form-check-label" for="${id}">
                        ${label}
                    </label>
                </div>
            `;
            groupCheckList.append(checkBox)
        }

        function createColumnMahasiswaList() {
            var col = `
                <div class="col d-flex flex-column gap-2">
                
                </div>
            `
            return col
        }

        function CreateCheckboxMahasiswa(label, value) {
            const mahasiswa = `
                            <div class="form-check">
                                <input class="form-check-input mahaasiswa-selector" type="checkbox"
                                    value="${value}" id="select-${value}"
                                    name="mahasiswa[]">
                                <label class="form-check-label" for="select-${value}">
                                    ${label}
                                </label>
                            </div>
            `;
            return mahasiswa;
        }

        btnCariMahasiswa.on("click", () => {
            const prodi = selectProdi.val()
            const angkatan = selectAngkatan.val()
            $.ajax({
                url: `/api/mahasiswa?prodi=${prodi}&&angkatan=${angkatan}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    const mahasiswa = res.data
                    CreateCheckBoxGroup(
                        `Semua ${prodiList.find(data=>data.id === prodi).nama} ${angkatan !== "all"? angkatan:""}`, {
                            prodi,
                            angkatan
                        }, `group-${prodi}_${angkatan}`)
                    const halfIndex = Math.ceil(mahasiswa.length / 2)

                    const colKiri = createColumnMahasiswaList()
                    mahasiswa.forEach((data, index) => {
                        if (index < halfIndex) {

                        }
                    });
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        })
    </script> --}}
    <script>
        const isFetching = {
            d3te: true,
            s1te: false,
            s1ti: false,
        }
        const d3TECheckList = $("#d3TECheckList")
        const s1TECheckList = $("#s1TECheckList")
        const s1TICheckList = $("#s1TICheckList")

        function createCheckBoxMahasiswa(label, value, prodi) {
            return `
                <div class="form-check">
                    <input class="form-check-input mahasiswa-selector selector-${prodi}" type="checkbox"
                        value="${value}" id="select-${value}"
                        name="mahasiswa[]">
                    <label class="form-check-label" for="select-${value}">
                        ${label}
                    </label>
                </div>
            `
        }

        function createCheckBoxAngkatan(label, prodi) {
            return `
                <div class="col">
                    <div class="form-check mb-3">
                        <input class="form-check-input group-selector" type="checkbox"
                            value="angkatan_${prodi}_${label}" id="select_all_${prodi}_${label}"
                            name="group[]">
                        <label class="form-check-label" for="select_all_${prodi}_${label}">
                            ${label}
                        </label>
                    </div>
                    <hr>
                    <div id="check_${prodi}_${label}"></div>
                </div>
            `
        }

        function getMahasiswa(prodi, element) {
            $.ajax({
                url: `/api/mahasiswa?prodi=${prodi}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    const groups = res.data
                    Object.keys(groups).forEach(key => {
                        element.append(createCheckBoxAngkatan(key, prodi))
                        groups[`${key}`].forEach(mhs => {
                            $(`#check_${prodi}_${key}`).append(createCheckBoxMahasiswa(mhs
                                .nama,
                                mhs.nim, prodi))
                            $(`#select-${mhs.nim}`).on('change', e => {
                                const isChecked = e.target.checked
                                if (!isChecked) {
                                    selectAllMhs.prop("checked", false)
                                    $(`#all_${prodi}`).prop("checked", false)
                                }
                            })
                        })
                        const allprodiIsChecked = $(`#all_${prodi}`).prop("checked")
                        $(`.selector-${prodi}`).prop("checked", allprodiIsChecked)
                    });

                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        function init() {
            getMahasiswa(1, d3TECheckList)
        }
    </script>
    <script>
        init()
    </script>
@endpush
