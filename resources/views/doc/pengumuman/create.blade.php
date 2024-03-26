@extends('doc.main-layout')

@php
    use Carbon\Carbon;
@endphp

@section('title')
    SITEI | Distribusi Surat & Dokumen
@endsection

@section('content')
    <div class="">
        <h2 class="text-center fw-semibold ">Pengumuman Baru</h2>

        <form action="{{ route('pengumuman.store') }}" method="POST" class="d-flex flex-column gap-3"
            style="position: relative;padding-bottom: 200px" enctype="multipart/form-data">
            @method('post')
            @csrf
            {{-- Nomor Pengumuman --}}
            <div>
                <label for="nomor_pengumuman" class="fw-semibold">Nomor Pengumuman</label>
                <input type="text" class="form-control @error('nomor_pengumuman') is-invalid @enderror rounded-3 py-4"
                    name="nomor_pengumuman" id="nomor_pengumuman" value="{{ old('nomor_pengumuman') }}">
                @error('nomor_pengumuman')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            {{-- Nama --}}
            <div>
                <label for="nama" class="fw-semibold">Nama Pengumuman<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror rounded-3 py-4" name="nama"
                    placeholder="Contoh: Pengumuman..." id="nama" value="{{ old('nama') }}">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            {{-- Semester --}}
            <div>
                <label for="semester" class="fw-semibold">Semester<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="semester" id="semester"
                        class="text-secondary form-select text-capitalize rounded-3 text-capitalize @error('semester') border border-danger @enderror">
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester->nama }}" class="text-capitalize"
                                @if (old('semester')) {{ old('semester') == $semester->nama ? 'selected' : '' }}
                                @else
                                    {{ $semesters->last()->nama == $semester->nama ? 'selected' : '' }} @endif>
                                {{ $semester->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('semester')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            {{-- Kategori --}}
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
            {{-- Isi --}}
            <div>
                <label for="isi" class="fw-semibold">Isi Pengumuman<span class="text-danger">*</span></label>
                <textarea class="form-control rounded-3 py-4" placeholder="Isi Pengumuman" name="isi" id="isi" cols="3">{{ old('isi') }}</textarea>
            </div>
            {{-- Lampiran --}}
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
                    <input type="url" class="form-control rounded-3" value="{{ old('url_dokumen') }}"
                        name="url_dokumen" placeholder="Contoh: https://drive.google.com/..." id="url_dokumen">
                </div>
            </div>
            {{-- Tanggal Batas Pengumuman --}}
            <div>
                <label for="tgl_batas_pengumuman" class="fw-semibold">Tanggal Batas Pengumuman<span
                        class="text-danger">*</span></label>
                <input type="date"
                    class="form-control @error('tgl_batas_pengumuman') is-invalid @enderror rounded-3 py-4"
                    name="tgl_batas_pengumuman" id="tgl_batas_pengumuman" value="{{ old('tgl_batas_pengumuman') }}">
                @error('tgl_batas_pengumuman')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            {{-- Diumumkan Kepada --}}
            <div class="d-flex flex-lg-row flex-column gap-3 mt-2">
                <div style="width: fit-content">
                    <div class="fw-semibold" style="translate: 0 4px">
                        Diumumkan Kepada:
                    </div>
                </div>
                <div class="w-100">
                    <div class="form-check mb-3" style="padding: 0 24px">
                        <input class="form-check-input" type="checkbox" value="all" id="all" name="select_all"
                            {{ old('select_all') ? 'checked' : '' }}>
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
                                name="select_all_dosen" {{ old('select_all_dosen') ? 'checked' : '' }}>
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
                                                value={{ $dosen->nip }} id="select-{{ $dosen->nip }}" name="dosen[]"
                                                {{ old('dosen') && in_array($dosen->nip, old('dosen')) ? 'checked' : '' }}>
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
                                                value={{ $dosen->nip }} id="select-{{ $dosen->nip }}" name="dosen[]"
                                                {{ old('dosen') && in_array($dosen->nip, old('dosen')) ? 'checked' : '' }}>
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
                                name="select_all_staf" {{ old('select_all_staf') ? 'checked' : '' }}>
                            <label class="form-check-label" for="all_staf">
                                Semua Staf Administrasi Teknik Elektro
                            </label>
                        </div>
                        <hr>
                        <div class="row row-cols-2 px-3" style="row-gap: 8px">
                            @foreach ($staffs as $staff)
                                <div class="form-check">
                                    <input class="form-check-input staf-selector" type="checkbox"
                                        value={{ $staff->username }} id="select-{{ $staff->username }}" name="staf[]"
                                        {{ old('staf') && in_array($staff->username, old('staf')) ? 'checked' : '' }}>
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
                                name="select_all_mahasiswa" {{ old('select_all_mahasiswa') ? 'checked' : '' }}>
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
                                    id="all_1" name="select_all_d3te" {{ old('select_all_d3te') ? 'checked' : '' }}>
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
                                    {{-- Tab Angkatan --}}
                                    @foreach ($mahasiswas['1']->sortKeys() as $angkatan => $angkatans)
                                        <div id="1_{{ $angkatan }}" style="min-width: 100px;cursor: pointer;"
                                            class="d3te-tab-angkatan text-center {{ $isActive ? 'active fw-bold text-success' : '' }}">
                                            <input class="form-check-input angkatan-selector d3te-selector"
                                                type="checkbox" value="{{ $angkatan }}"
                                                id="selector_1_{{ $angkatan }}" name="d3te_angkatan[]"
                                                {{ old('d3te_angkatan') && in_array($angkatan, old('d3te_angkatan')) ? 'checked' : '' }}>
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
                                                        {{ old('d3te') && in_array($mahasiswa->nim, array_merge(...old('d3te'))) ? 'checked' : '' }}>
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
                                    id="all_2" name="select_all_s1te" {{ old('select_all_s1te') ? 'checked' : '' }}>
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
                                    {{-- Tab Angkatan --}}
                                    @foreach ($mahasiswas['2']->sortKeys() as $angkatan => $angkatans)
                                        <div id="2_{{ $angkatan }}" style="min-width: 100px;cursor: pointer;"
                                            class="s1te-tab-angkatan text-center {{ $isActive ? 'active fw-bold text-success' : '' }}">
                                            <input class="form-check-input angkatan-selector s1te-selector"
                                                type="checkbox" value="{{ $angkatan }}"
                                                id="selector_2_{{ $angkatan }}" name="s1te_angkatan[]"
                                                {{ old('s1te_angkatan') && in_array($angkatan, old('s1te_angkatan')) ? 'checked' : '' }}>
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
                                                        {{ old('s1te') && in_array($mahasiswa->nim, array_merge(...old('s1te'))) ? 'checked' : '' }}>
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
                                    id="all_3" name="select_all_s1ti" {{ old('select_all_s1ti') ? 'checked' : '' }}>
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
                                    {{-- Tab Angkatan --}}
                                    @foreach ($mahasiswas['3']->sortKeys() as $angkatan => $angkatans)
                                        <div id="3_{{ $angkatan }}" style="min-width: 100px;cursor: pointer;"
                                            class="s1ti-tab-angkatan text-center {{ $isActive ? 'active fw-bold text-success' : '' }}">
                                            <input class="form-check-input angkatan-selector s1ti-selector"
                                                type="checkbox" value="{{ $angkatan }}"
                                                id="selector_3_{{ $angkatan }}" name="s1ti_angkatan[]"
                                                {{ old('s1ti_angkatan') && in_array($angkatan, old('s1ti_angkatan')) ? 'checked' : '' }}>
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
                                                        {{ old('s1ti') && in_array($mahasiswa->nim, array_merge(...old('s1ti'))) ? 'checked' : '' }}>
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

    {{-- Tab Mahasiswa --}}
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
@endpush
