<!DOCTYPE html>
<html>

<head>
    <title>KPTI/TE-1 Surat Permohonan KP</title>
    @php
        use Carbon\Carbon;
        use SimpleSoftwareIO\QrCode\Facades\QrCode;
    @endphp
    <style type="text/css">
        table {
            border-style: double;
            border-width: 3px;
            border-color: white;
            margin: 1%;
            /* border-collapse: collapse; */
        }

        /*design table 1*/
        .table1 {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #232323;
            border-collapse: collapse;
            border: 1px solid #999;
            padding: 8px 20px;
            margin-top: 30px;
            margin-left: auto;
            margin-right: auto;
        }

        table tr .text2 {
            text-align: right;
            font-size: 13pt;
        }

        table tr .text {
            text-align: center;
            font-size: 13px;
        }

        table tr td {
            font-size: 13px;
        }

        table,
        th,
        td {
            /* border: 1px solid black; */
        }


        tr .tr2 {
            border-bottom: 1pt solid black;
        }

        @page {
            size: A4 portrait;
            margin: 1cm;
            padding: 0; // you can set margin and padding 0
        }

        body {
            font-family: Times New Roman;
            font-size: 33px;
        }

        body .isi {
            margin: 0 1.5cm 0 1.5cm;
        }

        .container {
            position: relative;
            text-align: left;
            color: black;
        }

        .ttd {
            position: absolute;
            margin: 0%;
            left: 0%;
        }

        .logo {
            position: absolute;
            top: 7%;
            right: 73%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body>

    <div class="isi">

        <table width="100%" style="margin-bottom: 0%">
            <tr>
                <td>
                    <div class="logo">
                        <img id="img" src="https://live.staticflickr.com/65535/51644220143_f5dba04544_o_d.png"
                            width="110" height="110">
                    </div>
                </td>
                <td>
                    @foreach ($pendaftaran_kp as $kp)
                        <center>
                            <font size="4">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN</font><br>
                            <font size="4">RISET DAN TEKNOLOGI</font><br>
                            <font size="3"><b>UNIVERSITAS RIAU - FAKULTAS TEKNIK</b></font><br>
                            <font size="3"><b>JURUSAN TEKNIK ELEKTRO</b></font><br>
                            @if ($kp->mahasiswa->prodi_id == 1)
                                <font size="3"><b>PROGRAM STUDI TEKNIK ELEKTRO D3</b></font><br>
                            @elseif ($kp->mahasiswa->prodi_id == 2)
                                <font size="3"><b>PROGRAM STUDI TEKNIK ELEKTRO S1</b></font><br>
                            @else
                                <font size="3"><b>PROGRAM STUDI TEKNIK INFORMATIKA</b></font><br>
                            @endif
                            <font size="2">Kampus Bina Widya Km. 12,5 Simpang Baru Pekanbaru 28293</font><br>
                            <font size="2">Telepon (0761) 66596 Faksimile (0761) 66595</font><br>
                            @if ($kp->mahasiswa->prodi_id == 1)
                                <font size="2">Laman: <u>http://elektrod3.ft.unri.ac.id</u></font>
                            @elseif ($kp->mahasiswa->prodi_id == 2)
                                <font size="2">Laman: <u>http://elektros1.ft.unri.ac.id</u></font>
                            @else
                                <font size="2">Laman: <u>http://informatika.ft.unri.ac.id</u></font>
                            @endif
                        </center>
                        @foreach
                </td>
            </tr>

            <table width="600px" style="text-align:center">
                <tr>
                    <td>
                        <hr style="margin: 1px; border: 2px solid black">
                        <hr style="margin: 1px; border: 1px solid black">
                    </td>
                </tr>
            </table>

        </table>

        <table width="100%" style="text-align:center; margin-top:0px;">
            <tr>
                <td style="font-size:12pt;text-decoration: underline;">
                    <strong>LEMBAR PERBAIKAN SEMINAR KERJA PRAKTEK</strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="text-align: right; margin-top:-40px;">
            <tr>
                <td style="font-size:12pt;">
                    @if ($pendaftaran_kp->mahasiswa->prodi_id == 1)
                        <strong style="border:1px solid #000; padding:4px">KPTE-9</strong>
                    @elseif ($pendaftaran_kp->mahasiswa->prodi_id == 2)
                        <strong style="border:1px solid #000; padding:4px">KPTE-9</strong>
                    @else
                        <strong style="border:1px solid #000; padding:4px">KPTI-9</strong>
                    @endif
                </td>
            </tr>
        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:20px; line-height: 1.5">
            <tr class="text2">
                <td>Nama Mahasiswa</td>
                <td>:</td>
                <td width="70%">{{ $pendaftaran_kp->mahasiswa->nama }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td width="70%">{{ $pendaftaran_kp->mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td>Judul KP</td>
                <td>:</td>
                <td width="70%">{{ $pendaftaran_kp->judul_kp }}</td>
            </tr>

            <tr class="text2">
                <td width="30%">Tanggal Seminar KP</td>
                <td>:</td>
                <td width="70%">{{ Carbon::parse($pendaftaran_kp->tanggal_mulai)->translatedFormat('l, d F Y') }}
                </td>
            </tr>

            <tr>
                <td>Dosen Penguji</td>
                <td>:</td>
                <td width="70%">DOSEN</td>
            </tr>
        </table>



        <table width="100%" style="font-family: Arial, sans-serif; margin-top:100px;">
            <tr>
                <td width="60%" align="right">
                    <!-- Disini untuk perintah Qr code -->
                </td>
                <td class="text" style="text-align: left;">
                    <div class="container">
                        <p>Pekanbaru, {{ Carbon::parse($pendaftaran_kp->tanggal_mulai)->translatedFormat('d F Y') }}
                        </p>
                        <p>Dosen Penguji</p>
                        <div class="ttd">
                            <img src="data:img/png;base64, {!! $qrcode !!}">
                            {{-- {{ QrCode::size(80)->generate(url('/surat-permohonan-kp-data'. '/' . $pendaftaran_kp->id)); }}             --}}
                        </div>
                        <br><br><br><br><br><br>
                        <strong
                            style="text-decoration: underline;">{{ $pendaftaran_kp->dosen_pembimbing_nip->nama }}</strong><br>NIP.
                        {{ $pendaftaran_kp->dosen_pembimbing_nip }}
                    </div>
                    <br>
                </td>
            </tr>
        </table>

        <!--<table width="100%" style="margin-top: 60px;">-->
        <!--    <tr>-->
        <!--        <td style="font-size:10px;"><i>*catatan:</i></td>  -->
        <!--    </tr>-->

        <!--    <tr>-->
        <!--    <td style="font-size:10px;"><i><b>Dosen penguji disarankan langsung membubuhkan tanda tangan pada lembar perbaikan</b></i></td>                -->
        <!--    </tr>-->

        <!--    <tr>-->
        <!--    <td style="font-size:10px;"><i>Lembar ini dapat diminta langsung oleh mahasiswa ke Admin Prodi pasca Seminar KP</i></td>                        -->
        <!--    </tr>   -->
        <!--</table>-->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
</body>

</html>
