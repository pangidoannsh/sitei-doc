<!DOCTYPE html>
<html>
@php
    use Carbon\Carbon;
@endphp

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style type='text/css'>
        body,
        html {
            margin: 0;
            padding: 0;
            color: #424242;
            font-family: "Poppins", sans-serif;
        }

        @page {
            size: 858px 612px landscape;
            padding: 0;
        }

        .container {
            margin: auto;
            width: 858px;
            height: 612px;
            background-image: url("{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/sertifikat/sertif-bg.jpg'))) }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        .sign-sertif {
            text-align: start;
            margin: 24px auto;
            border: 1px solid #BDBDBD;
            padding: 8px;
            max-width: 240px;
        }

        .sign-sertif div:nth-child(1) {
            font-size: 10px;
            margin: 0;
        }

        .sign-sertif div:nth-child(2) {
            font-size: 10px;
            font-weight: 600;
            text-align: center;
            text-transform: uppercase;
        }

        .sign-sertif div:nth-child(3) {
            margin: 0;
            margin-top: 4px;
            font-size: 11px;
            font-weight: 800;
        }

        .content {
            position: relative;
            width: max-content;
            margin: 0 auto;
            top: 17%;
            text-align: center;
            width: 60%;
        }

        .title {
            text-align: center;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 26px;
            line-height: 0.75;
        }

        .common {
            margin-top: 6px;
            font-size: 14px;
            text-align: center;
        }

        .name {
            text-align: center;
            font-weight: 600;
            font-size: 32px;
            line-height: 0.75;
            margin: 12px 0
        }

        .date {
            margin-top: 24px;
            font-size: 14px;
            text-align: center;
        }

        .header {
            position: relative;
            top: 24px;
            width: 100%;
        }

        .left-logos {
            position: absolute;
            left: 48px;
            top: 24px;
        }

        .right-logos {
            position: absolute;
            right: 48px;
            top: 24px;
        }

        .wrapper {
            scale: 1.2;
        }

        .sign {
            position: absolute;
            bottom: 15%;
            right: 10%;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="left-logos">
                    @foreach ($data->sertifikat->logos as $item)
                        @if (optional($item->logo)->position == 'kiri')
                            <div style="margin-right: 16px;display: inline-block">
                                <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('storage/' . $item->logo->url))) }}"
                                    height="42">
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="right-logos">
                    @foreach ($data->sertifikat->logos as $item)
                        @if (optional($item->logo)->position == 'kanan')
                            <div style="margin-left: 16px;display: inline-block">
                                <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('storage/' . $item->logo->url))) }}"
                                    height="42">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="content">
                <div class="title">{{ $data->sertifikat->nama }}</div>
                <div class="common" style="font-size: 12px">{{ $data->nomor_sertif }}</div>
                <div class="common">Diberikan kepada</div>
                <div class="name">
                    @if ($data->user_penerima)
                        {{ $data->nama_display ?? data_get($data, $data->jenis_penerima . '.nama') }}
                    @else
                        {{ $data->nama_penerima }}
                    @endif
                </div>
                <div class="common" style="line-height: 0.8;">{{ $data->sertifikat->isi }}</div>
                <div class="date">Pekanbaru,
                    {{ Carbon::parse($data->sertifikat->tanggal)->translatedFormat('d M Y') }}
                </div>
                <div class="sign-sertif">
                    <div>Ditandatangani secara elektronik oleh:</div>
                    <div>
                        {{ $data->sertifikat->signer_role }}</div>
                    <div>
                        {{ $data->sertifikat->signed->nama }}
                    </div>
                </div>
            </div>
            @if ($qrcode)
                <div class="sign">
                    <img src="data:img/png;base64, {!! $qrcode !!}">
                </div>
            @endif
        </div>
    </div>
</body>

</html>
