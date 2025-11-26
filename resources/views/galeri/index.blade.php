@extends('layout.index')
@section('title', 'Galeri')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Galeri</h3>
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
                    <a href="#">Galeri</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Input Foto</h4>
                    </div>
                    <div class="card-body">
                        <form action="/album" method="post" enctype="multipart/form-data" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="fileInput" class="form-label">Pilih Foto</label>
                                <input type="file" id="fileInput" name="photos[]" accept="image/*" class="form-control"
                                    multiple required>
                            </div>
                            <div id="previewContainer" class="row g-3 mb-3">
                                <!-- Preview images will appear here -->
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section for displaying uploaded images -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Uploaded Photos</h4>
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
                            <!-- Display uploaded photos from the database -->
                            @foreach(App\Models\Galeri::all() as $photo)
                            <div class="col">
                                <img src="{{ asset('img/galeri/' . $photo->filename) }}" alt="Uploaded Photo"
                                    class="img-fluid img-thumbnail shadow-sm photo-item" style="cursor:pointer;">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Modal -->
<div id="fullscreenModal" class="fullscreen-modal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.9); z-index: 1050; align-items: center; justify-content: center;">
    <button id="closeFullscreen"
        style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 2rem; color: white; cursor: pointer;">&times;</button>
    <img id="fullscreenImage" src="" alt="Fullscreen Photo" style="max-width: 90%; max-height: 90%;">
</div>

<script>
    // JavaScript for previewing selected images
    document.getElementById('fileInput').addEventListener('change', function (event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('previewContainer');
        previewContainer.innerHTML = ''; // Reset preview container

        Array.from(files).forEach((file) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const col = document.createElement('div');
                    col.classList.add('col');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-fluid', 'img-thumbnail', 'shadow-sm');

                    col.appendChild(img);
                    previewContainer.appendChild(col);
                };

                reader.readAsDataURL(file);
            }
        });
    });

    // JavaScript for opening images in fullscreen
    document.querySelectorAll('.photo-item').forEach(item => {
        item.addEventListener('click', function () {
            const fullscreenModal = document.getElementById('fullscreenModal');
            const fullscreenImage = document.getElementById('fullscreenImage');
            fullscreenImage.src = this.src;
            fullscreenModal.style.display = 'flex';
        });
    });

    // JavaScript for closing fullscreen modal
    document.getElementById('closeFullscreen').addEventListener('click', function () {
        document.getElementById('fullscreenModal').style.display = 'none';
    });

    // JavaScript for submitting the form
    document.getElementById('uploadForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('/galeri', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                location.reload(); // Reload to display updated gallery
            } else {
                alert('Upload failed');
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection