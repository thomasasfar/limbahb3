<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Limbah B3</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 7px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
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

        .warna-biru {
            background-color: #99ccff;
        }

        .warna-merah-muda {
            background-color: #ffccff;
        }

        .warna-abu-abu {
            background-color: #d3d3d3;
        }

        .warna-hijau-muda {
            background-color: #b0d12a;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center; margin: 30px 0 10px;">Form Neraca Limbah B3</h1>
    <h1 style="text-align: center; margin: 0 0 10px;">PT Semen Padang</h1>
    <h1 style="text-align: center; margin: 0 0 10px;">Periode {{ \Carbon\Carbon::parse($start_date)->format('F Y') }} -
        {{ \Carbon\Carbon::parse($end_date)->format('F Y') }}</h1>

    <table>
        <thead>
            <tr class="warna-abu-abu">
                <th rowspan="2">NO</th>
                <th rowspan="2">JENIS LIMBAH B3</th>
                <th rowspan="2">SUMBER</th>
                <th rowspan="2">SATUAN</th>
                <th rowspan="2">PERLAKUAN</th>
                {{-- <th rowspan="2">Periode Sebelumnya (SALDO)</th> --}}
                @foreach ($monthsInRange as $month)
                <th>{{ DateTime::createFromFormat('!m', $month)->format('M') }}</th>
                @endforeach
                <th rowspan="2">TOTAL</th>
            </tr>
            <tr class="warna-abu-abu">
                @foreach ($monthsInRange as $month)
                <th>{{ DateTime::createFromFormat('!m', $month)->format('Y') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($klasifikasiLimbahs as $index => $limbah)
            @php
            $total = $akumulasi->get($limbah->id, collect());
            $bulanData = $akumulasi->get($limbah->id, collect())->groupBy('bulan');
            @endphp

            <!-- Data DIHASILKAN -->
            <tr>
                <td rowspan="7" class="warna-abu-abu">{{ $index + 1 }}</td>
                <td rowspan="7" class="warna-abu-abu">{{ $limbah->jenis_limbah }}</td>
                <td rowspan="7" class="warna-abu-abu">Proses/Utilitas</td>
                <td rowspan="7" class="warna-abu-abu">TON</td>
                <td class="warna-kuning">DIHASILKAN</td>
                {{-- <td class="warna-kuning">{{
                    number_format(optional($akumulasiTahunSebelumnya->get($limbah->id))->sum('dihasilkan'), 3, ',', '.')
                    }}</td> --}}
                @foreach ($monthsInRange as $month)
                <td class="warna-kuning">{{ number_format(optional($bulanData->get($month))->sum('dihasilkan'), 3, ',',
                    '.') }}</td>
                @endforeach
                <td class="warna-kuning">{{ number_format(optional($total)->sum('dihasilkan'), 3, ',', '.') }}</td>
            </tr>

            <!-- Data DISIMPAN DI TPS -->
            <tr class="warna-orange">
                <td>DISIMPAN DI TPS</td>
                {{-- <td>{{ number_format(optional($akumulasiTahunSebelumnya->get($limbah->id))->sum('disimpan'), 3,
                    ',',
                    '.') }}</td> --}}
                @foreach ($monthsInRange as $month)
                <td>{{ number_format(optional($bulanData->get($month))->sum('disimpan'), 3, ',', '.') }}</td>
                @endforeach
                <td>{{ number_format(optional($total)->sum('disimpan'), 3, ',', '.') }}</td>
            </tr>

            <!-- Data DIMANFAATKAN SENDIRI -->
            <tr>
                <td class="warna-hijau">DIMANFAATKAN SENDIRI</td>
                {{-- <td>{{
                    number_format(optional($akumulasiTahunSebelumnya->get($limbah->id))->sum('dimanfaatkan_sendiri'),
                    3, ',', '.') }}</td> --}}
                @foreach ($monthsInRange as $month)
                <td>{{ number_format(optional($bulanData->get($month))->sum('dimanfaatkan_sendiri'), 3, ',', '.') }}
                </td>
                @endforeach
                <td>{{ number_format(optional($total)->sum('dimanfaatkan_sendiri'), 3, ',', '.') }}</td>
            </tr>

            <!-- Data DIOLAH SENDIRI -->
            <tr>
                <td class="warna-biru">DIOLAH SENDIRI</td>
                {{-- <td>{{ number_format(optional($akumulasiTahunSebelumnya->get($limbah->id))->sum('diolah_sendiri'),
                    3,
                    ',', '.') }}</td> --}}
                @foreach ($monthsInRange as $month)
                <td>{{ number_format(optional($bulanData->get($month))->sum('diolah_sendiri'), 3, ',', '.') }}</td>
                @endforeach
                <td>{{ number_format(optional($total)->sum('diolah_sendiri'), 3, ',', '.') }}</td>
            </tr>

            <!-- Data DITIMBUN SENDIRI -->
            <tr>
                <td class="warna-merah-muda">DITIMBUN SENDIRI</td>
                {{-- <td>{{
                    number_format(optional($akumulasiTahunSebelumnya->get($limbah->id))->sum('ditimbun_sendiri'), 3,
                    ',', '.') }}</td> --}}
                @foreach ($monthsInRange as $month)
                <td>{{ number_format(optional($bulanData->get($month))->sum('ditimbun_sendiri'), 3, ',', '.') }}</td>
                @endforeach
                <td>{{ number_format(optional($total)->sum('ditimbun_sendiri'), 3, ',', '.') }}</td>
            </tr>

            <!-- Data DISERAHKAN KE PIHAK KETIGA -->
            <tr class="warna-hijau-muda">
                <td>DISERAHKAN KE PIHAK KETIGA</td>
                {{-- <td>{{
                    number_format(optional($akumulasiTahunSebelumnya->get($limbah->id))->sum('diserahkan_ke_pihak_ketiga'),
                    3, ',', '.') }}</td> --}}
                @foreach ($monthsInRange as $month)
                <td>{{ number_format(optional($bulanData->get($month))->sum('diserahkan_ke_pihak_ketiga'), 3, ',', '.')
                    }}</td>
                @endforeach
                <td>{{ number_format(optional($total)->sum('diserahkan_ke_pihak_ketiga'), 3, ',', '.') }}</td>
            </tr>

            <!-- Data TIDAK DIKELOLA -->
            <tr class="warna-abu-abu">
                <td>TIDAK DIKELOLA</td>
                {{-- <td>{{ number_format(optional($akumulasiTahunSebelumnya->get($limbah->id))->sum('tidak_dikelola'),
                    3,
                    ',', '.') }}</td> --}}
                @foreach ($monthsInRange as $month)
                <td>{{ number_format(optional($bulanData->get($month))->sum('tidak_dikelola'), 3, ',', '.') }}</td>
                @endforeach
                <td>{{ number_format(optional($total)->sum('tidak_dikelola'), 3, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>