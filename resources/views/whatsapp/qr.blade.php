@extends('layout.index')
@section('title')
Login Whatsapp
@endsection
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Login Whatsapp</h3>
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
                    <a href="#">Show QR Code</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title">Show QR Code</h4>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="px-2 bg-light ">
                            <!-- Jika belum login, tampilkan QR code -->
                            @if(!$isAuthenticated)
                            <p>Scan QR Code di bawah ini untuk masuk ke WhatsApp</p>
                            @if($qrCodeImageUrl)
                            <img src="{{ $qrCodeImageUrl }}" alt="QR Code Login WhatsApp">
                            @else
                            <p>QR code tidak tersedia. Silakan coba lagi.</p>
                            @endif

                            <!-- Jika sudah login, tampilkan pesan berhasil -->
                            @else
                            <p>Anda sudah login ke WhatsApp</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Tampilkan notifikasi berhasil login menggunakan SweetAlert
        Swal.fire({
            title: 'Berhasil!',
            text: 'Anda telah login ke WhatsApp.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
</script>


<!-- Auto refresh halaman setiap 10 detik untuk cek status login -->
<script>
    setInterval(function() {
        location.reload(); // Reload halaman setiap 10 detik
    }, 10000);
</script>
@endsection