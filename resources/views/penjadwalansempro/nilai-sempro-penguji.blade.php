<!DOCTYPE html>
<html>

<head>
    <title>STI-5 Form Nilai Penguji Seminar Proposal Skripsi</title>
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

        .tablesti5 {
            margin-top: 30px;
        }

        .tablesti5_1 {
            margin-top: -13px;
            margin-left: 5%;
        }

        .tablesti5_2 {
            margin-top: -15px;
            margin-left: 15%;
        }

        .table2 {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #232323;
            border-collapse: collapse;
            border: 1px solid #999;
            padding: 3px 10px;
            margin-top: 30px;
            margin-left: 70px;
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
                        @if ($penjadwalan->mahasiswa->prodi->id == 1)
                            <font size="3"><b>PROGRAM STUDI TEKNIK ELEKTRO D3</b></font><br>
                        @elseif ($penjadwalan->mahasiswa->prodi->id == 2)
                            <font size="3"><b>PROGRAM STUDI TEKNIK ELEKTRO S1</b></font><br>
                        @else
                            <font size="3"><b>PROGRAM STUDI TEKNIK INFORMATIKA</b></font><br>
                        @endif
                        <font size="2">Kampus Bina Widya Km. 12,5 Simpang Baru Pekanbaru 28293</font><br>
                        <font size="2">Telepon (0761) 66596 Faksimile (0761) 66595</font><br>
                        @if ($penjadwalan->mahasiswa->prodi->id == 1)
                            <font size="2">Laman: <u>http://elektrod3.ft.unri.ac.id</u></font>
                        @elseif ($penjadwalan->mahasiswa->prodi->id == 2)
                            <font size="2">Laman: <u>http://elektros1.ft.unri.ac.id</u></font>
                        @else
                            <font size="2">Laman: <u>http://informatika.ft.unri.ac.id</u></font>
                        @endif
                    </center>
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
                    <strong>FORM NILAI PENGUJI SEMINAR PROPOSAL SKRIPSI</strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="text-align: right; margin-top:-40px;">
            <tr>
                <td style="font-size:12pt;">
                    @if ($penjadwalan->mahasiswa->prodi->id == 1)
                        <strong style="border:1px solid #000; padding:4px">STE-5</strong>
                    @elseif ($penjadwalan->mahasiswa->prodi->id == 2)
                        <strong style="border:1px solid #000; padding:4px">STE-5</strong>
                    @else
                        <strong style="border:1px solid #000; padding:4px">STI-5</strong>
                    @endif
                </td>
            </tr>

        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:20px; line-height: 1.5">
            <tr class="text2">
                <td>Nama Mahasiswa</td>
                <td>:</td>
                <td width="70%">{{ $penjadwalan->mahasiswa->nama }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td width="70%">{{ $penjadwalan->mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td>Judul Proposal</td>
                <td>:</td>
                <td width="70%">
                    {{ $penjadwalan->revisi_proposal != null ? $penjadwalan->revisi_proposal : $penjadwalan->judul_proposal }}
                </td>
            </tr>
            <tr>
                <td>Tanggal Seminar</td>
                <td>:</td>
                <td width="70%">{{ Carbon::parse($penjadwalan->tanggal)->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td width="27%">Dosen Pembimbing</td>
                <td>:</td>
                <td width="70%">1. {{ $penjadwalan->pembimbingsatu->nama ?? '-' }}</td>
            </tr>
        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:-10px; margin-left:31%;">
            <tr>
                @if ($penjadwalan->pembimbingdua_nip != null)
                    <td width="70%">2. {{ $penjadwalan->pembimbingdua->nama }}</td>
                @else
                    <td width="70%">2. -</td>
                @endif
            </tr>
        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:2px;">
            <tr>
                <td width="27%">Dosen Penguji ( @if ($penjadwalan->pengujisatu_nip == $penilaianpenguji->penguji->nip)
                        1
                    @elseif ($penjadwalan->pengujidua_nip == $penilaianpenguji->penguji->nip)
                        2
                    @elseif ($penjadwalan->pengujitiga_nip == $penilaianpenguji->penguji->nip)
                        3
                    @endif)</td>
                <td>:</td>
                <td width="70%">{{ $penilaianpenguji->penguji->nama }}</td>
            </tr>
        </table>

        <table class="tablesti5" width="100%"
            style="margin-top:-10px; font-family: Arial, sans-serif; line-height: 1.5">
            <tr>
                <td>Aspek Penilaian</td>
            </tr>

            <tr class="text3">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. &nbsp;&nbsp;&nbsp;Presentasi (5)</td>
                <td>:</td>
                <td>{{ $penilaianpenguji->presentasi }}</td>
            </tr>

            <tr class="text2">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. &nbsp;&nbsp;&nbsp;Tingkat Penguasaan
                    Materi (8)</td>
                <td>:</td>
                <td>{{ $penilaianpenguji->tingkat_penguasaan_materi }}</td>
            </tr>

            <tr class="text2">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. &nbsp;&nbsp;&nbsp;Keaslian (5)</td>
                <td>:</td>
                <td>{{ $penilaianpenguji->keaslian }}</td>
            </tr>

            <tr class="text2">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. &nbsp;&nbsp;&nbsp;Ketepatan
                    Metodologi (7)</td>
                <td>:</td>
                <td>{{ $penilaianpenguji->ketepatan_metodologi }}</td>
            </tr>

            <tr class="text2">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. &nbsp;&nbsp;&nbsp;Penguasaan Dasar
                    Teori (6)</td>
                <td>:</td>
                <td>{{ $penilaianpenguji->penguasaan_dasar_teori }}</td>
            </tr>

            <tr class="text2">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6. &nbsp;&nbsp;&nbsp;Kecermatan
                    Perumusan Masalah (6)</td>
                <td>:</td>
                <td>{{ $penilaianpenguji->kecermatan_perumusan_masalah }}</td>
            </tr>

            <tr class="text2">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7. &nbsp;&nbsp;&nbsp;Tinjauan Pustaka
                    (7)</td>
                <td>:</td>
                <td>{{ $penilaianpenguji->tinjauan_pustaka }}</td>
            </tr>

            <tr class="text2">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8. &nbsp;&nbsp;&nbsp;Tata Tulis (5)</td>
                <td>:</td>
                <td>{{ $penilaianpenguji->tata_tulis }}</td>
            </tr>

            <tr class="text2">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9. &nbsp;&nbsp;&nbsp;Sumbangan Pemikiran
                    Terhadap Ilmu Pengetahuan Dan Terapannya (6)</td>
                <td>:</td>
                <td>{{ $penilaianpenguji->sumbangan_pemikiran }}</td>
            </tr>
        </table>

        <table class="tablesti5_1" width="90%">
            <tr>
                <td>
                    <hr style="margin: 1px; width:600px; border: 1px solid black">
                </td>
                <td>&#43;</td>
            </tr>
        </table>

        <table class="tablesti5_2" width="93.5%" style="font-family: Arial, sans-serif; line-height: 1.5">
            <tr>
                <td><b>Total Nilai Penguji (maks: 55%)</b></td>
                <td>: &nbsp; {{ $penilaianpenguji->total_nilai_angka }}</td>
            </tr>
        </table>

        <!--<table class="table2" style="font-family: Arial, sans-serif; text-align:center; margin-left:0px;">-->
        <!--    <tr>-->
        <!--        <th class="table2">Nilai Angka</th>-->
        <!--        <th class="table2">Nilai Mutu</th>-->
        <!--        <th class="table2">Angka Mutu</th>-->
        <!--        <th class="table2">Sebutan Mutu</th>-->
        <!--    </tr>-->
        <!--    <tr>-->
        <!--        <td class="table2">47 - 55 </td>  -->
        <!--        <td class="table2">A</td>                -->
        <!--        <td class="table2">4.00</td>-->
        <!--        <td class="table2">Sangat Baik</td>                        -->
        <!--    </tr>-->

        <!--    <tr>-->
        <!--        <td class="table2">44 - 46</td>  -->
        <!--        <td class="table2">A -</td>                -->
        <!--        <td class="table2">3.75</td>                -->
        <!--        <td class="table2">Sangat Baik</td>                        -->
        <!--    </tr>-->

        <!--    <tr>-->
        <!--        <td class="table2">42 - 43</td>  -->
        <!--        <td class="table2">B +</td>                -->
        <!--        <td class="table2">3.50</td>                -->
        <!--        <td class="table2">Baik</td>                        -->
        <!--    </tr>  -->

        <!--    <tr>-->
        <!--        <td class="table2">39 - 41</td>  -->
        <!--        <td class="table2">B</td>                -->
        <!--        <td class="table2">3.00</td>                -->
        <!--        <td class="table2">Baik</td>                        -->
        <!--    </tr>  -->

        <!--    <tr>-->
        <!--        <td class="table2">36 - 38</td>  -->
        <!--        <td class="table2">B -</td>                -->
        <!--        <td class="table2">2.75</td>                -->
        <!--        <td class="table2">Cukup</td>                        -->
        <!--    </tr>  -->

        <!--    <tr>-->
        <!--        <td class="table2">33 - 35</td>  -->
        <!--        <td class="table2">C +</td>                -->
        <!--        <td class="table2">2.50</td>                -->
        <!--        <td class="table2">Cukup</td>                        -->
        <!--    </tr>  -->

        <!--    <tr>-->
        <!--        <td class="table2">31 - 32</td>  -->
        <!--        <td class="table2">C</td>                -->
        <!--        <td class="table2">2.00</td>                -->
        <!--        <td class="table2">Cukup</td>                        -->
        <!--    </tr>  -->

        <!--    <tr>-->
        <!--        <td class="table2">22 - 30</td>  -->
        <!--        <td class="table2">D</td>                -->
        <!--        <td class="table2">1.00</td>                -->
        <!--        <td class="table2">Kurang</td>                        -->
        <!--    </tr>  -->

        <!--    <tr>-->
        <!--        <td class="table2">0 - 21</td>  -->
        <!--        <td class="table2">E</td>                -->
        <!--        <td class="table2">0.00</td>                -->
        <!--        <td class="table2">Gagal</td>                        -->
        <!--    </tr>  -->
        <!--</table>-->

        <table width="100%" style="font-family: Arial, sans-serif; position: absolute; margin-top: 100px">
            <tr>
                <td width="55%" align="right">
                    <!-- Disini untuk perintah Qr code -->
                </td>
                <td class="text" style="text-align: left;">
                    <div class="container">
                        <p>Pekanbaru, {{ Carbon::parse($penjadwalan->tanggal)->translatedFormat('d F Y') }} </p>
                        <p>Dosen Penguji Seminar Proposal</p>
                        <div class="ttd">
                            <img src="data:img/png;base64, {!! $qrcode !!}">
                        </div>
                        <br><br><br><br><br><br>
                        <strong
                            style="text-decoration: underline;">{{ $penilaianpenguji->penguji->nama }}</strong><br>NIP.
                        {{ $penilaianpenguji->penguji->nip }}
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
