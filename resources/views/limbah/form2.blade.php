<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penyerahan Limbah</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background: url('{{ public_path(' template.jpg') }}') no-repeat center center;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 50px;
        }

        th,
        td {
            border: 1px solid #000;
            text-align: center;
            padding: 8px;
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
            border-collapse: collapse;
        }

        table th,
        table td {

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

        .left-image {
            position: absolute;
            top: 10px;
            /* Jarak dari atas */
            left: 10px;
            /* Jarak dari kiri */
            width: 80px;
            /* Atur ukuran gambar */
        }

        .right-image {
            position: absolute;
            top: 10px;
            /* Jarak dari atas */
            right: 10px;
            /* Jarak dari kanan */
            width: 80px;
            /* Atur ukuran gambar */
        }
    </style>

</head>

<body>
    <div class="container">
        <img class="left-image"
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo_sig.png'))) }}" alt=""
            style="height: 80px;width:80px;">
        <img class="right-image"
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/semen_padang.png'))) }}"
            alt="" style="height: 80px;">
    </div>
    <div class="container">
        <!-- Header Section -->
        <div class="header" style="text-align: center">

            <strong style="font-size: 14px;"><b>BERITA ACARA PENYERAHAN LIMBAH B3</b></strong><br>
            <strong style="font-size: 14px;">DARI PT SEMEN PADANG</strong><br>
            <strong style="font-size: 14px;">KE {{strtoupper($aktivitas_limbah->tujuan)}}</strong><br>
            <p>No.
                00{{strtoupper($aktivitas_limbah->no)}}/KK.02.03/BA/50056520/3000/{{date('m')}}.{{date('Y')}}
            </p><br>

        </div>
        <div class="container" style="text-align:justify">

            <p style="margin:0 0 10px 0;">
                Pada hari ini {{ \Carbon\Carbon::parse($aktivitas_limbah->tgl_keluar)->translatedFormat('l, d F Y') }}
                ,telah diserah terimakan limbah B3 dari {{$aktivitas_limbah->tujuan}} kepada
                Pengolah Limbah B3 berupa :</p>
            </p>

        </div>


        <table style=" border: 1px solid #000;">
            {{-- <tr>
                <td colspan="7">Kepada Yth. <br>
                    Koordinator Pengumpul / Ka. Unit WHRPG dan Utilitas <br>
                    di Tempat</td>
            </tr> --}}

            <tr style=" border: 1px solid #000;">
                <th>No</th>
                <th colspan="2">Jenis Limbah B3 Yang Diberikan</th>
                <th colspan="1">Kode</th>
                <th colspan="2">Banyak</th>
                <th colspan="2">Keterangan</th>
            </tr>


            @foreach ($aktivitas_keluar as $index=>$k)
            @php
            $klr = \App\Models\KlasifikasiLimbah::find($k->id_klasifikasilimbah);
            @endphp
            <tr style=" border: 1px solid #000;">
                <td>{{$index+1}}</td>
                <td colspan="2">{{$klr->jenis_limbah}}</td>
                <td colspan="1" style="text-align:center">{{$klr->kode}}</td>
                <td colspan="2" style="text-align:center">{{$k->jml_keluar}} {{$klr->satuan}}
                </td>
                <td colspan="2" style="text-align:center">{{$k->keterangan}}</td>
            </tr>
            @endforeach


        </table>

        <!-- Penanggung Jawab Kegiatan -->
        <table style="border: 1px solid #000; width: 100%;">
            <tr>
                <td colspan="6" style="text-align:right;">
                    <b>Padang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</b>
                </td>
            </tr>
            <tr style="text-align:center;">
                <th colspan="3">Yang Menyerahkan <br>TPS LB3 Tenaga</th>
                <th colspan="3">Yang Menerima <br>{{ $aktivitasLimbah->tujuan ?? '' }}</th>
            </tr>
            <tr style="text-align: center; font-weight:bold">
                <td colspan="3">
                    {{ $aktivitasLimbah->menyerahkan ?? '' }}
                    @if(session('qrCode') && request()->route()->getName() === 'validation.approve.menyerahkan')
                    <img src="{{ session('qrCode') }}" alt="QR Code">
                    @endif

                    <!-- Form Approval untuk Menyerahkan -->
                    @if($currentRole === 'menyerahkan')
                    <form action="{{ route('validation.approve.menyerahkan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $aktivitasLimbah->id }}">
                        <input type="text" name="name" placeholder="Nama yang menyerahkan" required>
                        <button type="submit">Approve</button>
                    </form>
                    @endif
                </td>
                <td colspan="3">
                    {{ $aktivitasLimbah->menerima ?? '' }}
                    @if(session('qrCode') && request()->route()->getName() === 'validation.approve.menerima')
                    <img src="{{ session('qrCode') }}" alt="QR Code">
                    @endif

                    <!-- Form Approval untuk Menerima -->
                    @if($currentRole === 'menerima')
                    <form action="{{ route('validation.approve.menerima') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $aktivitasLimbah->id }}">
                        <input type="text" name="name" placeholder="Nama yang menerima" required>
                        <button type="submit">Approve</button>
                    </form>
                    @endif
                </td>
            </tr>
            <tr>
                <th colspan="3"></th>
                <th colspan="3"></th>
            </tr>
            <tr>
                <td colspan="4" style="text-align:start;">Disaksikan Oleh :</td>
                <td colspan="2" style="text-align:start;">Diketahui :</td>
            </tr>
            <tr>
                <th colspan="2">Unit SP SHE</th>
                <th colspan="2">Unit Pengamanan</th>
                <th colspan="2">Ka.Unit SP SHE</th>
            </tr>
            <tr>
                <td colspan="2">
                    {{ $aktivitasLimbah->personil_she ?? '' }}
                    @if(session('qrCode') && request()->route()->getName() === 'validation.approve.personil_she')
                    <img src="{{ session('qrCode') }}" alt="QR Code">
                    @endif

                    <!-- Form Approval untuk Personil SHE -->
                    @if($currentRole === 'personil_she')
                    <form action="{{ route('validation.approve.personil_she') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $aktivitasLimbah->id }}">
                        <input type="text" name="name" placeholder="Nama personil SHE" required>
                        <button type="submit">Approve</button>
                    </form>
                    @endif
                </td>
                <td colspan="2">
                    {{ $aktivitasLimbah->personil_pengamanan ?? '' }}
                    @if(session('qrCode') && request()->route()->getName() === 'validation.approve.personil_pengamanan')
                    <img src="{{ session('qrCode') }}" alt="QR Code">
                    @endif

                    <!-- Form Approval untuk Personil Pengamanan -->
                    @if($currentRole === 'personil_pengamanan')
                    <form action="{{ route('validation.approve.personil_pengamanan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $aktivitasLimbah->id }}">
                        <input type="text" name="name" placeholder="Nama personil pengamanan" required>
                        <button type="submit">Approve</button>
                    </form>
                    @endif
                </td>
                <td colspan="2">
                    {{ $aktivitasLimbah->kaunit_she ?? '' }}
                    @if(session('qrCode') && request()->route()->getName() === 'validation.approve.kaunit_she')
                    <img src="{{ session('qrCode') }}" alt="QR Code">
                    @endif

                    <!-- Form Approval untuk KaUnit SHE -->
                    @if($currentRole === 'kaunit_she')
                    <form action="{{ route('validation.approve.kaunit_she') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $aktivitasLimbah->id }}">
                        <input type="text" name="name" placeholder="Nama Ka.Unit SP SHE" required>
                        <button type="submit">Approve</button>
                    </form>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>

</html>