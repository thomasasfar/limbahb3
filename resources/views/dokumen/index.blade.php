@extends('layout.index')
@section('title', 'Dokumen')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dokumen</h3>
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
                    <a href="#">Dokumen</a>
                </li>
            </ul>
        </div>

        <!-- Folder and Document List -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Dokumen</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="pdf_file">Unggah Dokumen PDF:</label>
                                <input class="form-control" type="file" id="pdf_file" name="pdf_file"
                                    accept="application/pdf" required>

                            </div>
                            <div class="form-group">
                                <label for="folder">Pilih Folder Penyimpanan:</label>
                                <select id="folder" name="folder" required>
                                    <option value="documents">Documents</option>
                                    <option value="uploads">Uploads</option>
                                    <option value="reports">Reports</option>
                                    <!-- Tambahkan folder lain sesuai kebutuhan -->
                                </select>
                            </div>
                            <button class="btn btn-primary" type="submit">Unggah</button>
                        </form>

                        <ul class="list-group mt-3">

                            <li class="list-group-item">
                                <span class="folder-toggle" data-folder-id="1" style="cursor: pointer;">
                                    <i class="icon-folder mr-2"></i>&nbspLimbah MAsuk
                                </span>
                                @foreach ($aktivitas_masuk as $item)

                                @endforeach
                                <ul class="list-group mt-2" id="documents-1" style="display: none;">

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ asset('storage/documents/')}}" target="_blank">icandnnd</a>
                                        <i class="icon-file"></i>
                                    </li>

                                </ul>
                            </li>

                            <li class="list-group-item">
                                <span class="folder-toggle" data-folder-id="2" style="cursor: pointer;">
                                    <i class="icon-folder mr-2"></i>&nbsp Limbah Keluar
                                </span>
                                <ul class="list-group mt-2" id="documents-2" style="display: none;">

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ asset('storage/documents/')}}" target="_blank">icandnnd</a>
                                        <i class="icon-file"></i>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ asset('storage/documents/')}}" target="_blank">icandnnd</a>
                                        <i class="icon-file"></i>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ asset('storage/documents/')}}" target="_blank">icandnnd</a>
                                        <i class="icon-file"></i>
                                    </li>

                                </ul>
                                <ul class="list-group mt-2" id="documents-2" style="display: none;">

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ asset('storage/documents/')}}" target="_blank">icandnnd</a>
                                        <i class="icon-file"></i>
                                    </li>

                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.folder-toggle').forEach(folderToggle => {
        folderToggle.addEventListener('click', function () {
            const folderId = this.getAttribute('data-folder-id');
            const documentList = document.getElementById(`documents-${folderId}`);

            if (documentList.style.display === 'none') {
                documentList.style.display = 'block';
            } else {
                documentList.style.display = 'none';
            }
        });
    });
</script>
@endsection