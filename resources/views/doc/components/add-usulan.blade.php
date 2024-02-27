<div class="dropdown">
    @if ($jenis_user != 'mahasiswa')
        <div class="d-flex gap-3">
            <a class="btn btn-success w-content mb-3 d-flex align-items-center justify-content-center fw-bold gap-2 rounded-2"
                href="{{ route('dokumen.create') }}">
                <i class="fa-solid fa-plus"></i>
                Dokumen
            </a>
            <button
                class="btn btn-success w-content mb-3 d-flex align-items-center justify-content-center fw-bold gap-2 rounded-2"
                type="button" id="dropdownAddSurat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-plus"></i>
                Surat
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownAddSurat">
                <a class="dropdown-item" href="{{ route('surat.create') }}">Usulan Surat</a>
                <a class="dropdown-item" href="{{ route('suratcuti.create') }}">Surat Cuti</a>
            </div>
            <a class="btn btn-success w-content mb-3 d-flex align-items-center justify-content-center fw-bold gap-2 rounded-2"
                href="{{ route('sertif.create') }}">
                <i class="fa-solid fa-plus"></i>
                Sertifikat
            </a>
        </div>
    @else
        <a href="{{ route('surat.create') }}"
            class="btn btn-success w-content mb-3 d-flex align-items-center justify-content-center fw-bold gap-2 rounded-2">
            <i class="fa-solid fa-plus"></i>
            Usulan Surat
        </a>
    @endif
</div>
