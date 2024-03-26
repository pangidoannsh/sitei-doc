@extends('doc.main-layout')

@section('title')
    SITEI | Distribusi Surat & Dokumen
@endsection

@section('content')
    <div class="">
        <h2 class="text-center fw-semibold ">Ubah Pengumuman</h2>

        <form action="{{ route('pengumuman.update', $data->id) }}" method="POST" class="d-flex flex-column gap-3"
            style="position: relative;padding-bottom: 200px" enctype="multipart/form-data">
            @method('put')
            @csrf
            {{-- Nomor Pengumuman --}}
            <div>
                <label for="nomor_pengumuman" class="fw-semibold">Nomor Pengumuman</label>
                <input type="text" class="form-control @error('nomor_pengumuman') is-invalid @enderror rounded-3 py-4"
                    name="nomor_pengumuman" id="nomor_pengumuman"
                    value="{{ old('nomor_pengumuman') ?? $data->nomor_pengumuman }}">
                @error('nomor_pengumuman')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            {{-- Nama --}}
            <div>
                <label for="nama" class="fw-semibold">Nama Pengumuman<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror rounded-3 py-4" name="nama"
                    placeholder="Contoh: Pengumuman..." id="nama" value="{{ old('nama') ?? $data->nama }}">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            {{-- Semester --}}
            <div>
                <label for="semester" class="fw-semibold">Semester<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="semester" id="semester"
                        class="text-secondary text-capitalize rounded-3 text-capitalize @error('semester') border border-danger @enderror">
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester->nama }}" class="text-capitalize"
                                @if (old('semester')) {{ old('semester') == $semester->nama ? 'selected' : '' }}
                                @else
                                    {{ $data->semester == $semester->nama ? 'selected' : '' }} @endif>
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
                <label for="kategori" class="fw-semibold">Kategori<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="kategori" id="kategori"
                        class="text-secondary text-capitalize rounded-3 text-capitalize @error('kategori') border border-danger @enderror">
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori }}" class="text-capitalize"
                                {{ (old('kategori') ?? $data->kategori) == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
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
                <textarea class="form-control rounded-3 py-4" placeholder="Isi Pengumuman" name="isi" id="isi" cols="3">{{ old('isi') ?? $data->isi }}</textarea>
            </div>
            <div id="current-dokumen">
                <label class="fw-semibold">Lampiran</label>
                <div class="d-flex gap-2 align-items-center">
                    @if ($data->url_dokumen || $data->url_dokumen_lokal)
                        @if ($data->url_dokumen_lokal)
                            <a href="{{ asset('storage/' . $data->url_dokumen_lokal) }}" target="_blank"
                                class="btn btn-success">
                                Lampiran saat ini
                            </a>
                        @endif
                        @if ($data->url_dokumen)
                            <a href="{{ $data->url_dokumen }}" target="_blank" class="btn btn-success">
                                Lampiran saat ini
                            </a>
                        @endif
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
                        value="{{ old('url_dokumen') ?? $data->url_dokumen }}" name="url_dokumen"
                        placeholder="https://drive.google.com/..." id="url_dokumen">
                </div>
            </div>
            <div>
                <label for="tgl_batas_pengumuman" class="fw-semibold">Tanggal Batas Pengumuman<span
                        class="text-danger">*</span></label>
                <input type="date"
                    class="form-control @error('tgl_batas_pengumuman') is-invalid @enderror rounded-3 py-4"
                    name="tgl_batas_pengumuman" id="tgl_batas_pengumuman"
                    value="{{ old('tgl_batas_pengumuman') ?? $data->tgl_batas_pengumuman }}">
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
                    <div id="mahasiswaCheckList" style="min-height: 460px">
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
                                {{-- Tab --}}
                                <div class="breadcrumb col-lg-12 mb-4 d-flex position-relative" style="padding:12px 0;">
                                    <div id="borderActiveMahasiswaD3TE" class="border-active" style="width: 100px"></div>
                                    @php
                                        $isActive = true;
                                    @endphp
                                    @foreach ($mahasiswas['1']->sortKeys() as $angkatan => $angkatans)
                                        <div id="1_{{ $angkatan }}" style="min-width: 100px;cursor: pointer;"
                                            class="d3te-tab-angkatan text-center {{ $isActive ? 'active fw-bold text-success' : '' }}">
                                            <input class="form-check-input angkatan-selector d3te-selector"
                                                type="checkbox" value="{{ $angkatan }}"
                                                id="selector_1_{{ $angkatan }}" name="d3te_angkatan[]"
                                                {{ in_array("d3te_$angkatan", $data->mentions->pluck('user_mentioned')->toArray()) ? 'checked' : '' }}>
                                            {{ $angkatan }}
                                        </div>
                                        @php
                                            $isActive = false;
                                        @endphp
                                    @endforeach
                                </div>
                                <div id="d3teCheckList">
                                    @php
                                        $isActive = true;
                                    @endphp
                                    @foreach ($mahasiswas['1']->sortKeys() as $angkatan => $angkatans)
                                        <div id="check_1_{{ $angkatan }}"
                                            class="{{ !$isActive ? 'd-none' : '' }} row row-cols-2">
                                            @foreach ($angkatans->sortBy('nama') as $mahasiswa)
                                                <div class="col form-check mb-2">
                                                    <input class="form-check-input mahasiswa-selector d3te-selector"
                                                        type="checkbox" value="{{ $mahasiswa->nim }}"
                                                        id="1_{{ $angkatan }}_{{ $mahasiswa->nim }}"
                                                        name="d3te[{{ $angkatan }}][]"
                                                        {{ in_array($mahasiswa->nim, $data->mentions->pluck('user_mentioned')->toArray()) ||
                                                        in_array("d3te_$angkatan", $data->mentions->pluck('user_mentioned')->toArray())
                                                            ? 'checked'
                                                            : '' }}>
                                                    <label class="form-check-label text-capitalize"
                                                        for="1_{{ $angkatan }}_{{ $mahasiswa->nim }}">
                                                        {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})
                                                    </label>
                                                </div>
                                            @endforeach
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
                                        <div id="2_{{ $angkatan }}" style="min-width: 100px;cursor: pointer;"
                                            class="s1te-tab-angkatan text-center {{ $isActive ? 'active fw-bold text-success' : '' }}">
                                            <input class="form-check-input angkatan-selector s1te-selector"
                                                type="checkbox" value="{{ $angkatan }}"
                                                id="selector_2_{{ $angkatan }}" name="s1te_angkatan[]"
                                                {{ in_array("s1te_$angkatan", $data->mentions->pluck('user_mentioned')->toArray()) ? 'checked' : '' }}>
                                            {{ $angkatan }}
                                        </div>
                                        @php
                                            $isActive = false;
                                        @endphp
                                    @endforeach
                                </div>
                                <div id="s1teCheckList">
                                    @php
                                        $isActive = true;
                                    @endphp
                                    @foreach ($mahasiswas['2']->sortKeys() as $angkatan => $angkatans)
                                        <div id="check_2_{{ $angkatan }}"
                                            class="{{ !$isActive ? 'd-none' : '' }} row row-cols-2">
                                            @foreach ($angkatans->sortBy('nama') as $mahasiswa)
                                                <div class="col form-check mb-2">
                                                    <input class="form-check-input mahasiswa-selector s1te-selector"
                                                        type="checkbox" value="{{ $mahasiswa->nim }}"
                                                        id="2_{{ $angkatan }}_{{ $mahasiswa->nim }}"
                                                        name="s1te[{{ $angkatan }}][]"
                                                        {{ in_array($mahasiswa->nim, $data->mentions->pluck('user_mentioned')->toArray()) ||
                                                        in_array("s1te_$angkatan", $data->mentions->pluck('user_mentioned')->toArray())
                                                            ? 'checked'
                                                            : '' }}>
                                                    <label class="form-check-label text-capitalize"
                                                        for="2_{{ $angkatan }}_{{ $mahasiswa->nim }}">
                                                        {{ Str::ucfirst($mahasiswa->nama) }} ({{ $mahasiswa->nim }})
                                                    </label>
                                                </div>
                                            @endforeach
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
                                    <div id="borderActiveMahasiswaS1TI" class="border-active" style="width: 100px"></div>
                                    @php
                                        $isActive = true;
                                    @endphp
                                    {{-- Tab --}}
                                    @foreach ($mahasiswas['3']->sortKeys() as $angkatan => $angkatans)
                                        <div id="3_{{ $angkatan }}" style="min-width: 100px;cursor: pointer;"
                                            class="s1ti-tab-angkatan text-center {{ $isActive ? 'active fw-bold text-success' : '' }}">
                                            <input class="form-check-input angkatan-selector s1ti-selector"
                                                type="checkbox" value="{{ $angkatan }}"
                                                id="selector_3_{{ $angkatan }}" name="s1ti_angkatan[]"
                                                {{ in_array("s1ti_$angkatan", $data->mentions->pluck('user_mentioned')->toArray()) ? 'checked' : '' }}>
                                            {{ $angkatan }}
                                        </div>
                                        @php
                                            $isActive = false;
                                        @endphp
                                    @endforeach
                                </div>
                                <div id="s1tiCheckList">
                                    @php
                                        $isActive = true;
                                    @endphp
                                    @foreach ($mahasiswas['3']->sortKeys() as $angkatan => $angkatans)
                                        <div id="check_3_{{ $angkatan }}"
                                            class="{{ !$isActive ? 'd-none' : '' }} row row-cols-2">
                                            @foreach ($angkatans->sortBy('nama') as $mahasiswa)
                                                <div class="col form-check mb-2">
                                                    <input class="form-check-input mahasiswa-selector s1ti-selector"
                                                        type="checkbox" value="{{ $mahasiswa->nim }}"
                                                        id="3_{{ $angkatan }}_{{ $mahasiswa->nim }}"name="s1ti[{{ $angkatan }}][]"
                                                        {{ in_array($mahasiswa->nim, $data->mentions->pluck('user_mentioned')->toArray()) ||
                                                        in_array("s1ti_$angkatan", $data->mentions->pluck('user_mentioned')->toArray())
                                                            ? 'checked'
                                                            : '' }}>
                                                    <label class="form-check-label text-capitalize"
                                                        for="3_{{ $angkatan }}_{{ $mahasiswa->nim }}">
                                                        {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})
                                                    </label>
                                                </div>
                                            @endforeach
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
        const currentDokumen = "{{ $data->url_dokumen }}"

        const btnEditDokumen = $("#btn-edit-dokumen")
        btnEditDokumen.on("click", () => {
            $("#input-dokumen").removeClass("d-none")
            $("#input-dokumen").addClass("d-flex")
        })

        $("#close-button-input").on('click', () => {
            $("#input-dokumen").removeClass("d-flex")
            $("#input-dokumen").addClass("d-none")
            $("#url_dokumen").val(currentDokumen)
        })
    </script>
    {{-- Tab Mahasiswa --}}
    <script>
        const selectAllMhs = $("#all_mahasiswa")

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

        function setDefaultAngkatanDisplay(id) {
            $(`#${id}`).removeClass("active fw-bold text-success");
            $(`#check_${id}`).addClass("d-none")
        }

        function setActiveAngkatanDisplay(id) {
            $(`#${id}`).addClass("active fw-bold text-success");
            $(`#check_${id}`).removeClass("d-none")
        }

        // S1 Teknik Elektro
        const d3teTabAngkatan = $(".d3te-tab-angkatan")
        d3teTabAngkatan.on("click", e => {
            const id = e.target.id.replace("selector_", "")
            for (let i = 0; i < d3teTabAngkatan.length; i++) {
                if (d3teTabAngkatan[i].id === id) {
                    setActiveAngkatanDisplay(d3teTabAngkatan[i].id)
                    borderTabD3TE.css({
                        "translate": `${i*100}% 0`
                    })
                } else setDefaultAngkatanDisplay(d3teTabAngkatan[i].id)
            }
        })

        // S1 Teknik Elektro
        const s1teTabAngkatan = $(".s1te-tab-angkatan")
        s1teTabAngkatan.on("click", e => {
            const id = e.target.id.replace("selector_", "")
            for (let i = 0; i < s1teTabAngkatan.length; i++) {
                if (s1teTabAngkatan[i].id === id) {
                    setActiveAngkatanDisplay(s1teTabAngkatan[i].id)
                    borderTabS1TE.css({
                        "translate": `${i*100}% 0`
                    })
                } else setDefaultAngkatanDisplay(s1teTabAngkatan[i].id)
            }
        })

        // S1 Teknik Informatika
        const s1tiTabAngkatan = $(".s1ti-tab-angkatan")
        s1tiTabAngkatan.on("click", e => {
            const id = e.target.id.replace("selector_", "")
            for (let i = 0; i < s1tiTabAngkatan.length; i++) {
                if (s1tiTabAngkatan[i].id === id) {
                    setActiveAngkatanDisplay(s1tiTabAngkatan[i].id)
                    borderTabS1TI.css({
                        "translate": `${i*100}% 0`
                    })
                } else setDefaultAngkatanDisplay(s1tiTabAngkatan[i].id)
            }
        })
    </script>
    {{-- Handle Checkbox mahasiswa --}}
    <script>
        const selectAllD3TE = $(`#all_1`)
        const selectAllS1TE = $(`#all_2`)
        const selectAllS1TI = $(`#all_3`)

        // Select All Prodi TE D3
        selectAllD3TE.on("change", e => {
            const isChecked = e.target.checked
            $(".d3te-selector").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
                selectAll.prop("checked", false)
            }
        })
        // Untuk handle onChange tahun angkatan
        $("#d3TE .angkatan-selector").on("change", e => {
            const isChecked = e.target.checked
            const id = e.target.id.replace("selector_", "")
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
            $(".s1te-selector").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
                selectAll.prop("checked", false)
            }
        })

        // Untuk handle onChange tahun angkatan
        $("#s1TE .angkatan-selector").on("change", e => {
            const isChecked = e.target.checked
            const id = e.target.id.replace("selector_", "")
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
            $(".s1ti-selector").prop("checked", isChecked)
            if (!isChecked) {
                selectAllMhs.prop("checked", false)
                selectAll.prop("checked", false)
            }
        })
        // Untuk handle onChange tahun angkatan
        $("#s1TI .angkatan-selector").on("change", e => {
            const isChecked = e.target.checked
            const id = e.target.id.replace("selector_", "")
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
                const [prodiID, angkatan, nim] = idElement.split("_")
                $(`#all_${prodiID}`).prop("checked", false)
                $(`#selector_${prodiID}_${angkatan}`).prop("checked", false)
                selectAllMhs.prop("checked", false)
                selectAll.prop("checked", false)
            }
        })
    </script>

    {{-- Edit --}}
    <script>
        @if ($data->for_all_mahasiswa)
            selectAllMhs.prop('checked', true)
            $(".mahasiswa-selector").prop('checked', true)
            $(".angkatan-selector").prop('checked', true)
            $(".prodi-selector").prop('checked', true)
        @endif
        @if (in_array('d3te_all', $data->mentions->pluck('user_mentioned')->toArray()))
            selectAllD3TE.prop('checked', true)
            $(".d3te-selector").prop('checked', true)
        @endif
        @if (in_array('s1te_all', $data->mentions->pluck('user_mentioned')->toArray()))
            selectAllS1TE.prop('checked', true)
            $(".s1te-selector").prop('checked', true)
        @endif
        @if (in_array('s1ti_all', $data->mentions->pluck('user_mentioned')->toArray()))
            selectAllS1TI.prop('checked', true)
            $(".s1ti-selector").prop('checked', true)
        @endif
    </script>
@endpush
