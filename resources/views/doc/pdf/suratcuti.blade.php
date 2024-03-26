<!DOCTYPE html>
<html>
@php
    use Carbon\Carbon;
    function nipFormat($inputString)
    {
        $length1 = 8;
        $length2 = 6;
        $length3 = 1;
        $length4 = 3;

        if (strlen($inputString) >= $length1 + $length2 + $length3 + $length4) {
            $part1 = substr($inputString, 0, $length1);
            $part2 = substr($inputString, $length1, $length2);
            $part3 = substr($inputString, $length1 + $length2, $length3);
            $part4 = substr($inputString, $length1 + $length2 + $length3, $length4);

            $outputString = $part1 . ' ' . $part2 . ' ' . $part3 . ' ' . $part4;
            return $outputString;
        } else {
            return $inputString;
        }
    }
@endphp

<head>
    <style type='text/css'>
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif
        }

        @page {
            size: A4 potrait;
            padding: 0;
        }

        .container {
            position: relative;
            margin-top: 1.5cm;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
            font-size: 12px;
        }

        table {
            margin-left: 1.5cm;
            width: 93%;
        }

        th,
        td {
            padding: 2px 4px;
        }

        .label {
            width: 64px;
        }

        .check {
            width: 16px;
            position: relative
        }

        th {
            font-weight: bold;
            font-size: 12px;
            text-align: left;
        }

        .section-1 {
            position: absolute;
            right: 0;
            width: max-content;
            font-size: 14px;
            margin-right: 1.5cm;
        }

        .title {
            font-size: 14px;
            font-weight: 700;
            margin-top: 108px;
            margin-bottom: 12px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="section-1">
            <div>Pekanbaru, {{ Carbon::parse($data->created_at)->translatedFormat('d F Y') }}</div>
            <p>Kepada Yth. <br>
                {{ $data->jabatan_penandatangan_akhir }}, <br>
                Fakultas Teknik Universitas Riau
            </p>
        </div>
        <center>
            <div class="title">FORMULIR PERMINTAAN CUTI DAN PEMBERIAN CUTI</div>
        </center>
        <div style="padding-right: 1.5cm">
            <table>
                <tr>
                    <th colspan="4">I. DATA PEGAWAI</th>
                </tr>
                <tr>
                    <td class="label">Nama</td>
                    <td>{{ data_get($data, $data->jenis_user . '.nama') }}</td>
                    <td class="label">NIP</td>
                    <td>{{ $data->jenis_user == 'dosen' ? nipFormat($data->dosen->nip) : '' }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td>{{ data_get($data, $data->jenis_user . '.role.role_akses') ?? 'Dosen ' . $data->dosen->prodi->nama_prodi }}
                    </td>
                    <td class="label">Masa Kerja</td>
                    <td>{{ $masa_kerja }}</td>
                </tr>
                <tr>
                    <td class="label">Unit Kerja</td>
                    <td colspan="3">Jurusan Teknik Elektro</td>
                </tr>
            </table>
            <table style="margin-top: -11px;">
                <tr>
                    <th colspan="4">II. JENIS CUTI YANG DIAMBIL</th>
                </tr>
                <tr>
                    <td style="width:40%">1. Cuti Tahunan</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'tahunan')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                    <td style="width:40%">4. Cuti Melahirkan</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'melahirkan')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="width:40%">2. Cuti Besar</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'besar')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                    <td style="width:40%">5. Cuti Karena Alasan Penting</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'kepentingan')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="width:40%">3. Cuti Sakit</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'sakit')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                    <td style="width:40%">6. Cuti Diluar Tanggungan Negara</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'diluar tanggungan negara')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th colspan="4">III. ALASAN CUTI</th>
                </tr>
                <tr>
                    <td colspan="4" style="height: 36px;vertical-align: top;">{{ $data->alasan_cuti }}</td>
                </tr>
            </table>
            <table style="margin-top: -11px;">
                <tr>
                    <th colspan="4">IV. LAMANYA CUTI</th>
                </tr>
                <tr>
                    <td style="width: 48px">Selama</td>
                    <td>{{ $data->lama_cuti }} Hari</td>
                    <td style="width: 96px">Mulai Tanggal</td>
                    <td style="text-align: center">{{ Carbon::parse($data->mulai_cuti)->translatedFormat('d F Y') }}
                        <span style="margin: 0 8px">s.d.</span>
                        {{ Carbon::parse($data->selesai_cuti)->translatedFormat('d F Y') }}
                    </td>
                </tr>
            </table>
            <table style="margin-top: -11px;">
                <tr>
                    <th colspan="6">V. CATATAN CUTI</th>
                </tr>
                <tr>
                    <td colspan="3">1. CUTI TAHUNAN</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'tahunan')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                    <td>2. CUTI BESAR</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'besar')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-center">Tahun</td>
                    <td class="text-center">Sisa</td>
                    <td class="text-center" colspan="2">Katerangan</td>
                    <td>3. CUTI SAKIT</td>
                    <td class="check"></td>
                </tr>
                <tr>
                    <td>N</td>
                    <td></td>
                    <td colspan="2"></td>
                    <td>4. CUTI MELAHIRKAN</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'melahirkan')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>N-1</td>
                    <td></td>
                    <td colspan="2"></td>
                    <td>5. CUTI KARENA ALASAN PENTING</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'kepentingan')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>N-2</td>
                    <td></td>
                    <td colspan="2"></td>
                    <td>6. CUTI DILUAR TANGGUNAN NEGARA</td>
                    <td class="check">
                        @if ($data->jenis_cuti == 'diluar tanggungan negara')
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/icon/check.svg'))) }}"
                                width="16px" style="position: absolute;top:0; left:2px;">
                        @endif
                    </td>
                </tr>
            </table>
            <table style="margin-top: -11px;">
                <tr>
                    <th colspan="2">VI. ALAMAT SELAMA MENJALANKAN CUTI</th>
                </tr>
                <tr>
                    <td style="width:50%;vertical-align: center">
                        {{ $data->alamat_cuti }}
                    </td>
                    <td style="width:50%;padding: 0">
                        <div style="border-bottom: 1px solid black;padding: 0 4px">
                            <span style="border-right: 1px solid black; width: 56px;padding: 2px 0">
                                TELP/HP
                            </span>
                            <span>{{ $data->nomor_telepon }}</span>
                        </div>
                        <div style="padding: 0 36px; padding-bottom: 2px; padding-top: 4px">
                            <div class="text-center">Hormat Saya,</div>
                            <div style="height: 64px;text-align: center">
                                <img width="98"
                                    src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('storage/' . $data->tanda_tangan))) }}">
                            </div>
                            <div class="text-center">{{ data_get($data, $data->jenis_user . '.nama') }}</div>
                            <div style="border-bottom: 1px solid black"></div>
                            <div class="text-center" style="height: 16px">
                                {{ $data->jenis_user == 'dosen' ? 'NIP ' . nipFormat($data->dosen->nip) : '' }}
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <table style="margin-top: -11px;">
                <tr>
                    <th colspan="4">VII. PERTIMBANGAN ATASAN LANGSUNG</th>
                </tr>
                <tr>
                    <td class="text-center" style="width:25%">DISETUJUI</td>
                    <td class="text-center" style="width:25%">PERUBAHAN</td>
                    <td class="text-center" style="width:25%">DITANGGUHKAN</td>
                    <td class="text-center" style="width:25%">TIDAK DISETUJUI</td>
                </tr>
                <tr>
                    <td style="width:25%"></td>
                    <td style="width:25%"></td>
                    <td style="width:25%" class="text-center">-</td>
                    <td style="width:25%" class="text-center">-</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2">
                        <div style="padding: 0 36px; padding-bottom: 2px; padding-top: 4px">
                            <div style="height: 64px"></div>
                            <div class="text-center">{{ $kajur->nama }}</div>
                            <div style="border-bottom: 1px solid black"></div>
                            <div class="text-center">NIP {{ nipFormat($kajur->nip) }}</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th colspan="4">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</th>
                </tr>
                <tr>
                    <td class="text-center" style="width:25%">DISETUJUI</td>
                    <td class="text-center" style="width:25%">PERUBAHAN</td>
                    <td class="text-center" style="width:25%">DITANGGUHKAN</td>
                    <td class="text-center" style="width:25%">TIDAK DISETUJUI</td>
                </tr>
                <tr>
                    <td style="width:25%; height: 16px"></td>
                    <td style="width:25%; height: 16px"></td>
                    <td style="width:25%; height: 16px"></td>
                    <td style="width:25%; height: 16px"></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2">
                        <div style="padding: 0 36px; padding-bottom: 2px; padding-top: 4px">
                            <div style="height: 64px"></div>
                            <div class="text-center">{{ $data->nama_penandatangan_akhir }}</div>
                            <div style="border-bottom: 1px solid black"></div>
                            <div class="text-center">NIP {{ $data->nip_penandatangan_akhir }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
