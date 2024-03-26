@php
    use Carbon\Carbon;

    function generateName($inputString)
    {
        $posisiTandaKoma = strpos($inputString, ',');
        if ($posisiTandaKoma !== false) {
            $inputString = substr_replace($inputString, '', $posisiTandaKoma);
        }
        $originalName = str_replace(['Prof.', 'Ir.', 'Dr.'], '', $inputString);
        return str_replace(['Prof.', 'Ir.', 'Dr.'], '', $inputString);
    }
@endphp
<div>
    <div class="d-flex justify-content-center" style="scale: .7;margin-top: -96px">
        <div
            style="
                width: 858px;
                height: 612px;
                background-image: url('{{ asset('assets/sertifikat/sertif-bg.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                position: relative;">

            <div class="d-flex justify-content-between" style="padding: 48px;">
                <div class="d-flex gap-4">
                    @foreach ($data->sertifikat->logos ?? $data->logos as $item)
                        @if (optional($item->logo)->position == 'kiri')
                            <img src="{{ asset('storage/' . $item->logo->url) }}" height="42">
                        @endif
                    @endforeach
                </div>
                <div>
                    @foreach ($data->sertifikat->logos ?? $data->logos as $item)
                        @if (optional($item->logo)->position == 'kanan')
                            <img src="{{ asset('storage/' . $item->logo->url) }}" height="42">
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="sertif-content">
                <div class="sertif-title">
                    {{ optional($data->sertifikat)->nama ?? $data->nama }}
                </div>
                <div class="common" style="font-size: 12px">{{ $data->nomor_sertif ?? '-nomor sertifikat-' }}</div>
                <div class="common">Diberikan kepada</div>
                <div class="sertif-nama-penerima">
                    @if ($data->user_penerima)
                        {{ $data->nama_display ?? generateName(data_get($data, $data->jenis_penerima . '.nama')) }}
                    @elseif($data->nama_penerima)
                        {{ generateName($data->nama_penerima) }}
                    @else
                        -nama penerima-
                    @endif
                </div>
                {{-- <div class="common">telah menyelesaikan pelatihan</div> --}}
                <div class="common">{{ optional($data->sertifikat)->isi ?? $data->isi }}</div>
                <div class="common" style="margin-top: 28px">Pekanbaru,
                    {{ Carbon::parse($data->tanggal ?? $data->sertifikat->tanggal)->translatedFormat('d M Y') }}</div>
                <div class="sign-sertif">
                    <div>Ditandatangani secara elektronik oleh:</div>
                    <div id="signer_role_preview">
                        {{ $data->sertifikat->signer_role ?? $data->signer_role }}</div>
                    <div id="sign_by_preview">
                        {{ $data->sertifikat->signed->nama ?? $data->signed->nama }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
