@extends('layout.index')
@section('title')
Limbah
@endsection
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    #signature-pad {
        border: 1px solid black;
        background-color: lightgray;
        /* Background for visibility */
        width: 100%;
        height: 200px;
    }
</style>
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Limbah</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Data Aktivitas Limbah B3</a>
                </li>
            </ul>
        </div>

        <!-- Tabel Job Safety Analysis -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                    </div>
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">Laporan Limbah</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">Limbah
                                    Masuk</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                    type="button" role="tab" aria-controls="contact" aria-selected="false">Limbah
                                    Keluar</button>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content mt-3">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row my-4">
                                    <div class="col-md-6">
                                        <h4 class="card-title">Laporan Limbah B3</h4>
                                    </div>
                                    <div class="col-md-6 d-grid gap-2 d-md-flex justify-content-md-end">
                                        <!-- Tombol untuk membuka modal -->
                                        <button type="button" class="btn btn-primary justify-content-end text-small"
                                            data-bs-toggle="modal" data-bs-target="#klasifikasiModal">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Tambahkan Klasifikasi Limbah
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="multi-filter-select" class="display table table-striped table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>Jenis Limbah</th>
                                                <th>Kode Limbah</th>
                                                <th>Jumlah Akumulasi Limbah</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($klasifikasi_limbah as $index=>$k)
                                            @php
                                            $jml_masuk =
                                            App\Models\AktivitasMasukLimbah::where('id_klasifikasilimbah',$k->id)
                                            ->pluck('jml_masuk')->toArray();
                                            $jml_keluar =
                                            App\Models\AktivitasKeluarLimbah::where('id_klasifikasilimbah',$k->id)
                                            ->pluck('jml_keluar')->toArray();
                                            $akumulasi_masuk = array_sum($jml_masuk);
                                            $akumulasi_keluar = array_sum($jml_keluar);
                                            @endphp
                                            <tr>
                                                <td>{{$index+1}}</td>
                                                <td>{{$k->jenis_limbah}} </td>
                                                <td class="text-center">{{$k->kode_limbah}}</td>
                                                <td class="text-center">
                                                    {{array_sum($jml_masuk)-array_sum($jml_keluar)}}
                                                    kg</td>
                                                <td>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-responsive">
                                    <h2>Data Akumulasi Limbah Berdasarkan Unit</h2>
                                    <table id="multi-filter-select3" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Unit</th>
                                                <th>Total Jml Masuk Limbah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results as $index=>$data)
                                            <tr>
                                                <td>{{ $index+1 }}</td>
                                                <td>{{ $data->unit }}</td>
                                                <td>{{ number_format($data->total_jml_masuk, 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row my-4">
                                    <div class="col-md-6">
                                        <h4 class="card-title">Laporan Limbah B3 Masuk</h4>
                                    </div>
                                    <div class="col-md-6 d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a type="button" class="btn btn-primary" href="/limbah/create"> <i
                                                class="fa fa-plus" aria-hidden="true"></i> Tambahkan Data Limbah
                                            Masuk</a>
                                        <button type="button" class="btn btn-primary justify-content-end text-small"
                                            data-bs-toggle="modal" data-bs-target="#addLimbahMasuk">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Tambahkan Data Limbah Keluar
                                        </button>

                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <div class="table-responsive">
                                        <table id="multi-filter-select1"
                                            class="display table table-striped table-hover">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>No</th>
                                                    {{-- <th>Tanggal Masuk LB3</th> --}}
                                                    <th>Tanggal Masuk Limbah</th>
                                                    <th>Jenis LB3</th>
                                                    <th>Sumber LB3</th>
                                                    <th>Jumlah LB3 Masuk</th>
                                                    <th>Maksimal Penyimpanan s.d Tanggal</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($aktivitas_masuk as $index=>$a)
                                                @php
                                                $masuk = App\Models\KlasifikasiLimbah::find($a->id_klasifikasilimbah);
                                                @endphp
                                                <tr>
                                                    <td>{{$index+1}}</td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($a->tgl_masuk)->translatedFormat('l,d F
                                                        Y')
                                                        }}</td>
                                                    <td>{{$masuk->jenis_limbah}}</td>
                                                    <td>{{$a->aktivitas_limbah->sumber ?? "-"}}</td>
                                                    <td>{{$a->jml_masuk}} {{$masuk->satuan}}</td>
                                                    <td>{{Carbon\Carbon::parse($a->tgl_masuk)->addDays(90)}}</td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-primary justify-content-end text-small"
                                                            data-bs-toggle="modal" data-bs-target="#pdfModal{{$index}}">
                                                            <i class="fas fa-file-signature" aria-hidden="true"></i>
                                                        </button>

                                                        <button onclick="confirmDelete({{ $a->id }})" type="button"
                                                            class="btn btn-link btn-danger btn-lg"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Tombol Hapus Data">
                                                            <i class="fa fa-times"></i>
                                                        </button>

                                                        <div class="modal fade" id="pdfModal{{$index}}" tabindex="-1"
                                                            aria-labelledby="pdfModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="pdfModalLabel">
                                                                            Tampilkan PDF</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        @if ($a->id_aktivitaslimbah!=null)
                                                                        <iframe
                                                                            src="{{ asset('laporan/limbah/masuk/limbah_masuk' .$a->aktivitas_limbah->id.'.pdf' ) }}"
                                                                            style="width: 100%; height: 500px; border: none;"></iframe>
                                                                        @else
                                                                        <i>Dokumen PDF tidak tersedia</i>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <h3>Jumlah Limbah Masuk Pada Periode Tahun</h3>
                                <div>
                                    <!-- Dropdown untuk memilih tahun -->
                                    <label for="yearSelect">Pilih Tahun:</label>
                                    <select class="form-control" id="yearSelect">
                                        <option value="" selected>=====Pilih Tahun=====</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                    </select>
                                </div>
                                <div>
                                    <!-- Canvas untuk menampilkan grafik, ID diperbarui menjadi limbahChartByYear -->
                                    <canvas id="limbahChartByYear"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="row my-4">
                                    <div class="col-md-6">
                                        <h4 class="card-title">Laporan Limbah B3 Keluar</h4>
                                    </div>
                                    <div class="col-md-6 d-grid gap-2 d-md-flex justify-content-md-end">
                                        <!-- Tombol untuk membuka modal -->
                                        <button type="button" class="btn btn-primary justify-content-end text-small"
                                            data-bs-toggle="modal" data-bs-target="#limbahModal">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Tambahkan Data Limbah Keluar
                                        </button>
                                        <a type="button" href="/limbah/creates"
                                            class="btn btn-primary justify-content-end text-small">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Tambahkan Data Limbah Keluar
                                            (Pihak Ketiga)
                                        </a>
                                        <form action="{{ route('laporan.generate') }}" method="POST">
                                            @csrf
                                            <button type="button" class="btn btn-primary justify-content-end text-small"
                                                data-bs-toggle="modal" data-bs-target="#generateLaporan">
                                                <i class="fa fa-file" aria-hidden="true"></i> Generate Laporan
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="generateLaporan" data-bs-backdrop="static"
                                                data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                Generate Laporan</h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="laporanForm">
                                                                <div class="mb-3">
                                                                    <label for="startDate" class="form-label">Tanggal
                                                                        Mulai</label>
                                                                    <input type="date" class="form-control"
                                                                        id="startDate" name="start_date" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="endDate" class="form-label">Tanggal
                                                                        Akhir</label>
                                                                    <input type="date" class="form-control" id="endDate"
                                                                        name="end_date" required>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Generate</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <div class="table-responsive">
                                        <table id="multi-filter-select2"
                                            class="display table table-striped table-hover">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>No</th>
                                                    {{-- <th>Tanggal Keluar LB3</th> --}}
                                                    <th>Tahun Periode Keluar Limbah</th>
                                                    <th>Jenis Limbah B3</th>
                                                    <th>Tujuan Penyerahan LB3</th>
                                                    <th>Jumlah LB3 Keluar</th>
                                                    <th>Dokumen</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($aktivitas_keluar as $index=>$a)
                                                <tr>
                                                    <td>{{$index+1}}</td>
                                                    {{-- <td>{{$a->tgl_keluar}}</td> --}}
                                                    <td>{{\Carbon\Carbon::parse($a->tgl_keluar)->format('Y')}}</td>
                                                    <td>{{$a->klasifikasi_limbah->jenis_limbah}}</td>
                                                    <td>{{$a->tujuan?? '-'}}</td>
                                                    <td>{{$a->jml_keluar}}</td>
                                                    <td>{{Carbon\Carbon::parse($a->tgl_masuk)->addDays(90)}}</td>
                                                    <td>
                                                        <button onclick="confirmDelete({{ $a->id }})" type="button"
                                                            class="btn btn-link btn-danger btn-lg"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Tombol Hapus Data">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-primary justify-content-end text-small"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#pdfModals{{$index}}">
                                                            <i class="fas fa-file-signature" aria-hidden="true"></i>
                                                        </button>
                                                        <div class="modal fade" id="pdfModals{{$index}}" tabindex="-1"
                                                            aria-labelledby="pdfModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="pdfModalLabel">
                                                                            Tampilkan PDF</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        {{-- <input type="hidden" name="file_path"
                                                                            id="filePath"
                                                                            value="laporan/limbah/masuk/limbah_masuk'.{{$a->aktivitas_limbah->id}}.'.pdf">
                                                                        --}}
                                                                        @if ($a->id_aktivitaslimbah!=null)
                                                                        <iframe
                                                                            src="{{ asset('laporan/limbah/keluar/limbah_keluar' .$a->aktivitas_limbah->id.'.pdf' ) }}"
                                                                            style="width: 100%; height: 500px; border: none;"></iframe>
                                                                        @else
                                                                        <i>Dokumen PDF tidak tersedia</i>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        {{-- <button id="share-page"
                                                                            class="btn btn-secondary">Bagikan
                                                                            Halaman</button> --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <h3>Jumlah Limbah Keluar Pada Periode Tahun</h3>
                                <div>
                                    <!-- Dropdown untuk memilih tahun -->
                                    <label for="yearSelect">Pilih Tahun:</label>
                                    <select class="form-control" id="yearSelects">
                                        <option value="" selected>=====Pilih Tahun=====</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                    </select>
                                </div>
                                <div>
                                    <!-- Canvas untuk menampilkan grafik, ID diperbarui menjadi limbahChartByYear -->
                                    <canvas id="limbahChartByYears"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">


                        {{-- <h2>Grafik Limbah Masuk per Tahun</h2>
                        <canvas id="grafikMasuk"></canvas>

                        <h2>Grafik Limbah Keluar per Tahun</h2>
                        <canvas id="grafikKeluar"></canvas>

                        <h2>Akumulasi Sisa Limbah per Tahun</h2>
                        <canvas id="grafikSisa"></canvas> --}}

                        <h2>Grafik Limbah PerTahun</h2>

                        <!-- Dropdown untuk memilih jenis limbah -->
                        <label for="limbah">Pilih Jenis Limbah:</label>
                        <select class="form-control" id="limbah" name="limbah">
                            <option value="">-- Pilih Limbah --</option>
                            @foreach($klasifikasi_limbah as $limbah)
                            <option value="{{ $limbah->id }}">{{ $limbah->jenis_limbah }}</option>
                            @endforeach
                        </select>

                        <!-- Tempat untuk Grafik -->
                        <canvas id="grafikLimbah" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addLimbahMasuk" tabindex="-1" aria-labelledby="addLimbahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="formLimbah" action="{{ route('limbah.store') }}" method="POST">
                @csrf
                <input type="hidden" name="aktivitas" value="masuk">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLimbahModalLabel">Tambah Limbah Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form fields -->
                    <div class="mb-3">
                        <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk"
                            value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="mb-3">
                        <label for="sumber" class="form-label">Unit / Instansi Penghasil</label>
                        <input type="text" class="form-control" id="sumber" name="unit">
                    </div>

                    <label for="" class="form-label">Data Limbah</label>
                    <div id="limbahInputs">
                        <div class="limbah-row mb-3 d-flex align-items-center">
                            <div class="flex-grow-1 me-2">
                                <select class="form-select" id="id_klasifikasilimbah" name="id_klasifikasilimbah[]">
                                    <option value="" selected>=====Pilih Klasifikasi=====</option>
                                    @foreach($klasifikasi_limbah as $klasifikasi)
                                    <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->jenis_limbah }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <input type="text" class="form-control" id="jml_masuk" name="jml_masuk[]"
                                    placeholder="Jumlah">
                            </div>
                            <div>
                                <button type="button" class="btn btn-danger removeLimbahRow"
                                    style="display:none;">-</button>
                                <button type="button" class="btn btn-success addLimbahRow">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="pengumpul" class="form-label">Personil Pengumpul</label>
                        <input type="text" class="form-control" id="pengumpul" name="pengumpul">
                        <div class="mt-2">
                            <h5>Signature Pengumpul</h5>
                            <canvas id="signature-pad-pengumpul" class="border"
                                style="width: 100%; height: 150px;"></canvas>
                            <input type="hidden" name="signature_pengumpul" id="signature-data-pengumpul">
                            <button type="button" id="clear-signature-pengumpul" class="btn btn-danger mt-2">Clear
                                Signature Pengumpul</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="penghasil" class="form-label">Personil Penghasil</label>
                        <input type="text" class="form-control" id="penghasil" name="penghasil">
                        <div class="mt-2">
                            <h5>Signature Penghasil</h5>
                            {{-- <canvas id="signature-pad-penghasil" class="border"
                                style="width: 100%; height: 150px;"></canvas> --}}
                            <input type="hidden" name="signature_penghasil" id="signature-data-penghasil">
                            <button type="button" id="clear-signature-penghasil" class="btn btn-danger mt-2">Clear
                                Signature Penghasil</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Menambahkan baris input baru
        document.querySelector('.addLimbahRow').addEventListener('click', function() {
            // Duplikasi baris pertama
            const newRow = document.querySelector('.limbah-row').cloneNode(true);
            
            // Reset nilai input pada baris baru
            newRow.querySelectorAll('input, select').forEach(input => input.value = '');
            
            // Mengubah tombol + menjadi tombol - pada baris baru
            const addButton = newRow.querySelector('.addLimbahRow');
            addButton.style.display = 'none'; // Menyembunyikan tombol +
            
            const removeButton = newRow.querySelector('.removeLimbahRow');
            removeButton.style.display = 'inline-block'; // Menampilkan tombol -

            // Menambahkan baris baru ke dalam #limbahInputs2
            document.getElementById('limbahInputs').appendChild(newRow);
        });

        // Menghapus baris input saat tombol - diklik
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('removeLimbahRow')) {
                const row = e.target.closest('.limbah-row');
                // Pastikan ada lebih dari 1 baris sebelum menghapus
                if (document.querySelectorAll('.limbah-row').length > 1) {
                    row.remove();
                }
            }
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get references to the select dropdown and canvas element
    const yearSelect = document.getElementById('yearSelect');
    const ctx = document.getElementById('limbahChartByYear').getContext('2d');

    // Initialize the Chart.js chart variable
    let limbahChartByYear;

    // Function to fetch the data based on the selected year
    function getLimbahData(year) {
        fetch(`/limbah-masuk/${year}`)
            .then(response => response.json())
            .then(data => {
                // Process the data to prepare labels and data for the chart
                const labels = [];
                const masukData = [];
                
                // Loop through the data to extract labels (jenis limbah) and values (total_masuk)
                data.forEach(item => {
                    labels.push(item.jenis_limbah);  // The waste type name
                    masukData.push(item.total_masuk); // The total amount of waste entered
                });

                // Update the chart with the new data
                updateChart2(labels, masukData);
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // Function to update the chart with the new data
    function updateChart2(labels, masukData) {
        // If a chart already exists, destroy it before creating a new one
        if (limbahChartByYear) {
            limbahChartByYear.destroy();
        }

        // Create a new chart
        limbahChartByYear = new Chart(ctx, {
            type: 'bar', // Chart type: Bar chart
            data: {
                labels: labels, // X-axis labels (jenis limbah)
                datasets: [{
                    label: 'Jumlah Limbah Masuk (Ton)', // Dataset label
                    data: masukData, // Data for the bars (total_masuk)
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Bar background color
                    borderColor: 'rgba(54, 162, 235, 1)', // Bar border color
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true, // Make the chart responsive
                scales: {
                    y: {
                        beginAtZero: true // Start the y-axis from zero
                    }
                }
            }
        });
    }

    // Event listener for when the year is selected
    yearSelect.addEventListener('change', (event) => {
        const selectedYear = event.target.value; // Get the selected year
        getLimbahData(selectedYear); // Fetch the data for the selected year
    });

    // Fetch data for the default year (2023)
    getLimbahData(yearSelect.value);

</script>

<script>
    $(document).ready(function () {
      $("#basic-datatables").DataTable({});

      $("#multi-filter-select3").DataTable({
        pageLength: 10,
        initComplete: function () {
          this.api()
            .columns()
            .every(function () {
              var column = this;
              var select = $(
                '<select class="form-select"><option value=""></option></select>'
              )
                .appendTo($(column.footer()).empty())
                .on("change", function () {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());

                  column
                    .search(val ? "^" + val + "$" : "", true, false)
                    .draw();
                });

              column
                .data()
                .unique()
                .sort()
                .each(function (d, j) {
                  select.append(
                    '<option value="' + d + '">' + d + "</option>"
                  );
                });
            });
        },
      });

      // Add Row
      $("#add-row").DataTable({
        pageLength: 3,
      });

      var action =
        '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

      $("#addRowButton").click(function () {
        $("#add-row")
          .dataTable()
          .fnAddData([
            $("#addName").val(),
            $("#addPosition").val(),
            $("#addOffice").val(),
            action,
          ]);
        $("#addRowModal").modal("hide");
      });
    });
</script>

<script>
    // Get references to the select dropdown and canvas element
    const yearSelects = document.getElementById('yearSelects');
    const ctxs = document.getElementById('limbahChartByYears').getContext('2d');

    // Initialize the Chart.js chart variable
    let limbahChartByYears;

    // Function to fetch the data based on the selected year
    function getLimbahDatas(year) {
        fetch(`/limbah-keluar/${year}`)
            .then(response => response.json())
            .then(data => {
                // Process the data to prepare labels and data for the chart
                const labelss = [];
                const keluarData = [];
                
                // Loop through the data to extract labelss (jenis limbah) and values (total_masuk)
                data.forEach(item => {
                    labelss.push(item.jenis_limbah);  // The waste type name
                    keluarData.push(item.total_masuk); // The total amount of waste entered
                });

                // Update the chart with the new data
                updateChart3(labelss, keluarData);
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // Function to update the chart with the new data
    function updateChart3(labelss, keluarData) {
        // If a chart already exists, destroy it before creating a new one
        if (limbahChartByYears) {
            limbahChartByYears.destroy();
        }

        // Create a new chart
        limbahChartByYears = new Chart(ctxs, {
            type: 'bar', // Chart type: Bar chart
            data: {
                labels: labelss, // X-axis labels (jenis limbah)
                datasets: [{
                    label: 'Jumlah Limbah Masuk', // Dataset label
                    data: keluarData, // Data for the bars (total_masuk)
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Bar background color
                    borderColor: 'rgba(54, 162, 235, 1)', // Bar border color
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true, // Make the chart responsive
                scales: {
                    y: {
                        beginAtZero: true // Start the y-axis from zero
                    }
                }
            }
        });
    }

    // Event listener for when the year is selected
    yearSelects.addEventListener('change', (event) => {
        const selectedYear = event.target.value; // Get the selected year
        getLimbahData(selectedYears); // Fetch the data for the selected year
    });

    // Fetch data for the default year (2023)
    getLimbahDatas(yearSelects.value);

</script>


{{-- script untuk filter limbah --}}
<script>
    $(document).ready(function() {
    $('#filter-btn').click(function() {
        var startMonth = $('#start-month').val();
        var startYear = $('#start-year').val();
        var endMonth = $('#end-month').val();
        var endYear = $('#end-year').val();

        // Kirim data filter ke server untuk mendapatkan hasil yang difilter
        $.ajax({
            url: '{{ route('filterLimbah') }}', // Ganti dengan route yang sesuai di Laravel
            method: 'GET',
            data: {
                start_month: startMonth,
                start_year: startYear,
                end_month: endMonth,
                end_year: endYear
            },
            success: function(response) {
                // Mengupdate tabel dengan data yang difilter
                var tableBody = $('#multi-filter-select tbody');
                tableBody.empty();
                
                response.data.forEach(function(k, index) {
                    var row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${k.jenis_limbah}</td>
                            <td class="text-center">${k.kode_limbah}</td>
                            <td class="text-center">${k.akumulasi_masuk - k.akumulasi_keluar} ${k.satuan}</td>
                            <td>${k.akumulasi_masuk} () ${k.akumulasi_keluar}</td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            }
        });
    });
    });

</script>

<script>
    $(document).ready(function() {
    $('#filter-btn1').click(function() {
        var startMonth = $('#start-month').val();
        var startYear = $('#start-year').val();
        var endMonth = $('#end-month').val();
        var endYear = $('#end-year').val();

        // Kirim data filter ke server untuk mendapatkan hasil yang difilter
        $.ajax({
            url: '{{ route('filterAktivitasMasuk') }}', // Ganti dengan route yang sesuai di Laravel
            method: 'GET',
            data: {
                start_month: startMonth,
                start_year: startYear,
                end_month: endMonth,
                end_year: endYear
            },
            success: function(response) {
                // Mengupdate tabel dengan data yang difilter
                var tableBody = $('#multi-filter-select1 tbody');
                tableBody.empty();
                
                response.data.forEach(function(a, index) {
                    var row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${a.tgl_masuk}</td>
                            <td>${a.jenis_limbah}</td>
                            <td>${a.sumber_limbah}</td>
                            <td>${a.jml_masuk} ${a.satuan}</td>
                            <td>${a.maksimal_penyimpanan}</td>
                            <td>
                                <button type="button"
                                    class="btn btn-primary justify-content-end text-small"
                                    data-bs-toggle="modal" data-bs-target="#pdfModal${index}">
                                    <i class="fas fa-file-signature" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            }
        });
    });
    });

</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let chartLimbah;

    // Event listener saat memilih limbah
    $('#limbah').change(function() {
        const idLimbah = $(this).val();

        if (idLimbah) {
            // Ambil data dari server menggunakan AJAX
            $.ajax({
                url: '{{ route("grafik.limbah.data") }}',
                method: 'POST',
                data: {
                    id_klasifikasilimbah: idLimbah,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    updateChart(response);
                }
            });
        }
    });

    // Fungsi untuk memperbarui grafik
    function updateChart(data) {
        const labels = data.map(item => item.tahun);
        const masukData = data.map(item => item.masuk);
        const keluarData = data.map(item => item.keluar);
        const sisaData = data.map(item => item.sisa);

        // Hapus grafik lama jika ada
        if (chartLimbah) {
            chartLimbah.destroy();
        }

        const ctx = document.getElementById('grafikLimbah').getContext('2d');
        chartLimbah = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Limbah Masuk',
                        data: masukData,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Limbah Keluar',
                        data: keluarData,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Sisa Limbah',
                        data: sisaData,
                        backgroundColor: 'rgba(153, 102, 255, 0.7)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
</script>

<script>
    // Data dari Controller (menggunakan Blade Directive)
    const tahunLabels = @json(array_keys($sisaPerTahun));
    const limbahMasuk = @json($limbahMasuk);
    const limbahKeluar = @json($limbahKeluar);
    const sisaPerTahun = @json(array_values($sisaPerTahun));

    // Grafik Limbah Masuk
    const ctxMasuk = document.getElementById('grafikMasuk').getContext('2d');
    new Chart(ctxMasuk, {
        type: 'bar',
        data: {
            labels: tahunLabels,
            datasets: [{
                label: 'Limbah Masuk',
                data: limbahMasuk.map(item => item.total_masuk),
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true }
    });

    // Grafik Limbah Keluar
    const ctxKeluar = document.getElementById('grafikKeluar').getContext('2d');
    new Chart(ctxKeluar, {
        type: 'bar',
        data: {
            labels: tahunLabels,
            datasets: [{
                label: 'Limbah Keluar',
                data: limbahKeluar.map(item => item.total_keluar),
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true }
    });

    // Grafik Sisa Limbah
    const ctxSisa = document.getElementById('grafikSisa').getContext('2d');
    new Chart(ctxSisa, {
        type: 'bar',
        data: {
            labels: tahunLabels,
            datasets: [{
                label: 'Akumulasi Sisa Limbah',
                data: sisaPerTahun,
                backgroundColor: 'rgba(153, 102, 255, 0.7)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: { responsive: true }
    });
</script>

<!-- Bootstrap JS and Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Signature Pad Library -->

<!-- JavaScript for Signature Pad -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const canvasPengumpul = document.getElementById('signature-pad-pengumpul');
        const canvasPenghasil = document.getElementById('signature-pad-penghasil');
        const signatureDataInputPengumpul = document.getElementById('signature-data-pengumpul');
        const signatureDataInputPenghasil = document.getElementById('signature-data-penghasil');

        if (!canvasPengumpul || !canvasPenghasil) {
            console.error('Canvas elements are missing in the DOM.');
            return;
        }

        const signaturePadPengumpul = new SignaturePad(canvasPengumpul, { penColor: 'black' });
        const signaturePadPenghasil = new SignaturePad(canvasPenghasil, { penColor: 'black' });

        function resizeCanvas(canvas, signaturePad) {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }

        resizeCanvas(canvasPengumpul, signaturePadPengumpul);
        resizeCanvas(canvasPenghasil, signaturePadPenghasil);

        window.addEventListener('resize', () => {
            resizeCanvas(canvasPengumpul, signaturePadPengumpul);
            resizeCanvas(canvasPenghasil, signaturePadPenghasil);
        });

        document.getElementById('clear-signature-pengumpul').addEventListener('click', () => {
            signaturePadPengumpul.clear();
        });

        document.getElementById('clear-signature-penghasil').addEventListener('click', () => {
            signaturePadPenghasil.clear();
        });

        document.getElementById('formLimbah').addEventListener('submit', (e) => {
            if (signaturePadPengumpul.isEmpty() || signaturePadPenghasil.isEmpty()) {
                alert('Please add both signatures before submitting.');
                e.preventDefault();
                return;
            }
            signatureDataInputPengumpul.value = signaturePadPengumpul.toDataURL();
            signatureDataInputPenghasil.value = signaturePadPenghasil.toDataURL();
        });
    });
</script>



<!-- Modal menambahkan data limbah keluar-->
<div class="modal fade" id="limbahModal" tabindex="-1" aria-labelledby="limbahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="limbahModalLabel">Pengajuan Penyerahan Limbah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/pengajuan/limbah" method="POST" onsubmit="return validateSignatures()">
                    @csrf
                    <label for="" class="form-label">Data Limbah</label>

                    <div id="limbahInputs">
                        <div class="limbah-row mb-3 p-3 border border-1 rounded">
                            <div class="row g-3">
                                <div class="col-lg-4 col-md-6">
                                    <label for="klasifikasiLimbah" class="form-label">Klasifikasi Limbah</label>
                                    <select class="form-select id_klasifikasilimbah" name="id_klasifikasilimbah[]">
                                        <option value="" selected>=====Pilih Klasifikasi=====</option>
                                        @foreach($klasifikasi_limbah as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}" data-satuan="{{ $klasifikasi->satuan }}"
                                            data-konversi="{{ $klasifikasi->konversi }}">
                                            {{ $klasifikasi->jenis_limbah }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <label for="jumlahMasuk" class="form-label">Jumlah Limbah</label>
                                    <div class="d-flex">
                                        <input type="number" class="form-control me-2 jml_masuk" name="jml_masuk[]"
                                            placeholder="Jumlah" oninput="convertToKg(this)">
                                        <select class="form-select me-2 satuan" onchange="convertToKg(this)">
                                            <!-- Options diisi secara dinamis -->
                                        </select>
                                        <input type="text" class="form-control jml_kg" name="jml_kg[]" readonly
                                            placeholder="Dalam kg">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control" name="keterangan[]" rows="2"
                                        placeholder="Keterangan (opsional)"></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-2">
                                <button type="button" class="btn btn-danger removeLimbahRow me-2"
                                    style="display:none;">Hapus</button>
                                <button type="button" class="btn btn-success addLimbahRow">Tambah</button>
                            </div>
                            <small class="text-muted note-konversi mt-2 d-block"></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsiLimbah" class="form-label">Nomor Surat</label>
                        <input type="text" class="form-control" name="no_form"
                            placeholder="Masukan Nomor Surat"></input>
                        <small class="text-disable"><b>Catatan:</b> Masukan nomor surat sesuai dengan NDO</small>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsiLimbah" class="form-label">Nama Personil Yang Menyerahkan</label>
                        <input type="text" class="form-control" name="menyerahkan"
                            placeholder="Masukan Nama Personil Yang Menyerahkan"></input>

                    </div>
                    <div class="mb-3">
                        <label for="tanggalPenyerahan" class="form-label">Tanggal Penyerahan</label>
                        <input type="date" class="form-control" id="tanggalPenyerahan" name="tanggal"
                            value="{{ now()->toDateString() }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Menambahkan baris input baru
        document.querySelector('.addLimbahRow2').addEventListener('click', function() {
            // Duplikasi baris pertama
            const newRow = document.querySelector('.limbah-row2').cloneNode(true);
            
            // Reset nilai input pada baris baru
            newRow.querySelectorAll('input, select').forEach(input => input.value = '');
            
            // Mengubah tombol + menjadi tombol - pada baris baru
            const addButton = newRow.querySelector('.addLimbahRow2');
            addButton.style.display = 'none'; // Menyembunyikan tombol +
            
            const removeButton = newRow.querySelector('.removeLimbahRow2');
            removeButton.style.display = 'inline-block'; // Menampilkan tombol -

            // Menambahkan baris baru ke dalam #limbahInputs
            document.getElementById('limbahInputs2').appendChild(newRow);
        });

        // Menghapus baris input saat tombol - diklik
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('removeLimbahRow2')) {
                const row = e.target.closest('.limbah-row2');
                // Pastikan ada lebih dari 1 baris sebelum menghapus
                if (document.querySelectorAll('.limbah-row2').length > 1) {
                    row.remove();
                }
            }
        });
    });
</script>

<!-- Modal menambahkan klasifikasi limbah -->
<div class="modal fade" id="klasifikasiModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="roleModalLabel">Klasifikasi Limbah</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Jenis Limbah</th>
                            <th>Kode Limbah</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($klasifikasi_limbah as $index => $k)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $k->jenis_limbah }}</td>
                            <td>{{ $k->kode_limbah }}</td>
                            <td>{{ $k->satuan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <!-- Form for adding a new role -->
                <h5>Tambahkan Data Klasifikasi Limbah</h5>
                <form action="{{ route('limbah.klasifikasi') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="role_name">Jenis Limbah</label>
                        <input type="text" class="form-control" id="role_name" name="jenis_limbah" required>
                    </div>
                    <div class="form-group">
                        <label for="role_name">Kode Limbah</label>
                        <input type="text" class="form-control" id="role_name" name="kode_limbah" required>
                    </div>
                    <div class="form-group">
                        <label for="role_name">Satuan</label>
                        <input type="text" class="form-control" id="role_name" name="satuan" required>
                    </div>
                    <div class="form-group">
                        <label for="role_name">Konversi ke Kg</label>
                        <input type="text" class="form-control" id="konversi" name="konversi" required>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">Tambah Data</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    async function fetchLaporan() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (!startDate || !endDate) {
            alert('Please fill in both dates.');
            return;
        }

        if (new Date(startDate) > new Date(endDate)) {
            alert('Start date cannot be later than end date.');
            return;
        }

        try {
            const response = await fetch('/generate-laporan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ startDate, endDate }),
            });

            if (response.ok) {
                const data = await response.json();
                displayLaporan(data);
            } else {
                alert('Failed to generate laporan. Please try again.');
            }
        } catch (error) {
            console.error('Error fetching laporan:', error);
            alert('An error occurred. Please check the console for more details.');
        }
    }

    function displayLaporan(data) {
        const resultsDiv = document.getElementById('laporanResults');
        resultsDiv.innerHTML = '';

        if (data.length === 0) {
            resultsDiv.innerHTML = '<p class="text-center text-muted">No data found for the selected range.</p>';
            return;
        }

        let html = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Klasifikasi Limbah</th>
                        <th>Diserahkan ke Pihak Ketiga</th>
                        <th>Tidak Dikelola</th>
                        <th>Dihasilkan</th>
                        <th>Disimpan</th>
                        <th>Dimanfaatkan Sendiri</th>
                        <th>Diolah Sendiri</th>
                        <th>Ditimbun Sendiri</th>
                    </tr>
                </thead>
                <tbody>
        `;

        data.forEach((row, index) => {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${row.jenis_limbah}</td>
                    <td>${row.diserahkan_ke_pihak_ketiga}</td>
                    <td>${row.tidak_dikelola}</td>
                    <td>${row.dihasilkan}</td>
                    <td>${row.disimpan}</td>
                    <td>${row.dimanfaatkan_sendiri}</td>
                    <td>${row.diolah_sendiri}</td>
                    <td>${row.ditimbun_sendiri}</td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
        `;

        resultsDiv.innerHTML = html;
    }
</script>




<script>
    $(document).ready(function () {
  $("#basic-datatables").DataTable({});

  $("#multi-filter-select1").DataTable({
    pageLength: 10,
    initComplete: function () {
      this.api()
        .columns()
        .every(function () {
          var column = this;
          var select = $(
            '<select class="form-select"><option value=""></option></select>'
          )
            .appendTo($(column.footer()).empty())
            .on("change", function () {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());

              column
                .search(val ? "^" + val + "$" : "", true, false)
                .draw();
            });

          column
            .data()
            .unique()
            .sort()
            .each(function (d, j) {
              select.append(
                '<option value="' + d + '">' + d + "</option>"
              );
            });
        });
    },
  });

  // Add Row
  $("#add-row").DataTable({
    pageLength: 3,
  });

  var action =
    '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

  $("#addRowButton").click(function () {
    $("#add-row")
      .dataTable()
      .fnAddData([
        $("#addName").val(),
        $("#addPosition").val(),
        $("#addOffice").val(),
        action,
      ]);
    $("#addRowModal").modal("hide");
  });
});
</script>

{{-- Script cetak PDF berdasarkan Tanggal --}}
<script>
    $(document).ready(function() {
    $('#generate-report').click(function() {
        var month = $('#month').val();
        var year = $('#year').val();

        if (month && year) {
            window.location.href = '/limbah/generate-pdf?month=' + month + '&year=' + year;
        } else {
            alert('Please select both month and year.');
        }
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const limbahInputs = document.getElementById('limbahInputs');

        // Handle changes to the limbah classification dropdown
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('id_klasifikasilimbah')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const row = e.target.closest('.limbah-row');
                const noteRow = row.querySelector('.note-konversi'); // Dynamic note
                const satuanSelect = row.querySelector('.satuan');
                const jumlahInput = row.querySelector('.jml_masuk');
                const kgInput = row.querySelector('.jml_kg');

                // Get data from the selected option
                const selectedJenis = selectedOption.textContent.trim();
                const selectedSatuan = selectedOption.getAttribute('data-satuan');
                const selectedKonversi = parseFloat(selectedOption.getAttribute('data-konversi')) || 1;

                // Reset the unit dropdown and add new options
                satuanSelect.innerHTML = '';
                const satuanOptions = [
                    { value: 'kg', text: 'kg' },
                    { value: selectedSatuan, text: selectedSatuan }
                ];
                satuanOptions.forEach(option => {
                    const newOption = document.createElement('option');
                    newOption.value = option.value;
                    newOption.textContent = option.text;
                    satuanSelect.appendChild(newOption);
                });

                // Perform conversion if quantity is already entered
                performConversion(row);

                // Update the dynamic note
                noteRow.textContent = `Jenis Limbah: ${selectedJenis}, konversi 1 ${selectedSatuan} yaitu ${selectedKonversi} kg`;
            }

            // Handle changes to the unit dropdown or quantity input
            if (e.target.classList.contains('satuan') || e.target.classList.contains('jml_masuk')) {
                const row = e.target.closest('.limbah-row');
                performConversion(row);
            }
        });

        // Function to perform the conversion to kg
        function performConversion(row) {
            const jumlahInput = row.querySelector('.jml_masuk');
            const satuanSelect = row.querySelector('.satuan');
            const kgInput = row.querySelector('.jml_kg');
            const jenisLimbahSelect = row.querySelector('.id_klasifikasilimbah');
            const selectedOption = jenisLimbahSelect.options[jenisLimbahSelect.selectedIndex];

            // Get data from the selected option
            const jumlah = parseFloat(jumlahInput.value) || 0;
            const konversi = parseFloat(selectedOption.getAttribute('data-konversi')) || 1;
            const selectedSatuan = satuanSelect.value; // Selected unit
            const satuanFromOption = selectedOption.getAttribute('data-satuan');

            // Perform the conversion
            let convertedKg = 0;
            if (selectedSatuan === 'kg') {
                convertedKg = jumlah; // If "kg" is selected, no conversion is needed
            } else if (selectedSatuan === satuanFromOption) {
                convertedKg = jumlah * konversi; // Convert using the specified conversion factor
            }

            kgInput.value = convertedKg.toFixed(2); // Display with 2 decimals
        }

        // Add a new row
        limbahInputs.addEventListener('click', function (e) {
            if (e.target.classList.contains('addLimbahRow')) {
                const row = e.target.closest('.limbah-row');
                const newRow = row.cloneNode(true);

                // Reset all inputs in the new row
                newRow.querySelectorAll('input, textarea, select').forEach(input => {
                    input.value = '';
                });

                // Reset conversion note
                const noteKonversi = newRow.querySelector('.note-konversi');
                if (noteKonversi) noteKonversi.textContent = '';

                // Tampilkan tombol hapus
                newRow.querySelector('.removeLimbahRow').style.display = 'inline-block';

                // Add the new row to the container
                limbahInputs.appendChild(newRow);
            }
        });

        // Remove a row
        limbahInputs.addEventListener('click', function (e) {
            if (e.target.classList.contains('removeLimbahRow')) {
                const row = e.target.closest('.limbah-row');
                row.remove();
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
  $("#basic-datatables").DataTable({});

  $("#multi-filter-select2").DataTable({
    pageLength: 10,
    initComplete: function () {
      this.api()
        .columns()
        .every(function () {
          var column = this;
          var select = $(
            '<select class="form-select"><option value=""></option></select>'
          )
            .appendTo($(column.footer()).empty())
            .on("change", function () {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());

              column
                .search(val ? "^" + val + "$" : "", true, false)
                .draw();
            });

          column
            .data()
            .unique()
            .sort()
            .each(function (d, j) {
              select.append(
                '<option value="' + d + '">' + d + "</option>"
              );
            });
        });
    },
  });

  // Add Row
  $("#add-row").DataTable({
    pageLength: 3,
  });

  var action =
    '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

  $("#addRowButton").click(function () {
    $("#add-row")
      .dataTable()
      .fnAddData([
        $("#addName").val(),
        $("#addPosition").val(),
        $("#addOffice").val(),
        action,
      ]);
    $("#addRowModal").modal("hide");
  });
});
</script>
@endsection