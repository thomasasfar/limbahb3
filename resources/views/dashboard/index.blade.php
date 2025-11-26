@extends('layout.index')
@section('title')
Dashboard | Website TPS Limbah B3 PT Semen Padang
@endsection
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dashboard</h3>
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
                    <a href="#">Dashboard</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
            </ul>
        </div>
        <div class="mb-3">
            <div id="carouselExampleCaptions" class="carousel slide">
                <div class="carousel-indicators">
                    @foreach ($infos as $index => $i)
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $index }}"
                        class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                        aria-label="{{ $index  }}">
                    </button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach ($infos as $index=>$i)
                    <div class="carousel-item {{$index == 0 ? 'active':''}}">
                        <img src="{{asset('img/info/'.$i->image)}}" class="d-block w-100" style="max-height:300px;"
                            alt="{{$i->title}}">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>{{$i->title}}</h5>
                            <p>{{$i->content}}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <!-- Profil Section -->
        <div class="row">

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="profile-picture mb-3">
                            <img src="{{asset('assets/img/profile.png')}}" class="rounded-circle img-thumbnail w-10"
                                alt="Profile">
                        </div>
                        <h4 class="fw-bold">{{ Session::get('unit') }}</h4>
                        <p class="text-muted mb-2">Email: {{ Session::get('email') }}</p>
                        <p class="text-muted">Level: {{ ucfirst(Session::get('level')) }}</p>
                        @if (Session::get('level')=="user")
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#limbahModal">Ajukan
                            Penyerahan Limbah</button>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Dashboard Content Placeholder -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        @if (Session::has('login'))
                        <h4 class="card-title">Selamat Datang, {{ Session::get('name') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="multi-filter-select" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($aktivitas_limbah as $index => $limbah)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $limbah->tanggal }}</td>
                                        <td>{{$limbah->aktivitas}}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#detail{{$index}}">
                                                Detail
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="detail{{$index}}" data-bs-backdrop="static"
                                                data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Detail
                                                                Pengajuan</h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="table-responsive">
                                                                <table id="multi-filter-select"
                                                                    class="table table-bordered table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No</th>
                                                                            <th>Jenis Limbah B3</th>
                                                                            <th>Jumlah Masuk</th>
                                                                            <th>Keterangan</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                        $aktivitas_masuk =
                                                                        App\Models\AktivitasMasukLimbah::where('id_aktivitaslimbah',$limbah->id)->get();
                                                                        @endphp
                                                                        @foreach ($aktivitas_masuk as $index =>
                                                                        $m)
                                                                        <tr>
                                                                            <td>{{ $index + 1 }}</td>
                                                                            <td>{{ $m->klasifikasi_limbah->jenis_limbah
                                                                                }}</td>
                                                                            <td>{{$m->jml_masuk}} kg</td>
                                                                            <td>{{$m->keterangan}}</td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
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
                    @else
                    <h4 class="card-title">Anda belum melakukan Login akun.</h4>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>

@if (Session::has('login'))
<!-- Modal Pengajuan Penyerahan Limbah -->
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
@else
<div class="modal fade" id="limbahModal" tabindex="-1" aria-labelledby="limbahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="limbahModalLabel">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Anda belum melakukan login akun user. Silahkan login terlebih dahulu
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary">Close</button>
                <a href="/login" type="button" class="btn btn-primary" data-bs-dismiss="modal">Login</a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Include Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
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


@endsection