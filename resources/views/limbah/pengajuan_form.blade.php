<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Limbah</title>
    <link rel="icon" href="{{asset('img/semen_padang.png')}}" type="image/x-icon" />
    <style>
        /* PDF-specific adjustments */
        @page {
            margin: 20mm 10mm;
            /* Adjust the margins for PDF */
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</head>

<body>
    <b>* Note : </b> Jika diakses melalui perangkat mobile harap untuk menggunakan tampilan dekstop.
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
                </tr>
            </table>
        </div>

        <div class="container" style="font-size:13px;text-align:justify;font-weight:bold;">
            <p style="margin:0 0 5px 0;">Kepada Yth.</p>
            <p style="margin:0 0 5px 0;">Koordinator Pengumpul / Ka. Unit WHRPG dan Utilitas</p>
            <p style="margin:0 0 5px 0;">di Tempat</p>
        </div>

        <div class="container" style="text-align:center">
            <h5 style="margin:10px 0 0 0;"><u>SURAT PENGANTAR PENYERAHAN</u></h5>
            <p style="margin:0 0 10px 0;">Nomor: {{$aktivitas_limbah->no_form}}</p>
        </div>

        <div class="container" style="text-align:justify">

            <p style="margin:0 0 10px 0;">Dengan ini menyatakan, telah menyerahkan sejumlah <b>Limbah Bahan Berbahaya
                    dan
                    Beracun (B3)</b> dengan rincian sebagai berikut :</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Klasifikasi Limbah</th>
                    <th>Jumlah Masuk (Satuan)</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($aktivitas_masuk as $index=>$l)
                <tr>
                    <td>{{$l->klasifikasi_limbah->jenis_limbah}}</td>
                    <td>{{$l->jml_masuk}} Kg</td>
                    <td>{{$l->keterangan}}</td>
                </tr>
                @endforeach

            </tbody>
        </table>


        <!-- Penanggung Jawab Kegiatan -->
        <table>
            <tr>
                <td colspan="6" style="text-align:right;"><b>Padang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y')
                        }}</b></td>
            </tr>
            <tr style="text-align:center;">
                <td colspan="3" rowspan="3"></td>
                <th colspan="3">Mengetahui</th>
            </tr>
            <tr style="text-align: center;font-weight:bold">

                <td colspan="3" style="text-align: center;">
                    <br><br><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">
                        Approve Pengajuan
                    </button>
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Approve pengajuan Limbah B3 Unit kepada TPS Limbah B3 PT Semen Padang?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <a href="/validasi_pengajuan/{{$aktivitas_limbah->id}}" type="button"
                                        class="btn btn-primary">Setuju</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>{{$user->penanggung_jawab}}
                </td>

            </tr>
            <tr>
                <th colspan="3">Ka.Unit {{$user->unit->nama_unit}}</th>
            </tr>
            <tr>
                <td colspan="6" style="text-align:start;">Diterima Tanggal : </td>
            </tr>
            <tr>
                <th colspan="3">Yang Menerima</th>
                <th colspan="3">Yang Menyerahkan</th>
            </tr>

            <tr style="text-align: center;font-weight:bold">
                <td colspan="3" style="text-align:center;">
                    <br><br>
                </td>
                <td colspan="3" style="text-align:center;">
                    <br>
                    <img style="width:60px;" src="data:image/png;base64,{{$qrcode_menyerahkan}}"
                        alt="QR Code Menyerahkan">
                    <br>
                    <br>
                    <p>{{$aktivitas_limbah->menyerahkan}}</p>
                </td>
            </tr>
            <tr>
                <th colspan="3">Personil Koordinator Pengumpul</th>
                <th colspan="3">Personil Unit Penghasil</th>
            </tr>
            <tr>
                <td colspan="6">Tembusan : 1. Ka. Unit SHE</td>
            </tr>
        </table>

    </div>
</body>

</html>
