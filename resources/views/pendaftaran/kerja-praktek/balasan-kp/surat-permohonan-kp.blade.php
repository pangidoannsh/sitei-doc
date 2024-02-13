<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

<head>
    <title>KPTI-1 Surat Permohonan Kerja Praktek</title>
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
                    <center>
                        <font size="4">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN</font><br>
                        <font size="4">RISET DAN TEKNOLOGI</font><br>
                        <font size="3"><b>UNIVERSITAS RIAU - FAKULTAS TEKNIK</b></font><br>
                        <font size="3"><b>JURUSAN TEKNIK ELEKTRO</b></font><br>
                        @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                            <font size="3"><b>PROGRAM STUDI TEKNIK ELEKTRO D3</b></font><br>
                        @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                            <font size="3"><b>PROGRAM STUDI TEKNIK ELEKTRO S1</b></font><br>
                        @else
                            <font size="3"><b>PROGRAM STUDI TEKNIK INFORMATIKA</b></font><br>
                        @endif
                        <font size="2">Kampus Bina Widya Km. 12,5 Simpang Baru Pekanbaru 28293</font><br>
                        <font size="2">Telepon (0761) 66596 Faksimile (0761) 66595</font><br>
                        @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                            <font size="2">Laman: <u>http://elektrod3.ft.unri.ac.id</u></font>
                        @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                            <font size="2">Laman: <u>http://elektros1.ft.unri.ac.id</u></font>
                        @else
                            <font size="2">Laman: <u>http://informatika.ft.unri.ac.id</u></font>
                        @endif
                    </center>
                </td>
            </tr>

            <table width="600px" style="text-align:center;">
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
                    <strong>SURAT PERMOHONAN KP</strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="text-align: right; margin-top:-40px;">
            <tr>
                <td style="font-size:12pt;">
                    @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                        <strong style="border:1px solid #000; padding:4px">KPTE-1</strong>
                    @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                        <strong style="border:1px solid #000; padding:4px">KPTE-1</strong>
                    @else
                        <strong style="border:1px solid #000; padding:4px">KPTI-1</strong>
                    @endif
                </td>
            </tr>
        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:0px; line-height: 1.5">
            <tr class="text2">
                <td width="27%">Kepada Yth.</td>
            </tr>
            <tr>
                
                <td width="27%">
                    @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                            Koordinator Program Studi <span><b><u>{{ $kaprodi1->nama }}</u></b></span>
                        @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                            Koordinator Program Studi <span><b><u>{{ $kaprodi2->nama }}</u></b></span>
                        @else
                            Koordinator Program Studi <span><b><u>{{ $kaprodi3->nama }}</u></b></span>
                        @endif
                </td>

            </tr>
            <tr>
                <td width="27%">Di Tempat</td>
            </tr>
        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:0px; line-height: 1.5">
            <tr class="text2">
                <td width="27%">Dengan Hormat,</td>
            </tr>
            <tr>
                <td width="27%">Saya yang bertanda tangan dibawah ini :
                </td>
            </tr>
        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:0px; line-height: 1.5">
            <tr class="text2">
                <td>Nama</td>
                <td>:</td>
                <td width="70%">{{ $pendaftaran_kp->mahasiswa->nama }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td width="70%">{{ $pendaftaran_kp->mahasiswa->nim }}</td>
            </tr>
        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:0px; line-height: 1.5">
            <tr class="text2">
                <td width="27%">Mengajukan permohonan KP, dengan memenuhi syarat-syarat berikut :</td>
            </tr>
            <tr class="text2">
                <td style="padding-left: 20px;" width="27%">a. Memiliki Indeks Prestasi Kumulatif <span>≥</span> 2,00.</td>
            </tr>
            <tr class="text2">
                <td style="padding-left: 20px;" width="27%">b. Telah mengikuti 90 SKS mata kuliah.</td>
            </tr>
            <tr class="text2">
                <td style="padding-left: 20px;" width="27%">c. Telah mengisi matakuliah KP di KRS semester berjalan.</td>
            </tr>

        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:0px; line-height: 1.5">
            <tr class="text2">
                <td width="27%">Sebagai bukti terpenuhinya syarat-syarat tersebut, terlampir:</td>
            </tr>
            <tr class="text2">
                <td style="padding-left: 20px;" width="27%">1. Copy Transkrip / KHS Terakhir.</td>
            </tr>
        </table>


        <table width="100%" style="font-family: Arial, sans-serif; margin-top:180px;">
            <tr>
                <td class="text" style="text-align: left;">
                    <div class="container">
                        <p>Menyetujui,</p>
                        @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                            <p style="margin-top:-10px;">Koordinator Program Studi Teknik Elektro D3</p>
                        @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                            <p style="margin-top:-10px;">Koordinator Program Studi Teknik Elektro S1</p>
                        @else
                            <p style="margin-top:-10px;">Koordinator Program Studi Teknik Informatika</p>
                        @endif

                        <div class="ttd">
                            <img src="data:img/png;base64, {!! $qrcode !!}">
                        </div>
                        <br><br><br><br><br><br>
                        @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                            <strong
                                style="text-decoration: underline;">{{ $kaprodi1->nama }}</strong><br>NIP.{{ $kaprodi1->nip }}
                        @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                            <strong
                                style="text-decoration: underline;">{{ $kaprodi2->nama }}</strong><br>NIP.{{ $kaprodi2->nip }}
                        @else
                            <strong
                                style="text-decoration: underline;">{{ $kaprodi3->nama }}</strong><br>NIP.{{ $kaprodi3->nip }}
                        @endif

                    </div>
                    <br>
                </td>
                <td class="text" style="text-align: left;">
                    <div class="container" style="float: right; margin-top:-200px;">
                        <p>Pekanbaru, {{ Carbon::parse($pendaftaran_kp->tgl_created_usulankp)->translatedFormat('d F Y') }} </p>
                        <p style="margin-top:-10px;">Mahasiswa Ybs</p>
                        <div class="ttd">
                            <img src="data:img/png;base64, {!! $qrcode !!}">
                        </div>
                        <br><br><br><br><br><br>
                            <strong
                                style="text-decoration: underline;">{{ $pendaftaran_kp->mahasiswa->nama }}</strong><br>NIM.{{ $pendaftaran_kp->mahasiswa->nim }}
                    </div>
                    <br>
                </td>
            </tr>
        </table>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>

</body>

</html>
