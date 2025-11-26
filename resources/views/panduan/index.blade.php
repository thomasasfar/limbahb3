@extends('layout.index')
@section('title', 'Galeri')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Panduan</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="/">
                        <i class="icon-home">Dashboard</i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="/panduan">Panduan</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Panduan</h4>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Section for displaying uploaded images -->
    </div>
</div>

<!-- Fullscreen Modal -->
<div id="fullscreenModal" class="fullscreen-modal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.9); z-index: 1050; align-items: center; justify-content: center;">
    <button id="closeFullscreen"
        style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 2rem; color: white; cursor: pointer;">&times;</button>
    <img id="fullscreenImage" src="" alt="Fullscreen Photo" style="max-width: 90%; max-height: 90%;">
</div>

@endsection