<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Limbah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            text-align: center;
            padding: 5px;
        }

        .warna-kuning {
            background-color: #ffff00;
        }

        .warna-orange {
            background-color: #ff9900;
        }

        .warna-hijau {
            background-color: #00ffcc;
        }

        .warna-coklat {
            background-color: #c0c0c0;
        }

        .warna-biru {
            background-color: #99ccff;
        }

        .warna-merah-muda {
            background-color: #ffccff;
        }
    </style>
</head>

<body>
    <h1>Laporan Limbah Tahun {{ $tahun }}</h1>
    {{-- <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Jenis Limbah</th>
                <th rowspan="2">Sumber</th>
                <th rowspan="2">Satuan</th>
                <th rowspan="2">Perlakuan</th>

                <th colspan="12">Limbah Keluar</th>
            </tr>
            <tr>

                @foreach (range(1, 12) as $bulan)
                <th>{{ date('M', mktime(0, 0, 0, $bulan, 1)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $index=>$data)
            <tr>
                <td rowspan="7">{{$index+1}}</td>
                <td rowspan="7">{{ $data['jenis_limbah'] }}</td>
                <td rowspan="7">Utilitas</td>
                <td rowspan="7">Ton</td>
                <td rowspan="7">Perlakuan</td>
                @foreach ($data['keluar'] as $keluarBulanan)
                <td rowspan="7">
                    @foreach ($keluarBulanan as $perlakuan => $jumlah)
                    {{ ucfirst($perlakuan) }}: {{ $jumlah ?? 0 }}<br>
                    @endforeach
                </td>
                @endforeach
            </tr>
            @endforeach

        </tbody>
    </table> --}}
    <table>
        <thead>
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">JENIS LIMBAH B3</th>
                <th rowspan="2">SUMBER</th>
                <th rowspan="2">SATUAN</th>
                <th rowspan="7">PERLAKUAN</th>
                <th rowspan="2">Periode sebelumnya (SALDO)</th>
                <th colspan="12">TAHUN .......</th>
            </tr>
            <tr>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>Mei</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Ags</th>
                <th>Sep</th>
                <th>Okt</th>
                <th>Nov</th>
                <th>Des</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($klasifikasiLimbahs as $index => $limbah)
            @php
            // Ambil data bulan per jenis limbah
            $bulanData = $akumulasi->get($limbah->id, collect())->groupBy('bulan');
            // Ambil data tahun sebelumnya per jenis limbah
            $tahunSebelumnyaData = $akumulasiTahunSebelumnya->get($limbah->id, collect());
            @endphp
            <tr class="warna-coklat">
                <td rowspan="7">{{ $index + 1 }}</td>
                <td rowspan="7">{{ $limbah->jenis_limbah }}</td>
                <td rowspan="7">Proses/Utilitas</td>
                <td rowspan="7">TON</td>
                <td>DISERAHKAN KE PIHAK KETIGA BERIZIN</td>
                <td>
                    {{ number_format(optional($tahunSebelumnyaData)->sum('diserahkan_ke_pihak_ketiga'), 3, ',', '.') ??
                    0 }}
                </td>
                @for ($i = 1; $i <= 12; $i++) <td>{{
                    number_format(optional($bulanData->get($i))->sum('diserahkan_ke_pihak_ketiga'), 3, ',', '.') ?? 0 }}
                    </td>
                    @endfor
            </tr>
            <tr class="warna-coklat">
                <td>TIDAK DIKELOLA</td>
                <td>
                    {{ number_format(optional($tahunSebelumnyaData)->sum('tidak_dikelola'), 3, ',', '.') ?? 0 }}
                </td>
                @for ($i = 1; $i <= 12; $i++) <td>{{ number_format(optional($bulanData->get($i))->sum('tidak_dikelola'),
                    3, ',', '.') ?? 0 }}</td>
                    @endfor
            </tr>
            <tr class="warna-kuning">
                <td>DIHASILKAN</td>
                <td>
                    {{ number_format(optional($tahunSebelumnyaData)->sum('dihasilkan'), 3, ',', '.') ?? 0 }}
                </td>
                @for ($i = 1; $i <= 12; $i++) <td>{{ number_format(optional($bulanData->get($i))->sum('dihasilkan'), 3,
                    ',', '.') ?? 0 }}</td>
                    @endfor
            </tr>
            <tr class="warna-orange">
                <td>DISIMPAN DI TPS</td>
                <td>
                    {{ number_format(optional($tahunSebelumnyaData)->sum('disimpan'), 3, ',', '.') ?? 0 }}
                </td>
                @for ($i = 1; $i <= 12; $i++) <td>{{ number_format(optional($bulanData->get($i))->sum('disimpan'), 3,
                    ',', '.') ?? 0 }}</td>
                    @endfor
            </tr>
            <tr class="warna-hijau">
                <td>DIMANFAATKAN SENDIRI</td>
                <td>
                    {{ number_format(optional($tahunSebelumnyaData)->sum('dimanfaatkan_sendiri'), 3, ',', '.') ?? 0 }}
                </td>
                @for ($i = 1; $i <= 12; $i++) <td>{{
                    number_format(optional($bulanData->get($i))->sum('dimanfaatkan_sendiri'), 3, ',', '.') ?? 0 }}</td>
                    @endfor
            </tr>
            <tr class="warna-biru">
                <td>DIOLAH SENDIRI</td>
                <td>
                    {{ number_format(optional($tahunSebelumnyaData)->sum('diolah_sendiri'), 3, ',', '.') ?? 0 }}
                </td>
                @for ($i = 1; $i <= 12; $i++) <td>{{ number_format(optional($bulanData->get($i))->sum('diolah_sendiri'),
                    3, ',', '.') ?? 0 }}</td>
                    @endfor
            </tr>
            <tr class="warna-merah-muda">
                <td>DITIMBUN SENDIRI</td>
                <td>
                    {{ number_format(optional($tahunSebelumnyaData)->sum('ditimbun_sendiri'), 3, ',', '.') ?? 0 }}
                </td>
                @for ($i = 1; $i <= 12; $i++) <td>{{
                    number_format(optional($bulanData->get($i))->sum('ditimbun_sendiri'), 3, ',', '.') ?? 0 }}</td>
                    @endfor
            </tr>
            @endforeach
        </tbody>
    </table>




</body>

</html>