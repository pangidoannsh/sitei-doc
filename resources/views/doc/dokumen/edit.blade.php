@extends('doc.main-layout')

@section('title')
    SITEI | Distribusi Surat & Dokumen
@endsection

@section('content')
    <div class="">
        <h2 class="text-center fw-semibold ">Ubah Dokumen</h2>

        <form action="{{ route('dokumen.update', ['id' => $dokumen->id]) }}" method="POST" class="d-flex flex-column gap-3"
            enctype="multipart/form-data">
            @method('put')
            @csrf
            <div>
                <label for="nama" class="fw-semibold">Nama Dokumen</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror rounded-3 py-4" name="nama"
                    placeholder="Contoh: Jurnal...." id="nama" value="{{ $dokumen->nama }}">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div>
                <label for="kategori" class="fw-semibold">Kategori</label>
                <div class="input-group">
                    <select name="kategori" id="kategori"
                        class="text-secondary text-capitalize rounded-3 text-capitalize @error('kategori') border border-danger @enderror">
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori }}" class="text-capitalize"
                                {{ $dokumen->kategori == $kategori ? 'selected' : '' }}>{{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="text-danger mt-1" style="font-size: 11px">{{ $message }} </div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="keterangan" class="fw-semibold">Keterangan</label>
                <textarea class="form-control rounded-3 py-4" placeholder="Keterangan" name="keterangan" id="keterangan" cols="3">{{ $dokumen->keterangan }}</textarea>
            </div>
            <div id="current-dokumen">
                <label class="fw-semibold">Dokumen</label>
                <div class="d-flex gap-2 align-items-center">
                    @if ($dokumen->url_dokumen != null)
                        <a href="@if ($dokumen->is_local_file) {{ asset('storage/' . $dokumen->url_dokumen) }}
                        @else
                        {{ $dokumen->url_dokumen }} @endif"
                            target="_blank" class="btn btn-outline-primary">
                            Dokumen saat ini
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
                    <label for="dokumen" class="fw-semibold">Dokumen</label>
                    <input type="file" class="form-control rounded-3 @error('dokumen') is-invalid @enderror"
                        name="dokumen" id="dokumen">
                    @error('dokumen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="or-divider">atau</div>
                <div class="w-100">
                    <label for="url_dokumen" class="fw-semibold">Tempel URL Dokumen</label>
                    <input type="url" class="form-control rounded-3"
                        value="@if (!$dokumen->is_local_file) {{ $dokumen->url_dokumen }} @endif" name="url_dokumen"
                        placeholder="https://drive.google.com/..." id="url_dokumen">
                </div>
            </div>
            <div>
                <label for="tanggal" class="fw-semibold">Tanggal Dokumen</label>
                <input type="date" class="form-control @error('tgl_dokumen') is-invalid @enderror rounded-3 py-4"
                    name="tgl_dokumen" id="tanggal" value="{{ $dokumen->tgl_dokumen }}">
                @error('tgl_dokumen')
                    <div class="invalid-feedback">{{ $message }} </div>
                @enderror
            </div>
            <div class="d-flex flex-lg-row flex-column gap-3 mt-2">
                <div style="width: fit-content">
                    <div class="fw-semibold" style="translate: 0 4px">
                        Dikirim Kepada:
                    </div>
                </div>
                {{-- Input for mention user --}}
                <input class="d-none" type="text" id="mentions-input" name="user_mentioned">

                <div id="mentions" class="mentions-wrapper" style="margin-bottom: 200px">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-toggle="modal"
                        data-target="#add_mentions_modal" id="btn-add-mentions" title="Tambah penerima">
                        <div style="scale: 2" class=" d-flex align-items-center align-content-center">
                            <i class="bi bi-plus"></i>
                        </div>
                    </button>
                </div>
            </div>
            <div class="footer-submit">
                <button type="submit" class="btn btn-success">Ubah Dokumen</button>
                <a type="button" class="btn btn-outline-success" href={{ url()->previous() }}>Kembali</a>
            </div>
        </form>

    </div>

    <!-- Modal -->
    <div class="modal fade w-100" id="add_mentions_modal" tabindex="-1" role="dialog" aria-labelledby="mentions"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content p-4 rounded-lg d-flex flex-column gap-4">
                <div class="d-flex gap-1 flex-column">
                    <h5 class="modal-title">Tandai Orang</h5>
                    <div class="divider-green"></div>
                </div>
                <div class="form-check" style="padding: 0 24px">
                    <input class="form-check-input" type="checkbox" value="all_dosen" id="select_all_dosen">
                    <label class="form-check-label" for="select_all_dosen">
                        Semua Dosen Teknik Elektro
                    </label>
                </div>
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
                                        value={{ $dosen->nip }} id="select-{{ $dosen->nip }}" name="dosen[]">
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
                                        value={{ $dosen->nip }} id="select-{{ $dosen->nip }}" name="dosen[]">
                                    <label class="form-check-label" for="select-{{ $dosen->nip }}">
                                        {{ $dosen->nama }}
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <button id="btn-close-modal" type="button" class="btn btn-success rounded-3 py-3"
                    data-dismiss="modal">Selesai</button>
            </div>
        </div>
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
        const currentDokumen = "{{ $dokumen->url_dokumen }}"

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
    <script>
        let mentions = []
        const mentionsInput = $("#mentions-input")

        function handleDelete(id) {
            $(`#mention-${id}`).remove()
            $(`#select-${id}`).prop('checked', false);
            mentions = mentions.filter(mention => mention.id != id).map(data => data);
            selectAllDosen.prop("checked", false)
            mentionsInput.val(mentions.map(mention => mention.id).join("--"))
            // console.log(mentionsInput.val());
        }

        function MentionsItem(name, id) {
            var mentionsItem = $(`<div class="mentions-item" id="mention-${id}"></div>`);
            mentionsItem.text(name);
            var deleteButton = $('<button class="delete-btn" type="button"><i class="bi bi-x-lg"></i></button>');
            deleteButton.on('click', () => handleDelete(id));
            mentionsItem.append(deleteButton);
            mentionsItem.insertBefore(addButton)
        }

        let dosenOptions = @json($dosens).map(data => ({
            id: data.nip,
            name: data.nama
        }));

        // Setup display mentioned dosen
        @foreach ($dokumen->mentions as $mention)
            @switch($mention->jenis_user)
                @case('dosen')
                $("#select-{{ $mention->dosen->nip }}").prop('checked', true)
                mentions.push({
                    id: "{{ $mention->dosen->nip }}",
                    name: "{{ $mention->dosen->nama }}"
                })
                @break

                @case('admin')
                $("#select-{{ $mention->admin->username }}").prop('checked', true)
                mentions.push({
                    id: "{{ $mention->admin->username }}",
                    name: "{{ $mention->admin->nama }}"
                })
                @break

                @case('mahasiswa')
                mentions.push({
                    id: "{{ $mention->mahasiswa->nim }}",
                    name: "{{ $mention->mahasiswa->nama }}"
                })
                $("#select-{{ $mention->mahasiswa->nim }}").prop('checked', true)
                @break
            @endswitch
        @endforeach
        const selectAllDosen = $("#select_all_dosen")

        var addButton = $('#btn-add-mentions');

        //ketika user klik pilih semua dosen
        selectAllDosen.on('change', e => {
            const isChecked = e.target.checked
            $(".dosen-selector").prop('checked', isChecked)
            if (isChecked) {

                mentions.forEach(mention => {
                    $(`#mention-${mention.id}`).remove();
                })
                mentions = dosenOptions
                mentions.forEach(mention => {
                    MentionsItem(mention.name, mention.id)
                });
            } else {
                mentions = []
                $(".mentions-item").remove()
            }
            mentionsInput.val(mentions.map(mention => mention.id).join("--"))
        })

        //ketika user klik daftar dosen
        $(".dosen-selector").on('change', e => {
            const isChecked = e.target.checked
            const id = e.target.value
            const name = dosenOptions.find(dosen => dosen.id === id).name
            if (isChecked) {
                mentions.push({
                    id,
                    name
                })
                MentionsItem(name, id)
            } else {
                selectAllDosen.prop("checked", false)
                mentions = mentions.filter(mention => mention.id !== id).map(data => data)
                $(`#mention-${id}`).remove()
            }
            mentionsInput.val(mentions.map(mention => mention.id).join("--"))
        })
        mentions.map(mention => MentionsItem(mention.name, mention.id))
        mentionsInput.val(mentions.map(mention => mention.id).join("--"))
    </script>
@endpush
