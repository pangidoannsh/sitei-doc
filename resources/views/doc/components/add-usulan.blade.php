<div class="dropdown">
    @if ($jenis_user != 'mahasiswa')
        <button type="button" id="dropdownAddDokumen" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="btn btn-success w-content mb-3 d-flex align-items-center justify-content-center fw-bold gap-2 rounded-2">
            <i class="fa-solid fa-plus"></i>
            Usulan
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownAddDokumen">
            <a class="dropdown-item" href="{{ route('pengumuman.create') }}">Pengumuman</a>
            <a class="dropdown-item" href="{{ route('dokumen.create') }}">Dokumen</a>
            <a class="dropdown-item" href="{{ route('suratcuti.create') }}">Surat Cuti</a>
            <a class="dropdown-item" href="{{ route('surat.create') }}">Surat</a>
            <a class="dropdown-item" href="{{ route('sertif.create') }}">Sertifikat</a>
        </div>
    @else
        <a href="{{ route('surat.create') }}"
            class="btn btn-success w-content mb-3 d-flex align-items-center justify-content-center fw-bold gap-2 rounded-2">
            <i class="fa-solid fa-plus"></i>
            Ajukan Surat
        </a>
    @endif
</div>
