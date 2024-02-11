<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

<head>
    <title>KPTI/TE-2 Surat Permohonan Kerja Praktek</title>
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

        <table width="100%" style="margin-top: -25px">
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
        </table>
            
            <table width="600px" style="text-align:center; margin-top : -10px;">
                <tr>
                    <td>
                        <hr style="margin: 1px; border: 2px solid black">
                        <hr style="margin: 1px; border: 1px solid black">
                    </td>
                </tr>
            </table>
        </table>

        <table width="100%" style="text-align:center; margin-top: -5px;">
            <tr>
                <td style="font-size:12pt;text-decoration: underline;">
                    <strong>FORM PERMOHONAN KP</strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="text-align: right; margin-top:-40px;">
            <tr>
                <td style="font-size:12pt;">
                    @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                        <strong style="border:1px solid #000; padding:4px">KPTE-2</strong>
                    @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                        <strong style="border:1px solid #000; padding:4px">KPTE-2</strong>
                    @else
                        <strong style="border:1px solid #000; padding:4px">KPTI-2</strong>
                    @endif
                </td>
            </tr>
        </table>

        <div style="margin: 1px; border: 1px solid black; padding: 1px; padding-bottom: 75px;">
        <table width="100%" style="font-family: Arial, sans-serif; margin-top:0px; line-height: 1.5;  border-collapse: collapse; border: none;">
            <tr class="text2">
                <td width="35%"><strong>A. Diisi oleh mahasiswa</strong></td>
                <td width="30%"><strong>Rencana KP :</strong> {{ Carbon::parse($pendaftaran_kp->tanggal_mulai)->translatedFormat('d F Y') }}</td>
                <td width="15%"><strong>s.d</strong></td>
            </tr>
        </table>
        <hr style="margin-top: -5px; border: 0.5px solid black">

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:-10px; line-height: 1;  border-collapse: collapse; border: none;">
            <tr class="text2">
                <td width="27%">Yang bertanda tangan di bawah ini :</td>
            </tr>
        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:0px; line-height: 1.15;  border-collapse: collapse; border: none;">
            <tr class="text2">
                <td style="padding-left: 50px;">Nama</td>
                <td>:</td>
                <td width="80%">{{ $pendaftaran_kp->mahasiswa->nama }}</td>
            </tr>
            <tr>
                <td style="padding-left: 50px;">NIM</td>
                <td>:</td>
                <td width="80%">{{ $pendaftaran_kp->mahasiswa->nim }}</td>
            </tr>
        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:-5px; line-height: 1.5;  border-collapse: collapse; border: none;">
            <tr class="text2">
                <td width="27%">Mengajukan Kerja Praktek bidang {{ $pendaftaran_kp->mahasiswa->konsentrasi->nama_konsentrasi }}.</td>
            </tr>
            <tr class="text2" style="margin-top:-10px; line-height: 1;">
                <td >Sebagai bahan pertimbangan, Saya lampirkan 
                    @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                        <strong>KPTE-1</strong>
                    @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                        <strong>KPTE-1</strong>
                    @else
                        <strong>KPTI-1</strong>
                    @endif
                    <strong>yang telah disetujui oleh </strong> 
                    @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                            <strong>Koor Prodi TE D3.</strong>
                        @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                            <strong>Koor Prodi TE S1.</strong>
                        @else
                            <strong>Koor Prodi TI S1.</strong>
                    @endif
                </td>
            </tr>

        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:0px; line-height: 1.15;  border-collapse: collapse; border: none;">
            <tr class="text2">
                <td width="27%">Apabila permohonan ini diizinkan, saya mengusulkan perusahaan berikut agar dapat disetujui sebagai tempat pelaksanaan Kerja Praktek tersebut.</td>
            </tr>
        </table>

        <table width="100%" style="font-family: Arial, sans-serif; margin-top:0px; line-height: 1.15;  border-collapse: collapse; border: none;">
            <tr class="text2">
                <td width="50%" style="padding-left: 50px;">Nama Perusahaan/Instansi</td>
                <td width="5%">:</td>
                <td width="27%">{{ $pendaftaran_kp->nama_perusahaan }}</td>
            </tr>
            <tr>
                <td style="padding-left: 50px;">Alamat</td>
                <td>:</td>
                <td width="70%">{{ $pendaftaran_kp->alamat_perusahaan }}</td>
            </tr>
            <tr>
                <td style="padding-left: 50px;">Bidang usaha/kegiatan</td>
                <td>:</td>
                <td width="70%">{{ $pendaftaran_kp->bidang_usaha }}</td>
            </tr>
        </table>

        <div style="width: 100%;">
            <table style="float: right; font-family: Arial, sans-serif; margin-top: 0px;  border-collapse: collapse; border: none;">
                <tr>
                    <td class="text" style="text-align: left;">
                        <div class="container">
                            <p style="margin-top: 4px;">Pekanbaru, {{ Carbon::parse($pendaftaran_kp->tgl_created_usulankp)->translatedFormat('d F Y') }} </p>
                            <p style="margin-top: -14px;">Pemohon,</p>
                            <div class="ttd" style="margin-top: -10px;">
                                <img width="55px" height="55px" src="data:img/png;base64, {!! $qrcode !!}">
                            </div>
                            <br><br><br>
                            <strong style="text-decoration: underline; ">{{ $pendaftaran_kp->mahasiswa->nama }}</strong><br>NIM.{{ $pendaftaran_kp->mahasiswa->nim }}
                        </div>
                        <br>
                    </td>
                </tr>
            </table>
        </div>

<div style="padding-top:115px;">
<hr style="margin-bottom: -5px; border: 0.5px solid black">
        <table width="100%" style="font-family: Arial, sans-serif; line-height: 1.5;  border-collapse: collapse; border: none;">
            <tr class="text2">
                <td><strong >B. Diisi oleh Calon Dosen Pembimbing KP</strong></td>
            </tr>
        </table>
        <hr style="margin-top: -5px; border: 0.5px solid black">
        <table width="100%" style="font-family: Arial, sans-serif;  border-collapse: collapse; border: none;">
            <tr>
                <td class="text" style="text-align: left;">
                    <div class="container">
                        <p style="margin-top: -10px;">Catatan :</p>
                    <br>
                </td>
                <div style="width: 100%; margin-top: -30px;">
                    <table style="float: right; font-family: Arial, sans-serif;  border-collapse: collapse; border: none;">
                        <tr>
                            <td class="text" style="text-align: left;">
                                <div class="container">
                                    <p style="margin-top: 10px;">Tanda Tangan</p>
                                    <p style="margin-top: -15px;">Dosen Pembimbing KP,</p>
                                    <div class="ttd" style="margin-top: -10px;">
                                        <img width="55px" height="55px" src="data:img/png;base64, {!! $qrcode !!}">
                                    </div>
                                    <br><br><br>
                                    <strong style="text-decoration: underline; ">{{ $pendaftaran_kp->dosen_pembimbingkp->nama }}</strong><br>NIP.{{ $pendaftaran_kp->dosen_pembimbing_nip }}
                                </div>
                                <br>
                            </td>
                        </tr>
                    </table>
                </div>
            </tr>
        </table>
</div>
<div style="padding-top:50px;">
<hr style="margin-bottom: -5px; border: 0.5px solid black">
        <table width="100%" style="font-family: Arial, sans-serif; line-height: 1.5;  border-collapse: collapse; border: none;">
            <tr class="text2">
                <td width="27%"><strong>C. Disetujui oleh Koordinator KP</strong></td>
            </tr>
        </table>
        <hr style="margin-top: -5px; border: 0.5px solid black">
        
        <table width="100%" style="font-family: Arial, sans-serif;   border-collapse: collapse; border: none;">
            <tr>
                <td class="text" style="text-align: left;">
                        <p style="margin-top: -10px;">Catatan :</p>
                    <br>
                </td>
                <div style="width: 100%; " >
                    <table style="float: right; font-family: Arial, sans-serif;  border-collapse: collapse; border: none;">
                        <tr>
                            <td class="text" style="text-align: left;">
                                <div class="container" >
                                    @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                                            <p style="margin-top: -15px;">Tanda Tangan Koor. Skripsi dan KP <br> Teknik Elektro D3</p>
                                        @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                                            <p style="margin-top: -15px;">Tanda Tangan Koor. Skripsi dan KP <br> Teknik Elektro S1</p>
                                        @else
                                            <p style="margin-top: -15px;">Tanda Tangan Koor. Skripsi dan KP <br> Teknik Informatika S1</p>
                                    @endif
                                    <div class="ttd" style="margin-top: -10px;">
                                        <img width="55px" height="55px" src="data:img/png;base64, {!! $qrcode !!}">
                                    </div>
                                    <br><br><br>
                                    @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                                    <strong
                                        style="text-decoration: underline;">{{ $koor1->nama }}</strong><br>NIP.{{ $koor1->nip }}
                                    @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                                        <strong
                                            style="text-decoration: underline;">{{ $koor2->nama }}</strong><br>NIP.{{ $koor2->nip }}
                                    @else
                                        <strong
                                            style="text-decoration: underline;">{{ $koor3->nama }}</strong><br>NIP.{{ $koor3->nip }}
                                    @endif
                                </div>
                                <br>
                            </td>
                        </tr>
                    </table>
                </div>
                
            </tr>
        </table>
    </div>
        </div>

        <div class="container" style="float: right; font-family: Arial, sans-serif; padding-top: -100px; font-size: 13px;">

        <p style="margin-top: 2px;">Mengetahui,</p>
                            @if ($pendaftaran_kp->mahasiswa->prodi->id == 1)
                                <p style="margin-top: -10px;">Koordinator Program Studi <br> Teknik Elektro D3</p>
                            @elseif ($pendaftaran_kp->mahasiswa->prodi->id == 2)
                                <p style="margin-top: -10px;">Koordinator Program Studi <br> Teknik Elektro S1</p>
                            @else
                                <p style="margin-top: -10px;">Koordinator Program Studi <br> Teknik Informatika S1</p>
                            @endif
    
                            <div class="ttd" style="margin-top: -10px;">
                                <img width="55px" height="55px" src="data:img/png;base64, {!! $qrcode !!}">
                            </div>
                            <br><br><br>
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


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>

</body>

</html>
