@extends('layout.index')

@section('title')
Login
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Login</h3>
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
                    <a href="/">Dashboard</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="/login">Login</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title">Login</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <form action="/login" method="POST">
                                @csrf
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="email2">Email Address</label>
                                        <input type="email" class="form-control" id="email2" placeholder="Enter Email"
                                            name="email" />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password"
                                                placeholder="Password" name="password" />
                                            <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                                                style="height: 100%;">Show</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    Belum memiliki akun TPS Limbah B3? Silahkan melakukan pendaftaran <a
                                        class="text-center" href="/daftar">Daftar</a>
                                </div>
                        </div>
                    </div>
                    <div class="card-action d-flex justify-content-end">
                        <button class="btn btn-success">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    // Menunggu sampai seluruh halaman dimuat sebelum menambahkan event listener
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        // Pastikan elemen ditemukan
        if (togglePassword && passwordField) {
            togglePassword.addEventListener('click', function() {
                // Toggle jenis input password
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;

                // Ubah teks tombol menjadi 'Show' atau 'Hide'
                this.textContent = type === 'password' ? 'Show' : 'Hide';
            });
        }
    });
</script>
@endsection