<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>@yield('title')</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{asset('img/semen_padang.png')}}" type="image/x-icon" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script> --}}
  <!-- Fonts and icons -->
  <script src="{{asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>

  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/css/multi-select-tag.css">
  <!-- Custom Stylesheet -->
  <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/js/multi-select-tag.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <style>
    .past-day {
      background-color: #f0f0f0;
      /* Warna abu-abu */
      color: #ccc;
      /* Warna teks abu-abu */
    }

    .pagi {
      background-color: #3c6471;
      color: #fff;
      /* Warna untuk shift pagi */
    }

    .siang {
      background-color: #ffd700;
      color: #fff;
      /* Warna untuk shift siang */
    }

    .malam {
      background-color: #ff7f50;
      color: #fff;
      /* Warna untuk shift malam */
    }
  </style>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
  <script>
    WebFont.load({
      google: { families: ["Public Sans:300,400,500,600,700"] },
      custom: {
        families: [
          "Font Awesome 5 Solid",
          "Font Awesome 5 Regular",
          "Font Awesome 5 Brands",
          "simple-line-icons",
        ],
        urls: ["{{asset('assets/css/fonts.min.css')}}"],
      },
      active: function () {
        sessionStorage.fonts = true;
      },
    });
  </script>

  <!-- CSS Files -->
  <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/plugins.min.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/kaiadmin.min.css')}}" />

  <!-- CSS Just for demo purpose, don't include it in your project -->
  {{--
  <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" /> --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
  <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
  <!-- Include Leaflet.markercluster CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
  <!-- Include Leaflet.markercluster JS -->
  <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
  <style>
    #map {
      height: 400px;
      width: 100%;
      /* Set the height of the map */
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
</head>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar" data-background-color="dark">
      <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
          <a href="/" class="logo">
            <img src="{{asset('img/semen_padang.png')}}" alt="navbar brand" class="navbar-brand" height="50" />
          </a>
          <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
              <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
              <i class="gg-menu-left"></i>
            </button>
          </div>
          <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
          </button>
        </div>
        <!-- End Logo Header -->
      </div>
      <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
          @if (Session::get('level')=='admin')
          <ul class="nav nav-secondary">
            <li class="nav-section">
              <span class="sidebar-mini-icon">
                <i class="fa fa-ellipsis-h"></i>
              </span>
              <h4 class="text-section">Features</h4>
            </li>


            {{-- <li class="nav-item {{ (request()->is('worker')) ? 'active' : '' }}">
              <a href="/worker">
                <i class="fas fa-user-cog"></i>
                <p>Worker</p>
              </a>
            </li> --}}
            {{-- @if (Session::get('level')=="admin")
            <li class="nav-item {{ (request()->is('show-qr')) ? 'active' : '' }}">
              <a href="/show-qr">
                <i class="fab fa-whatsapp"></i>
                <p>Login Whatsapp</p>
              </a>
            </li>
            @endif --}}

            <li class="nav-item {{ (request()->is('pengajuan')) ? 'active' : '' }}">
              <a href="/pengajuan">
                <i class="fas fa-file-export"></i>
                <p>Pengajuan</p>
              </a>
            </li>

            <li class="nav-item {{ (request()->is('limbah')) ? 'active' : '' }}">
              <a href="/limbah">
                <i class="fas fa-hockey-puck"></i>
                <p>Limbah</p>
              </a>
            </li>

            <li class="nav-item {{ (request()->is('galeri')) ? 'active' : '' }}">
              <a href="/galeri">
                <i class="fas fa-images"></i>
                <p>Galeri</p>
              </a>
            </li>

            <li class="nav-item {{ (request()->is('dokumen')) ? 'active' : '' }}">
              <a href="/dokumen">
                <i class="fas fa-file"></i>
                <p>Dokumen</p>
              </a>
            </li>

            <li class="nav-item {{ (request()->is('unit')) ? 'active' : '' }}">
              <a href="/unit">
                <i class="fas fa-building"></i>
                <p>Unit</p>
              </a>
            </li>

            <li class="nav-item {{ (request()->is('info.create')) ? 'active' : '' }}">
              <a href="/info/create">
                <i class="fas fa-info"></i>
                <p>Info</p>
              </a>
            </li>

          </ul>
          @elseif(Session::get('level')=='user')

          <ul class="nav nav-secondary">
            <li class="nav-section">
              <span class="sidebar-mini-icon">
                <i class="fa fa-ellipsis-h"></i>
              </span>
              <h4 class="text-section">Features</h4>
            </li>

            <li class="nav-item {{ (request()->is('dokumen.show')) ? 'active' : '' }}">
              <a href="/dokumen/{{Session::get('id')}}">
                <i class="fas fa-file"></i>
                <p>Dokumen</p>
              </a>
            </li>
          </ul>
          @endif
          <ul class="nav nav-secondary">
            <li class="nav-item {{ (request()->is('panduan')) ? 'active' : '' }}">
              <a href="/panduan">
                <i class="fas fa-book"></i>
                <p>Panduan</p>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- End Sidebar -->

    <div class="main-panel">
      <div class="main-header">
        <div class="main-header-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
              <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <!-- Navbar Header -->
        <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
          <div class="container-fluid">
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
              {{-- <div class="input-group">
                <div class="input-group-prepend">
                  <button type="submit" class="btn btn-search pe-1">
                    <i class="fa fa-search search-icon"></i>
                  </button>
                </div>
                <input type="text" placeholder="Search ..." class="form-control" />
              </div> --}}
            </nav>
            @if (Session::has('login'))
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
              <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                  <div class="avatar-sm">
                    <img src="{{asset('assets/img/profile.png')}}" alt="..." class="avatar-img rounded-circle" />
                  </div>
                  <span class="profile-username">
                    <span class="op-7">Hi,</span>
                    <span class="fw-bold">{{Session::get('name')}}</span>
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                  <div class="dropdown-user-scroll scrollbar-outer">
                    <li>
                      <div class="user-box">
                        <div class="avatar-lg">
                          <img src="{{asset('assets/img/profile.png')}}" alt="image profile"
                            class="avatar-img rounded" />
                        </div>
                        <div class="u-text">
                          <h4>{{Session::get('name')}}</h4>
                          <p class="text-muted">{{Session::get('email')}}</p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item" data-bs-toggle="modal"
                        data-bs-target="#updateUserModal">Account Setting</button>
                      <div class="dropdown-divider"></div>
                    </li>
                    <a class="dropdown-item" href="#">
                      <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                      </form>
                    </a>
                  </div>
                </ul>
              </li>
            </ul>
            @else
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
              <a href="/login">Login</a>&nbsp | &nbsp<a href="/daftar">Daftar</a>
            </ul>
            @endif
          </div>
        </nav>
        <!-- End Navbar -->
      </div>

      @yield('content')

      <!-- User Update Modal -->
      @if (Session::has('login'))
      @php
      $user = App\Models\User::find(Session::get('id'));
      @endphp
      <div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="/daftar/{{Session::get('id')}}" id="updateUserForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="updateUserModalLabel">Update User Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                  <label for="unit" class="form-label">Unit</label>
                  <input type="text" class="form-control" id="unit" name="unit" value="{{ $user->unit }}">
                </div>
                <div class="mb-3">
                  <label for="penanggung_jawab" class="form-label">Penanggung Jawab/ Ka. Unit</label>
                  <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab"
                    value="{{ $user->penanggung_jawab }}">
                </div>
                <div class="mb-3">
                  <label for="no_hp" class="form-label">No HP</label>
                  <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $user->no_hp }}">
                </div>



                <div class="form-group">
                  <label for="password" class="form-label">New Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="password" placeholder="Enter your password"
                      name="password" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">Show</button>
                  </div>
                  <div class="mt-2">
                    <span id="lengthBubble" class="bubble red"></span> At least 8 characters<br>
                    <span id="numberBubble" class="bubble red"></span> At least 1 number<br>
                    <span id="uppercaseBubble" class="bubble red"></span> At least 1 uppercase letter<br>
                    <span id="symbolBubble" class="bubble red"></span> At least 1 symbol<br>
                  </div>
                </div>

                <div class="form-group mb-3">
                  <label for="confirmPassword" class="form-label">Confirm Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm your password"
                      required>
                    <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">Show</button>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="submitButton" disabled>Update</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      @else

      @endif



      <footer class="footer">
        <div class="container-fluid d-flex justify-content-between">
          <nav class="pull-left">
            <ul class="nav">

            </ul>
          </nav>
          <div class="copyright text-primary">
            {{date('Y')}} - TPS Limbah B3 PT Semen Padang
          </div>
          <div>

          </div>
        </div>
      </footer>
    </div>

  </div>
  <script>
    function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form hapus data secara otomatis
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
  </script>
  <script>
    @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
  </script>
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
      const isPhoneValid = noHpInput.value.startsWith('08'); // Assuming this check for the phone number
  
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
  
    document.getElementById('updateUserForm').addEventListener('submit', (e) => {
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

  @include('sweetalert::alert')
  <!--   Core JS Files   -->
  <script src="{{asset('assets/js/core/jquery-3.7.1.min.js')}}"></script>
  <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>

  <!-- jQuery Scrollbar -->
  <script src="{{asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>

  <!-- Chart JS -->
  <script src="{{asset('assets/js/plugin/chart.js/chart.min.js')}}"></script>

  <!-- jQuery Sparkline -->
  <script src="{{asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js')}}"></script>

  <!-- Chart Circle -->
  <script src="{{asset('assets/js/plugin/chart-circle/circles.min.js')}}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
  </script>
  <!-- Datatables -->
  <script src="{{asset('assets/js/plugin/datatables/datatables.min.js')}}"></script>

  <!-- Bootstrap Notify -->
  <script src="{{asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

  <!-- jQuery Vector Maps -->
  <script src="{{asset('assets/js/plugin/jsvectormap/jsvectormap.min.js')}}"></script>
  <script src="{{asset('assets/js/plugin/jsvectormap/world.js')}}"></script>

  <!-- Sweet Alert -->
  <script src="{{asset('assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

  <!-- Kaiadmin JS -->
  <script src="{{asset('assets/js/kaiadmin.min.js')}}"></script>

  <script src="{{asset('assets/js/plugin/datatables/datatables.min.js')}}"></script>
  <!-- Kaiadmin DEMO methods, don't include it in your project! -->
  {{-- <script src="{{asset('assets/js/setting-demo.js')}}"></script>
  <script src="{{asset('assets/js/demo.js')}}"></script> --}}
  <script>
    $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#177dff",
    fillColor: "rgba(23, 125, 255, 0.14)",
  });

  $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#f3545d",
    fillColor: "rgba(243, 84, 93, .14)",
  });

  $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#ffa534",
    fillColor: "rgba(255, 165, 52, .14)",
  });
  </script>
  <script>
    $(document).ready(function () {
      $("#basic-datatables").DataTable({});

      $("#multi-filter-select").DataTable({
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
  @if(session('success'))
  <script>
    Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
  </script>
  @endif

  @if(session('error'))
  <script>
    Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false
        });
  </script>
  @endif
</body>

</html>