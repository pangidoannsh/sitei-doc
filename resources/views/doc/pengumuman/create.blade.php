@extends('doc.main-layout')

@php
    use Carbon\Carbon;
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
                <label for="isi" class="fw-semibold">Isi Pengumuman<span class="text-danger">*</span></label>
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
                        <div id="btn-staf" style="min-width: 80px;cursor: pointer;" class="text-center">
                            Staff
                        </div>
                        <div id="btn-mahasiswa" style="min-width: 80px;cursor: pointer;" class="text-center">
                            Mahasiswa
                        </div>
                    </div>
                    {{-- Dosen --}}
                    <div id="dosenCheckList">
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
                    {{-- Mahasiswa --}}
                    <div id="mahasiswaCheckList" class="d-none" style="min-height: 460px">
                        <div class="form-check mb-3" style="padding: 0 24px">
                            <input class="form-check-input" type="checkbox" value="all_mahasiswa" id="all_mahasiswa"
                                name="select_all_mahasiswa">
                            <label class="form-check-label" for="all_mahasiswa">
                                Semua Mahasiswa
                            </label>
                        </div>
                        <hr>
                        {{-- Tab --}}
                        <div class="breadcrumb col-lg-12 mb-4 d-flex position-relative" style="padding:12px 0;">
                            <div id="borderActiveMahasiswa" class="border-active" style="width: 160px"></div>
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
                            {{-- Tab --}}
                            @if (isset($mahasiswas['1']))
                                <div class="breadcrumb col-lg-12 mb-4 d-flex position-relative" style="padding:12px 0;">
                                    <div id="borderActiveMahasiswaD3TE" class="border-active" style="width: 100px"></div>
                                    @php
                                        $isActive = true;
                                    @endphp
                                    @foreach ($mahasiswas['1']->sortKeys() as $angkatan => $angkatans)
                                        <div id="{{ "d3te_$angkatan" }}" style="min-width: 100px;cursor: pointer;"
                                            class="text-center {{ $isActive ? 'active fw-bold text-success' : '' }}">
                                            <input class="form-check-input angkatan-selector" type="checkbox"
                                                value="d3te_{{ $angkatan }}" id="s1ti_{{ $angkatan }}"
                                                name="d3te_{{ $angkatan }}">
                                            {{ $angkatan }}
                                        </div>
                                        @php
                                            $isActive = false;
                                        @endphp
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
                            <hr>
                            @if (isset($mahasiswas['2']))
                                <div class="breadcrumb col-lg-12 mb-4 d-flex position-relative" style="padding:12px 0;">
                                    <div id="borderActiveMahasiswaS1TE" class="border-active" style="width: 100px"></div>
                                    @php
                                        $isActive = true;
                                    @endphp
                                    @foreach ($mahasiswas['2']->sortKeys() as $angkatan => $angkatans)
                                        <div id="{{ "s1te_$angkatan" }}" style="min-width: 100px;cursor: pointer;"
                                            class="text-center {{ $isActive ? 'active fw-bold text-success' : '' }}">
                                            <input class="form-check-input angkatan-selector" type="checkbox"
                                                value="s1te_{{ $angkatan }}" id="s1ti_{{ $angkatan }}"
                                                name="s1te_{{ $angkatan }}">
                                            {{ $angkatan }}
                                        </div>
                                        @php
                                            $isActive = false;
                                        @endphp
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
                            <hr>
                            @if (isset($mahasiswas['3']))
                                <div class="breadcrumb col-lg-12 mb-4 d-flex position-relative" style="padding:12px 0;">
                                    <div id="borderActiveMahasiswaS1TE" class="border-active" style="width: 100px"></div>
                                    @php
                                        $isActive = true;
                                    @endphp
                                    @foreach ($mahasiswas['3']->sortKeys() as $angkatan => $angkatans)
                                        <div id="{{ "s1ti_$angkatan" }}" style="min-width: 100px;cursor: pointer;"
                                            class="text-center {{ $isActive ? 'active fw-bold text-success' : '' }}">
                                            <input class="form-check-input angkatan-selector" type="checkbox"
                                                value="s1ti_{{ $angkatan }}" id="s1ti_{{ $angkatan }}"
                                                name="s1ti_{{ $angkatan }}">
                                            {{ $angkatan }}
                                        </div>
                                        @php
                                            $isActive = false;
                                        @endphp
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
                <button type="submit" class="btn btn-success">Buat Pengumuman</button>
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
    {{-- CheckBox Handle Dosen/Staf --}}
    <script>
        const selectAll = $("#all")
        const selectAllDosen = $("#all_dosen")
        const selectAllStaf = $("#all_staf")
        const selectAllMhs = $("#all_mahasiswa")

        //ketika user klik semua
        selectAll.on('change', e => {
            const isChecked = e.target.checked
            selectAllDosen.prop('checked', isChecked)
            selectAllStaf.prop('checked', isChecked)
            selectAllMhs.prop('checked', isChecked)
            $(".dosen-selector").prop('checked', isChecked)
            $(".staf-selector").prop('checked', isChecked)
            $(".mahasiswa-selector").prop('checked', isChecked)
            $(".angkatan-selector").prop('checked', isChecked)
            $(".prodi-selector").prop('checked', isChecked)
        })
        //ketika user klik pilih semua dosen
        selectAllDosen.on('change', e => {
            const isChecked = e.target.checked
            $(".dosen-selector").prop('checked', isChecked)
            $(".mentions-item-dosen").remove()
            if (!isChecked) {
                selectAll.prop("checked", false)
            }
        })

        // Ketika user check semua staf
        selectAllStaf.on('change', e => {
            const isChecked = e.target.checked
            $(".staf-selector").prop('checked', isChecked)
            $(".mentions-item-staf").remove()
            if (!isChecked) {
                selectAll.prop("checked", false)
            }
        })

        //ketika user klik daftar dosen
        $(".dosen-selector").on('change', e => {
            const isChecked = e.target.checked
            if (!isChecked) {
                selectAll.prop("checked", false)
                selectAllDosen.prop("checked", false)
            }
        })

        //ketika user klik daftar staf
        $(".staf-selector").on('change', e => {
            const isChecked = e.target.checked
            if (!isChecked) {
                selectAll.prop("checked", false)
                selectAllStaf.prop("checked", false)
            }
        })
    </script>

    {{-- Mahasiswa --}}
    <script>
        const borderActiveMahasiswa = $("#borderActiveMahasiswa")
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
            borderActiveMahasiswa.css({
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
            borderActiveMahasiswa.css({
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
            borderActiveMahasiswa.css({
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
    {{-- Tab Angkatan --}}

    <script>
        const borderTabD3TE = $("#borderActiveMahasiswaD3TE")
        const borderTabS1TE = $("#borderActiveMahasiswaS1TE")
        const borderTabS1TI = $("#borderActiveMahasiswaS1TI")

        function setDefaultAngkatanDisplay(prodi, angakatan) {
            (`#${prodi}_${angkatan}`).removeClass("active fw-bold text-success")
                (`#${prodi}_${angkatan}_show`).addClass("d-none")
        }

        function setActiveAngkatanDisplay(prodi, angakatan) {
            (`#${prodi}_${angkatan}`).addClass("active fw-bold text-success")
                (`#${prodi}_${angkatan}_show`).removeClass("d-none")
        }
    </script>
    {{-- Handle Checkbox mahasiswa --}}
    <script>
        const selectAllD3TE = $(`#all_1`)
        const selectAllS1TE = $(`#all_2`)
        const selectAllS1TI = $(`#all_3`)

        // Select All Prodi TE D3
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
                selectAll.prop("checked", false)
            }
        })
        // ----------------- END OF TE D3 -----------------

        // Select All Prodi TE S1
        selectAllS1TE.on("change", e => {
            const isChecked = e.target.checked
            $("#s1TECheckList .angkatan-selector").prop("checked", isChecked)
            $("#s1TECheckList .mahasiswa-selector").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
                selectAll.prop("checked", false)
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
                selectAll.prop("checked", false)
            }
        })
        // ----------------- END OF TE S1 -----------------

        // Select All Prodi TI S1
        selectAllS1TI.on("change", e => {
            const isChecked = e.target.checked
            $("#s1TICheckList .angkatan-selector").prop("checked", isChecked)
            $("#s1TICheckList .mahasiswa-selector").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
                selectAll.prop("checked", false)
            }
        })
        // Untuk handle onChange tahun angkatan
        $("#s1TICheckList .angkatan-selector").on("change", e => {
            const isChecked = e.target.checked
            const id = e.target.id
            $(`#check_${id} .mahasiswa-selector`).prop("checked", isChecked)
            if (!isChecked) {
                selectAllS1TI.prop("checked", false)
                selectAll.prop("checked", false)
            }
        })
        // ----------------- END OF TI S1 -----------------

        // Ketika user check semua mahasiswa
        selectAllMhs.on('change', e => {
            const isChecked = e.target.checked
            $(".mahasiswa-selector").prop('checked', isChecked)
            $(".prodi-selector").prop('checked', isChecked)
            $(".angkatan-selector").prop('checked', isChecked)
            selectAll.prop("checked", false)
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
                selectAll.prop("checked", false)
            }
        })
    </script>
@endpush
