<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Limbah</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        /* PDF-specific adjustments */
        @page {
            margin: 20mm 10mm;
            /* Adjust the margins for PDF */
        }

        /* Header Styles */
        .header {
            width: 100%;
            margin-bottom: 10px;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .header img {
            height: 80px;
        }

        .header .company-info {
            text-align: center;
        }

        .header .form-info table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }

        .header .form-info table td {
            padding: 2px 5px;
        }

        /* Table Styles */
        table {
            width: 100%;
            /* border-collapse: collapse; */
        }

        table th,
        table td {
            /* border: 1px solid #000; */
            padding: 5px;
            text-align: left;
            vertical-align: middle;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center
        }

        /* table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        } */

        /* Footer Styles */
        .footer {
            width: 100%;
            position: fixed;
            bottom: 0;
            text-align: center;
            font-size: 10px;
        }

        /* Avoid Flexbox/Grid (PDF issues) */
        .avoid-flex {
            display: block;
            text-align: left;
        }
    </style>

</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <table width="100%" style="border-collapse: collapse;">
                <tr>
                    <td style="width: 20%; text-align: center;">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/semen_padang.png'))) }}"
                            alt="" style="height: 80px;">
                    </td>
                    <td style="width: 60%; text-align: center;">
                        <div class="company-info">
                            <strong style="font-size: 14px;">TPS LIMBAH B3</strong><br>
                            <strong style="font-size: 14px;">PT. SEMEN PADANG</strong><br>
                            Jalan Raya Indarung, Padang 25237 Sumatera Barat<br>
                            Telp. (0751) 815-250 Fax. (0751) 815-590<br>
                            www.semenpadang.co.id
                        </div>
                    </td>
                    <td style="width: 20%; text-align: right; vertical-align: top;border:1px solid black;">
                        <table style="font-size: 12px; border-collapse: collapse;">
                            <tr style="border:1px solid black;">
                                <td>No. Formulir</td>
                                <td>:</td>
                                <td colspan="2">{{$aktivitas_limbah->no_form}}</td>
                            </tr>
                            <tr style="border:1px solid black;">
                                <td>Tgl. Terbit</td>
                                <td>:</td>
                                <td colspan="2">{{$aktivitas_limbah->tanggal}}</td>
                            </tr>
                            <tr style="border:1px solid black;">
                                <td>Revisi</td>
                                <td>:</td>
                                <td colspan="2">Rev. 0</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="container" style="font-size:13px;text-align:justify;font-weight:bold;">
            <p style="margin:0 0 5px 0;">Kepada Yth.</p>
            <p style="margin:0 0 5px 0;">Koordinator Pengumpul / Ka. Unit WHRPG dan Utilitas</p>
            <p style="margin:0 0 5px 0;">di Tempat</p>
        </div>

        <div class="container" style="text-align:center">
            <h2 style="margin:10px 0 0 0;"><u>SURAT PENGANTAR PENYERAHAN</u></h2>
            <p style="margin:0 0 10px 0;">Nomor: </p>
        </div>

        <div class="container" style="text-align:justify">

            <p style="margin:0 0 10px 0;">Dengan ini menyatakan, telah menyerahkan sejumlah <b>Limbah Bahan Berbahaya
                    dan
                    Beracun (B3)</b> dengan rincian sebagai berikut :</p>
        </div>
        {{-- <table>
            <tr>
                <th style="text-align: center">
                    <h2>PENGANTAR PENYERAHAN</h2>
                </th>
                <th style="text-align: start">Nomor: </th>
            </tr>
        </table> --}}

        <table style="border: 1px solid black;">
            {{-- <tr>
                <td colspan="7">Kepada Yth. <br>
                    Koordinator Pengumpul / Ka. Unit WHRPG dan Utilitas <br>
                    di Tempat</td>
            </tr> --}}

            <tr>
                <th>No</th>
                <th colspan="2">Jenis Limbah B3 Yang Diberikan</th>
                <th colspan="1">Kode</th>
                <th colspan="2">Banyak</th>
                <th colspan="2">Keterangan</th>
            </tr>
            @if ($akt=="masuk")
            @foreach ($aktivitas_masuk as $index=>$m)
            <tr>
                <td>{{$index+1}}</td>
                <td colspan="2">{{$m->klasifikasi_limbah->jenis_limbah}}</td>
                <td colspan="1" style="text-align:center">{{$m->klasifikasi_limbah->kode_limbah}}</td>
                <td colspan="2" style="text-align:center">{{$m->jml_masuk}}
                    {{$m->klasifikasi_limbah->satuan}}</td>
                <td colspan="2" style="text-align:center">{{$m->keterangan}}</td>
            </tr>
            @endforeach
            @else

            @foreach ($aktivitas_keluar as $index=>$k)
            <tr>
                {{$k->tgl_keluar}}
            </tr>
            {{-- <tr>
                <td>{{$index+1}}</td>
                <td colspan="2">{{$k->klasifikasi_limbah->jenis_limbah}}</td>

                <td colspan="1" style="text-align:center">{{$k->klasifikasi_limbah->kode_limbah}}</td>
                <td colspan="2" style="text-align:center">{{$k->jml_keluar}}
                    {{$k->klasifikasi_limbah->satuan}}</td>
                <td colspan="2" style="text-align:center">{{$k->keterangan}}</td>
            </tr> --}}
            @endforeach
            @endif

        </table>

        <!-- Penanggung Jawab Kegiatan -->
        <table style="border: 1px solid white;">
            <tr style="border: 1px solid white;">
                <td colspan="6" style="text-align:right;"><b>Padang, {{
                        \Carbon\Carbon::parse($aktivitas_limbah->tanggal)->translatedFormat('d F Y') }}</b></td>
            </tr>
            <tr style="border: 1px solid white;">
                <td colspan="3" rowspan="3"></td>
                <th colspan="3">Mengetahui</th>
            </tr>
            <tr style="border: 1px solid white;" style="text-align: center;font-weight:bold">

                <td colspan="3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Setujui Penyerahan Limbah
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Setujui Penyerahan Limbah</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Saya telah membaca detail penyerahan limbah dan menyatakan telah menyetujui
                                    penyerahan limbah B3 Unit kepada TPS Limbah B3 PT Semen Padang
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <a href="/setujui_penyerahan" type="button" class="btn btn-primary">Setujui</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr style="border: 1px solid white;">

                <th colspan="3">Ka.Unit {{$aktivitas_limbah->unit}}</th>
            </tr>
            <tr style="border: 1px solid white;">
                <td colspan="6" style="text-align:start;">Diterima pada : {{
                    \Carbon\Carbon::parse($aktivitas_limbah->tanggal)->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr style="border: 1px solid white;">
                <th colspan="3">Yang Menerima</th>
                <th colspan="3">Yang Menyerahkan</th>
            </tr>

            <tr style="text-align: center;font-weight:bold;style;border: 1px solid white;">
                <td colspan=" 3" style="text-align:center;"><br><img src="{{ $aktivitas_limbah->signature_pengumpul }}"
                        alt="Tanda Tangan Pengumpul"
                        style="width: 200px; height: auto;"><br>{{$aktivitas_limbah->pengumpul}}</td>
                <td colspan="3" style="text-align:center;"><br><img src="{{ $aktivitas_limbah->signature_penghasil }}"
                        alt="Tanda Tangan Penghasil"
                        style="width: 200px; height: auto;"><br>{{$aktivitas_limbah->penghasil}}</td>
            </tr>
            <tr style="border: 1px solid white;">
                <th colspan="3">Personil Koordinator Pengumpul</th>
                <th colspan="3">Personil Unit Penghasil</th>
            </tr>
            <tr style="border: 1px solid white;">
                <td colspan="6">Tembusan : 1. Ka. Unit SHE</td>
            </tr>
        </table>

    </div>
</body>

</html>