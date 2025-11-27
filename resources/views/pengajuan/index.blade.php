@extends('layout.index')
@section('title')
Dashboard
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
        <!-- Profil Section -->
        <div class="row">
            <!-- Dashboard Content Placeholder -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pengajuan Masuk Limbah</h4>
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
                                    @foreach ($aktivitas as $index => $limbah)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{
                                            \Carbon\Carbon::parse($limbah->tanggal)->translatedFormat('l, d F
                                            Y') }}</td>
                                        <td>
                                            @if ($limbah->status==0)
                                            Pengajuan
                                            @endif
                                        </td>
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
                                                                            <th>Jenis Limbah</th>
                                                                            <th>Kode Limbah</th>
                                                                            <th>Tanggal Masuk</th>
                                                                            <th>Yang Mengajukan</th>
                                                                            <th>Jumlah Masuk</th>
                                                                            <th>Keterangan</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                        $aktivitas_masuk =
                                                                        App\Models\AktivitasMasukLimbah::where('id_aktivitaslimbah',$limbah->id)->get();
                                                                        @endphp
                                                                        @foreach ($aktivitas_masuk as $index => $lmbh)
                                                                        <tr>
                                                                            <td>{{ $index + 1 }}</td>
                                                                            <td>{{
                                                                                $lmbh->klasifikasi_limbah->jenis_limbah
                                                                                }}</td>
                                                                            <td>{{
                                                                                $lmbh->klasifikasi_limbah->kode_limbah
                                                                                }}</td>
                                                                            <td>{{ $lmbh->tgl_masuk }}</td>
                                                                            <td>{{ $lmbh->user->unit->nama_unit ?? 'Tidak Diketahui' }}</td>
                                                                            <td>{{ $lmbh->jml_masuk }} {{
                                                                                $lmbh->satuan }}</td>
                                                                            <td></td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <a href="/setujui_pengajuan/{{$limbah->id}}" type="button"
                                                                class="btn btn-success">Setujui Pengajuan</a>
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
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Dashboard Content Placeholder -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Menunggu Pengantaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="multi-filter-select1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menunggu as $index => $limbah)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{
                                            \Carbon\Carbon::parse($limbah->tanggal)->translatedFormat('l, d F
                                            Y') }}</td>
                                        <td>
                                            @if ($limbah->status==0)
                                            Menunggu Diantarkan
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#detail1{{$index}}">
                                                Detail
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="detail1{{$index}}" data-bs-backdrop="static"
                                                data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Detail
                                                                Pengajuan</h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table id="multi-filter-select"
                                                                class="table table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Jenis Limbah</th>
                                                                        <th>Kode Limbah</th>
                                                                        <th>Tanggal Masuk</th>
                                                                        <th>Yang Mengajukan</th>
                                                                        <th>Jumlah Masuk</th>
                                                                        <th>Keterangan</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                    $aktivitas_masuk =
                                                                    App\Models\AktivitasMasukLimbah::where('id_aktivitaslimbah',
                                                                    $limbah->id)->get();
                                                                    @endphp

                                                                    <form action="/terima_pengantaran/{{$limbah->id}}"
                                                                        method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @foreach ($aktivitas_masuk as $index => $lmbh)
                                                                        <tr>
                                                                            <td>{{ $index + 1 }}</td>
                                                                            <td>
                                                                                <select name="id_aktivitasmasuk[]"
                                                                                    class="form-select" readonly>
                                                                                    @foreach ($klasifikasi_limbah as $l)
                                                                                    <option value="{{ $lmbh->id }}" {{
                                                                                        $l->
                                                                                        id ==
                                                                                        $lmbh->id_klasifikasilimbah ?
                                                                                        'selected' : '' }}>
                                                                                        {{ $l->jenis_limbah }}
                                                                                    </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select name="kode_limbah[]"
                                                                                    class="form-select" disabled>
                                                                                    @foreach ($klasifikasi_limbah as $l)
                                                                                    <option value="{{ $l->id }}" {{ $l->
                                                                                        id ==
                                                                                        $lmbh->id_klasifikasilimbah ?
                                                                                        'selected' : '' }}>
                                                                                        {{ $l->kode_limbah }}
                                                                                    </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>{{ $lmbh->tgl_masuk }}</td>
                                                                            <td>{{ $lmbh->user->unit->nama_unit ?? 'Tidak diketahui' }}</td>
                                                                            <td>
                                                                                <input type="number" name="jml_masuk[]"
                                                                                    class="form-control"
                                                                                    value="{{ $lmbh->jml_masuk }}"> Kg
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="keterangan[]"
                                                                                    class="form-control"
                                                                                    placeholder="Keterangan (Opsional)">
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="">Nama Personil
                                                                Penghasil</label>
                                                            <input class="form-control" type="text" name="penghasil"
                                                                placeholder="Inputkan nama Personil Penghasil">
                                                        </div>

                                                        <button type="submit" class="btn btn-success mx-3">Terima
                                                            Pengantaran</button>
                                                        </form>

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
                </div>
            </div>
        </div>
    </div>
</div>
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

@endsection