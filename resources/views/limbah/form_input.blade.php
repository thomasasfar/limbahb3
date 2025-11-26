<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Limbah</title>
    <link rel="icon" href="{{asset('img/semen_padang.png')}}" type="image/x-icon" />
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 50px;
            padding: 0;
            color: #000;
        }

        .container {
            width: 100%;
            margin: 10px auto;
            border: #000 1px;
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

        /* Modal Background */
        .modal {
            display: none;
            /* Default: Tidak terlihat */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            /* Transparansi hitam */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            /* Pusatkan modal */
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            /* Lebar modal */
            border-radius: 8px;
            text-align: center;
        }

        /* Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        //     function addRow(button) {
    //     // Temukan baris tempat tombol 'Tambah' diklik
    //     const row = button.parentElement.parentElement;

    //     // Buat elemen baris baru
    //     const newRow = document.createElement('tr');
    //     newRow.innerHTML = `
    //         <td>
    //             <select name="id_klasifikasilimbah[]" id="klasifikasiLimbah" required>
    //                 <option value="">Pilih Klasifikasi</option>
    //                 ${klasifikasiLimbahOptions}
    //             </select>
    //         </td>
    //         <td><input type="number" name="jml_masuk[]" placeholder="Jumlah masuk" required> <input type="text" id="satuan" class="form-control" placeholder="Satuan" readonly></td>
    //         <td><button type="button" onclick="addRow(this)">Tambah</button> <button type="button" onclick="removeRow(this)">Hapus</button></td>
    //     `;

    //     // Sisipkan baris baru setelah baris saat ini
    //     row.parentNode.insertBefore(newRow, row.nextSibling);
    // }

        function addRow(button) {
        // Buat baris baru
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select name="id_klasifikasilimbah[]" class="klasifikasiLimbah form-select" required>
                    <option value="">===== Pilih Jenis Limbah =====</option>
                    ${klasifikasiLimbahOptions} <!-- Pastikan klasifikasiLimbahOptions tersedia -->
                </select>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <input type="number" name="jml_masuk[]" class="form-control me-2 jml_masuk" placeholder="Jumlah masuk" required oninput="convertToKg(this)">
                    <select name="satuan[]" class="form-select me-2 satuan" required onchange="convertToKg(this)">
                        <option value="kg">kg</option>
                        <option value="ton">ton</option>
                        <option value="liter">liter</option>
                    </select>
                    <input type="text" name="jml_kg[]" class="form-control jml_kg" placeholder="Dalam kg" readonly>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-success" onclick="addRow(this)">Tambah</button> 
                <button type="button" class="btn btn-danger" onclick="removeRow(this)">Hapus</button>
            </td>
        `;

        // Tambahkan event listener untuk select option dalam baris ini
        const selectElement = newRow.querySelector('.klasifikasiLimbah');
        const satuanSelect = newRow.querySelector('.satuan');

        selectElement.addEventListener('change', function () {
            const selectedId = selectElement.value;

            if (selectedId) {
                // Ambil data satuan berdasarkan ID melalui AJAX
                fetch(`/klasifikasi-limbahs/${selectedId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.satuan) {
                            satuanSelect.value = data.satuan; // Set satuan otomatis
                            convertToKg(satuanSelect); // Panggil konversi otomatis setelah satuan diatur
                        } else {
                            satuanSelect.value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        satuanSelect.value = '';
                    });
            } else {
                satuanSelect.value = '';
            }
        });

        // Tambahkan baris ke tabel
        button.closest('table').querySelector('tbody').appendChild(newRow);
    }

    function removeRow(button) {
        // Hapus baris tempat tombol 'Hapus' diklik
        const row = button.closest('tr');
        const tableBody = row.parentElement;

        // Pastikan tabel memiliki lebih dari satu baris sebelum menghapus
        if (tableBody.rows.length > 1) {
            row.remove();
        } else {
            alert('Baris terakhir tidak dapat dihapus!');
        }
    }

    function convertToKg(element) {
        const row = element.closest('tr');
        const jumlahInput = row.querySelector('.jml_masuk');
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
            const signaturePengumpul = document.getElementById('signaturePengumpulInput').value;
            const signaturePenghasil = document.getElementById('signaturePenghasilInput').value;
            const signatureUnit = document.getElementById('signatureUnitInput').value;

            if (!signaturePengumpul || !signaturePenghasil) {
                alert('Silakan simpan semua tanda tangan terlebih dahulu!');
                // return false;
            }else if(signaturePenghasil&&signaturePengumpul&&!signatureUnit){
                document.getElementById('myModal').style.display = 'block';
            }
            return true;
        }

    window.onload = function () {
        setTodayDate();

        const signaturePadPengumpul = new SignaturePad(document.getElementById('signaturePengumpul'), {
            penColor: 'blue' // Warna tanda tangan biru
        });
        const signaturePadPenghasil = new SignaturePad(document.getElementById('signaturePenghasil'), {
            penColor: 'blue' // Warna tanda tangan biru
        });
        const signaturePadUnit = new SignaturePad(document.getElementById('signatureUnit'), {
            penColor: 'blue' // Warna tanda tangan biru
        });

        document.getElementById('clearPengumpul').onclick = function () {
            clearSignature(signaturePadPengumpul);
        };
        document.getElementById('savePengumpul').onclick = function () {
            saveSignature(signaturePadPengumpul, 'signaturePengumpulInput');
        };

        document.getElementById('clearPenghasil').onclick = function () {
            clearSignature(signaturePadPenghasil);
        };
        document.getElementById('savePenghasil').onclick = function () {
            saveSignature(signaturePadPenghasil, 'signaturePenghasilInput');
        };

        document.getElementById('clearUnit').onclick = function () {
            clearSignature(signaturePadUnit);
        };
        document.getElementById('saveUnit').onclick = function () {
            saveSignature(signaturePadUnit, 'signatureUnitInput');
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
                    <form action="/limbah" method="post">
                        @csrf
                        <input type="hidden" name="aktivitas" value="masuk">
                        <td style="width: 20%; text-align: right; vertical-align: top;">
                            <table style="font-size: 12px; border-collapse: collapse;">
                                <tr>
                                    <td>No. Formulir</td>
                                    <td>: </td>
                                    <td colspan="2"><input class="form-control" type="text" name="no_form"></td>
                                </tr>
                                <tr>
                                    <td>Tgl. Terbit</td>
                                    <td>:</td>
                                    <td colspan="2"><input class="form-control" type="date" name="tanggal"
                                            value="{{date('Y-m-d')}}"></td>
                                </tr>
                                <tr>
                                    <td>Revisi</td>
                                    <td>:</td>
                                    <td colspan="2">Rev. 0</td>
                                </tr>
                            </table>
                        </td>
                </tr>
            </table>
        </div>

        {{-- <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Judul Modal</h2>
                <p>Isi konten modal.</p>
                <button onclick="closeModal()">Tutup</button>
            </div>
        </div> --}}

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

        <table>
            <thead>
                <tr>
                    <th>Klasifikasi Limbah</th>
                    <th>Jumlah Masuk (Satuan)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="limbahInputs">
                <tr class="limbah-row">
                    <td>
                        <select name="id_klasifikasilimbah[]" class="form-select" required>
                            <option value="">===== Pilih Jenis Limbah =====</option>
                            @foreach ($klasifikasi_limbah as $index=>$k)
                            <option value="{{$k->id}}">{{$k->jenis_limbah}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control me-2 jml_masuk" name="jml_masuk[]"
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
                    <td>
                        <button type="button" class="btn btn-success addRow">Tambah</button>
                        <button type="button" class="btn btn-danger removeRow" style="display: none;">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>


        <!-- Penanggung Jawab Kegiatan -->
        <table>
            <tr>
                <td colspan="6" style="text-align:right;"><b>Padang, <input class="form-control" type="date"
                            id="todayDate" name="todayDate" required></b></td>
            </tr>
            <tr style="text-align:center;">
                <td colspan="3" rowspan="3"></td>
                <th colspan="3">Mengetahui</th>
            </tr>
            <tr style="text-align: center;font-weight:bold">

                <td colspan="3" style="text-align: center;">
                    <input class="form-control" type="text" name="no_mengetahui" placeholder="Nomor Whatsapp">
                    <br><br><input class="form-control" type="text" name="mengetahui" placeholder="Nama Ka.Unit">
                </td>
            </tr>
            <tr>
                <th colspan="3">Ka.Unit <input class="form-control" type="text" name="unit"
                        placeholder="Silahkan isi nama Unit"></th>
            </tr>
            <tr>
                <td colspan="6" style="text-align:start;">Diterima Tanggal : <input type="date"></td>
            </tr>
            <tr>
                <th colspan="3">Yang Menerima</th>
                <th colspan="3">Yang Menyerahkan</th>
            </tr>

            <tr style="text-align: center;font-weight:bold">
                <td colspan="3" style="text-align:center;">
                    {{-- <canvas id="signaturePengumpul" class="signature-pad"></canvas>
                    <button type="button" id="clearPengumpul">Clear</button>
                    <button type="button" id="savePengumpul">Save</button>
                    <input class="form-control" type="hidden" id="signaturePengumpulInput"
                        name="signature_pengumpul"><br> --}}
                    <br><input class="form-control" type="text" name="no_pengumpul"
                        placeholder="No.Whatsapp Pengumpul"><br>
                    <input class="form-control" type="text" name="pengumpul" placeholder="Nama Pengumpul">

                </td>
                <td colspan="3" style="text-align:center;">
                    <br><input class="form-control" type="text" name="no_penghasil"
                        placeholder="No.Whatsapp Pengumpul"><br>
                    <input type="text" class="form-control" name="penghasil" placeholder="Nama Penghasil">
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
    <button id="btnSubmit" onclick="validateSignatures()">Simpan Data</button>
    </form>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Ambil data klasifikasi limbah untuk mengisi dropdown
        $.get('/klasifikasi-limbahs', function(data) {
            data.forEach(function(item) {
                $('#klasifikasiLimbah').append(`<option value="${item.id}">${item.jenis_limbah}</option>`);
            });
        });
    
        // Saat dropdown berubah
        $('#klasifikasiLimbah').on('change', function() {
            const selectedId = $(this).val();
    
            if (selectedId) {
                // Fetch data satuan berdasarkan id
                $.get(`/klasifikasi-limbahs/${selectedId}`, function(data) {
                    $('#satuan').val(data.satuan);
                });
            } else {
                $('#satuan').val('');
            }
        });
    });
</script>
{{-- <script>
    function convertToKg(element) {
        const row = element.closest('.limbah-row');
        const jumlahInput = row.querySelector('#jml_masuk');
        const satuanSelect = row.querySelector('#satuan');
        const kgInput = row.querySelector('#jml_kg');

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

    // Add dynamic row functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('addLimbahRow')) {
            const newRow = document.querySelector('.limbah-row').cloneNode(true);
            newRow.querySelectorAll('input, select').forEach(el => el.value = '');
            newRow.querySelector('.removeLimbahRow').style.display = 'inline-block';
            document.getElementById('limbahInputs').appendChild(newRow);
        } else if (e.target.classList.contains('removeLimbahRow')) {
            e.target.closest('.limbah-row').remove();
        }
    });
</script> --}}

</html>