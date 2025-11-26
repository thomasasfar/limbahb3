@extends('layout.index')
@section('title')
Daftar
@endsection
@section('content')
<style>
    .bubble {
        display: inline-block;
        width: 15px;
        height: 15px;
        margin-right: 5px;
        border-radius: 50%;
    }

    .bubble.red {
        background-color: red;
    }

    .bubble.green {
        background-color: green;
    }
</style>
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Daftar</h3>
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
                    <a href="/login">Daftar</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title">Daftar</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <p>Silahkan buat akun TPS Limbah </p>
                            <form action="/daftar" method="POST" id="passwordForm" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="email2">Email</label>
                                        <input type="email" class="form-control" id="email2" placeholder="Masukan Email"
                                            name="email" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Nama</label>
                                        <input type="text" class="form-control" id="email2"
                                            placeholder="Masukan nama anda" name="name" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="email2">No.Hp</label>
                                        <input type="text" class="form-control" id="email2"
                                            placeholder="Masukan No.Whatsapp Anda" name="nohp_user" value="08"
                                            required />
                                    </div>
                                    <div class="form-group">
                                        <label for="unit_id">Nama Unit</label>
                                        <select class="form-control" id="unit_id" name="unit_id" required>
                                            <option value="">Pilih Unit</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->kode }} - {{ $unit->nama_unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Penanggung Jawab Unit</label>
                                        <input type="penanggung_jawab" class="form-control"
                                            placeholder="Nama Penanggung Jawab Unit" name="penanggung_jawab" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="no_hp" class="form-label">No.Hp Penanggung Jawab</label>
                                        <input type="text" class="form-control" id="no_hp"
                                            placeholder="No Hp Penanggung Jawab" name="no_hp" value="08" required>
                                        <small>Gunakan 08 sebagai prefix nomor Hp</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password"
                                                placeholder="Enter your password" name="password" required>
                                            <button type="button" class="btn btn-outline-secondary"
                                                id="togglePassword">Show</button>
                                        </div>
                                        <div class="mt-2">
                                            <span id="lengthBubble" class="bubble red"></span> At least 8 characters<br>
                                            <span id="numberBubble" class="bubble red"></span> At least 1 number<br>
                                            <span id="uppercaseBubble" class="bubble red"></span> At least 1 uppercase
                                            letter<br>
                                            <span id="symbolBubble" class="bubble red"></span> At least 1 symbol
                                            !@#$%^&*(),.?":{}|<>-!@#$%^&*(),.?":{}|<>-<br>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirmPassword"
                                                placeholder="Confirm your password" required>
                                            <button type="button" class="btn btn-outline-secondary"
                                                id="toggleConfirmPassword">Show</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    Sudah pernah mendaftarkan aku? Silahkan <a class="text-center"
                                        href="/daftar">Login</a>
                                </div>

                        </div>
                    </div>
                    <div class="card-action d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const noHpInput = document.getElementById('no_hp');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const togglePasswordButton = document.getElementById('togglePassword');
    const toggleConfirmPasswordButton = document.getElementById('toggleConfirmPassword');
    const submitButton = document.getElementById('submitButton');

    const lengthBubble = document.getElementById('lengthBubble');
    const numberBubble = document.getElementById('numberBubble');
    const uppercaseBubble = document.getElementById('uppercaseBubble');
    const symbolBubble = document.getElementById('symbolBubble');

    function validatePassword() {
        const password = passwordInput.value;
        const lengthValid = password.length >= 8;
        const numberValid = /\d/.test(password);
        const uppercaseValid = /[A-Z]/.test(password);
        const symbolValid = /[!@#$%^&*(),.?":{}|<>-]/.test(password);

        // Update bubble colors
        lengthBubble.className = `bubble ${lengthValid ? 'green' : 'red'}`;
        numberBubble.className = `bubble ${numberValid ? 'green' : 'red'}`;
        uppercaseBubble.className = `bubble ${uppercaseValid ? 'green' : 'red'}`;
        symbolBubble.className = `bubble ${symbolValid ? 'green' : 'red'}`;
    }

    function validateForm() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const noHp = noHpInput.value;

        // Validate phone number
        const isPhoneValid = noHp.startsWith('08');

        if (!isPhoneValid) {
            submitButton.disabled = true;
            return;
        }

        const lengthValid = password.length >= 8;
        const numberValid = /\d/.test(password);
        const uppercaseValid = /[A-Z]/.test(password);
        const symbolValid = /[!@#$%^&*(),.?":{}|<>-_+=]/.test(password);
        const passwordsMatch = password === confirmPassword;

        // Enable submit button if all conditions are met
        const allValid = lengthValid && numberValid && uppercaseValid && symbolValid && passwordsMatch && isPhoneValid;
        submitButton.disabled = !allValid;
    }

    // Add input listeners for real-time validation
    noHpInput.addEventListener('input', validateForm);
    passwordInput.addEventListener('input', () => {
        validatePassword();
        validateForm();
    });
    confirmPasswordInput.addEventListener('input', validateForm);

    document.getElementById('passwordForm').addEventListener('submit', (e) => {
    // Validasi nomor telepon
    if (!noHpInput.value.startsWith('08')) {
        e.preventDefault(); // Cegah pengiriman form
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Silahkan masukkan nomor dengan awalan 08!'
        });
        return;
    }

    // Validasi kesesuaian password
    if (passwordInput.value !== confirmPasswordInput.value) {
        e.preventDefault(); // Cegah pengiriman form
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Passwords do not match!'
        });
        return;
    }

    // Jika semua validasi berhasil, form akan terkirim secara normal tanpa memanggil e.preventDefault()
    });

    togglePasswordButton.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        togglePasswordButton.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    toggleConfirmPasswordButton.addEventListener('click', () => {
        const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
        confirmPasswordInput.type = type;
        toggleConfirmPasswordButton.textContent = type === 'password' ? 'Show' : 'Hide';
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const inputs = [document.getElementById('no_hp'), document.querySelector('input[name="nohp_user"]')];

    inputs.forEach(input => {
        input.addEventListener('input', () => {
            // Hapus semua karakter non-angka
            input.value = input.value.replace(/[^0-9]/g, '');

            // Jika ada karakter non-angka yang dimasukkan
            if (/[^0-9]/.test(input.value)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Silahkan masukkan hanya angka!'
                });
            }
        });

        input.addEventListener('keydown', (e) => {
            // Izinkan tombol navigasi, backspace, delete, tab, enter, dan angka
            if (
                e.key === 'Backspace' ||
                e.key === 'Delete' ||
                e.key === 'Tab' ||
                e.key === 'Enter' ||
                e.key === 'ArrowLeft' ||
                e.key === 'ArrowRight' ||
                /^[0-9]$/.test(e.key) // Hanya angka
            ) {
                return;
            }

            // Blokir input selain angka
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hanya angka yang diperbolehkan!'
            });
        });
    });
});
</script>
@endsection