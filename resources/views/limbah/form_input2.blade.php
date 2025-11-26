<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penyerahan Limbah</title>
    <link rel="icon" href="{{asset('img/semen_padang.png')}}" type="image/x-icon" />
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 50px;
            padding: 0;
            color: #000;
            text-align: center;
        }

        .container {
            width: 100%;
            margin: 10px auto;
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
    </style>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        function addRow(button) {
            const row = button.parentElement.parentElement;
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td></td>
                <td>
                    <select name="id_klasifikasilimbah[]" required>
                        <option value="">Pilih Klasifikasi</option>
                        ${klasifikasiLimbahOptions}
                    </select>
                </td>
               <td>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control me-2 jml_keluar" name="jml_keluar[]"
                                placeholder="Jumlah" oninput="convertToKg(this)">
                            <select class="form-select me-2 satuan" name="satuan[]" onchange="convertToKg(this)">
                                <option value="kg" selected>kg</option>
                                <option value="ton">ton</option>
                                <option value="liter">liter</option>
                            </select>
                            <input type="text" class="form-control jml_kg" name="jml_kg[]" readonly
                                placeholder="Dalam kg">
                        </div>
                    </td>
                <td><button type="button" onclick="addRow(this)">Tambah</button></td>
            `;
            row.parentNode.insertBefore(newRow, row.nextSibling);
        }

        // Function to convert input value into kg
        function convertToKg(element) {
        const row = element.closest('tr');
        const jumlahInput = row.querySelector('.jml_keluar');
        const satuanSelect = row.querySelector('.satuan');
        const kgInput = row.querySelector('.jml_kg');

        const jumlah = parseFloat(jumlahInput.value) || 0;
        const satuan = satuanSelect.value;

        let convertedKg = 0;
        if (satuan === 'kg') {
            convertedKg = jumlah;
        } else if (satuan === 'ton') {
            convertedKg = jumlah * 1000;
        } else if (satuan === 'liter') {
            convertedKg = jumlah; // Assuming 1 liter = 1 kg
        }

        kgInput.value = convertedKg.toFixed(2); // Display with 2 decimal points
    }



    function removeRow(button) {
        // Hapus baris tempat tombol 'Hapus' diklik
        const row = button.parentElement.parentElement;
        const table = row.parentElement;

        // Pastikan tabel memiliki lebih dari satu baris sebelum menghapus
        if (table.rows.length > 1) {
            row.remove();
        } else {
            alert('Baris terakhir tidak dapat dihapus!');
        }
    }

    function setTodayDate() {
        const dateInput = document.getElementById('todayDate');
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
    }

    function clearSignature(signaturePad) {
        signaturePad.clear();
    }

    function saveSignature(signaturePad, inputFieldId) {
        if (signaturePad.isEmpty()) {
            alert("Tanda tangan kosong!");
            return;
        }
        const dataURL = signaturePad.toDataURL();
        document.getElementById(inputFieldId).value = dataURL;
    }
        function validateSignatures() {
        const signatureMenyerahkan = document.getElementById('signatureMenyerahkanInput').value;
        const signatureMenerima = document.getElementById('signatureMenerimaInput').value;
        const signatureUnit = document.getElementById('signatureUnitInput').value;
        const signatureKeamanan = document.getElementById('signatureKeamananInput').value;
        const signatureShe = document.getElementById('signatureSheInput').value;

        if (!signatureMenyerahkan || !signatureMenerima || !signatureUnit || !signatureKeamanan || !signatureShe) {
            alert('Silakan simpan semua tanda tangan terlebih dahulu!');
            return false; // Form will not be submitted
        }
        return true; // Form will be submitted if all signatures are saved
    }
    window.onload = function () {
    // Initialize SignaturePads
    const signaturePadMenyerahkan = new SignaturePad(document.getElementById('signatureMenyerahkan'), {
        penColor: 'blue' // Warna tanda tangan biru
    });
    const signaturePadMenerima = new SignaturePad(document.getElementById('signatureMenerima'), {
        penColor: 'blue'
    });
    const signaturePadUnit = new SignaturePad(document.getElementById('signatureUnit'), {
        penColor: 'blue'
    });
    const signaturePadKeamanan = new SignaturePad(document.getElementById('signatureKeamanan'), {
        penColor: 'blue'
    });
    const signaturePadShe = new SignaturePad(document.getElementById('signatureShe'), {
        penColor: 'blue'
    });

    // Function to clear signature
    function clearSignature(signaturePad) {
        signaturePad.clear();
    }

    // Function to save signature
    function saveSignature(signaturePad, inputFieldId) {
        if (signaturePad.isEmpty()) {
            alert("Tanda tangan kosong!");
            return;
        }
        const dataURL = signaturePad.toDataURL();
        document.getElementById(inputFieldId).value = dataURL;
    }

        // Event listeners for buttons to clear and save signature
        document.getElementById('clearMenyerahkan').onclick = function () {
            clearSignature(signaturePadMenyerahkan);
        };
        document.getElementById('saveMenyerahkan').onclick = function () {
            saveSignature(signaturePadMenyerahkan, 'signatureMenyerahkanInput');
        };

        document.getElementById('clearMenerima').onclick = function () {
            clearSignature(signaturePadMenerima);
        };
        document.getElementById('saveMenerima').onclick = function () {
            saveSignature(signaturePadMenerima, 'signatureMenerimaInput');
        };

        document.getElementById('clearUnit').onclick = function () {
            clearSignature(signaturePadUnit);
        };
        document.getElementById('saveUnit').onclick = function () {
            saveSignature(signaturePadUnit, 'signatureUnitInput');
        };

        document.getElementById('clearKeamanan').onclick = function () {
            clearSignature(signaturePadKeamanan);
        };
        document.getElementById('saveKeamanan').onclick = function () {
            saveSignature(signaturePadKeamanan, 'signatureKeamananInput');
        };

        document.getElementById('clearShe').onclick = function () {
            clearSignature(signaturePadShe);
        };
        document.getElementById('saveShe').onclick = function () {
            saveSignature(signaturePadShe, 'signatureSheInput');
        };
    };

     // Simulasi data dari controller
     const klasifikasiLimbahOptions = `
    @foreach ($klasifikasi_limbah as $index=>$k)
                            <option class="form-control" value="{{$k->id}}">{{$k->jenis_limbah}}</option>
                            @endforeach
    `;
    </script>



</head>

<body>
    <h3><b>* Note : </b> Jika diakses melalui perangkat mobile harap untuk menggunakan tampilan dekstop. </h3>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <table width="100%" style="border-collapse: collapse;border:1px solid #ffffff">
                <tr style="border-collapse: collapse;border:1px solid #ffffff">
                    <td style="width: 20%; text-align: center;">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo_sig.png'))) }}"
                            alt="" style="height: 50px;">
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
                    <form action="/limbah" method="post">
                        @csrf
                        <input type="hidden" name="aktivitas" value="keluar">
                        <td style="height: 50px; text-align: center; vertical-align: top;margin:20px">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/semen_padang.png'))) }}"
                                alt="" style="height: 80px;">
                        </td>
                </tr>
            </table>
        </div>

        <div class="container" style="text-align:justify">

            <p style="margin:0 0 10px 0;">Pada hari ini
                <b>Tanggal:{{date('now')}}</b>, telah diserah terimakan limbah B3
                dari Biro SHE kepada Pengolah Limbah B3 berupa :
            </p>
        </div>
        {{-- <table>
            <tr>
                <th style="text-align: center">
                    <h2>PENGANTAR PENYERAHAN</h2>
                </th>
                <th style="text-align: start">Nomor: </th>
            </tr>
        </table> --}}

        <table style="border: 1px solid #000;">
            <thead>
                <tr style="border: 1px solid #000;">
                    <th>No.</th>
                    <th>Klasifikasi Limbah</th>
                    <th>Jumlah Keluar (Ton)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td><select name="id_klasifikasilimbah[]" required>
                            <option value="">Pilih Klasifikasi</option>
                            @foreach ($klasifikasi_limbah as $index=>$k)
                            <option value="{{$k->id}}">{{$k->jenis_limbah}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control me-2 jml_keluar" name="jml_keluar[]"
                                placeholder="Jumlah" oninput="convertToKg(this)">
                            <select class="form-select me-2 satuan" name="satuan[]" onchange="convertToKg(this)">
                                <option value="kg" selected>kg</option>
                                <option value="ton">ton</option>
                                <option value="liter">liter</option>
                            </select>
                            <input type="text" class="form-control jml_kg" name="jml_kg[]" readonly
                                placeholder="Dalam kg">
                        </div>
                    </td>
                    <td><button type="button" onclick="addRow(this)">Tambah</button></td>
                </tr>
            </tbody>
        </table>

        <!-- Penanggung Jawab Kegiatan -->
        <table style="border: 1px solid #000;">
            <tr>
                <td colspan="6" style="text-align:right;"><b>Padang, {{
                        \Carbon\Carbon::parse(date('now'))->translatedFormat('d F Y') }}</b></td>
            </tr>
            <tr style="text-align:center;">
                <th colspan="3">Yang Menyerahkan <br>TPS LB3 Tenaga</th>
                <th colspan="3">Yang Menerima <br><input type="text" name="tujuan"></th>
            </tr>
            <tr style="text-align: center;font-weight:bold">
                <td colspan="3" style="text-align: center">
                    <input type="text" name="no_menyerahkan" placeholder="Nomor HP Penyerah">
                    <input type="text" name="menyerahkan" placeholder="Nama Penyerah">
                </td>
                <td colspan="3" style="text-align: center">
                    <input type="text" name="no_menerima" placeholder="Nomor HP Penerima">
                    <input type="text" name="menerima" placeholder="Nama Penerima">
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
            <tr style="text-align: center;font-weight:bold">
                <td colspan="2" style="text-align:center;">
                    <input type="text" name="no_personil_she" placeholder="Nomor HP SHE">
                    <input type="text" name="personil_she" placeholder="Nama SHE">
                </td>
                <td colspan="2" style="text-align:center;">
                    <input type="text" name="no_personil_keamanan" placeholder="Nomor HP Keamanan">
                    <input type="text" name="personil_keamanan" placeholder="Nama Keamanan">
                </td>
                <td colspan="2" style="text-align:center;">
                    <input type="text" name="no_kaunit_she" placeholder="Nomor HP Ka.Unit SHE">
                    <input type="text" name="kaunit_she" placeholder="Nama Ka Unit">
                </td>
            </tr>
            <tr>
                <td colspan="6">Tembusan : 1. Ka. Unit SHE</td>
            </tr>
        </table>


    </div>
    <button type="submit" onclick="validateSignatures()">Simpan Data</button>
    </form>
</body>

</html>