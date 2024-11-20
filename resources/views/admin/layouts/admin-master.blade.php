<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mangony App</title>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Bootstrap CSS (Optional, if needed for styling) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Plugins -->
    <link rel="stylesheet" href="{{asset('assets/extensions/sweetalert2/sweetalert2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/extensions/filepond/filepond.css')}}" />
    <link rel="stylesheet"
        href="{{asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/extensions/toastify-js/src/toastify.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/extensions/flatpickr/flatpickr.min.css')}}">
    <!-- Styling -->
    <link rel="stylesheet" href="{{asset('assets/compiled/css/app.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/compiled/css/app-dark.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/compiled/css/iconly.css')}}">
    <style>
        
            /* Sidebar item with dropdown menu */
        .sidebar-item.dropdown {
            position: relative;
        }

        /* Initially, hide the dropdown */
        .sidebar-item .dropdown-menu {
            display: none;  /* Hide by default */
            background-color: #f8f9fa; /* Background color for the dropdown */
            border: 1px solid #ddd;
            padding: 10px 0;
            list-style: none;
            width: 100%; /* Make it the same width as the sidebar */
            margin-top: 5px; /* Slight space between menu and parent */
        }

        /* Show the dropdown when hovering over the parent item */
        .sidebar-item.dropdown:hover .dropdown-menu {
            display: block;  /* Make the dropdown visible */
        }

        /* Optional: Add hover effect for links inside the dropdown */
        .sidebar-item .dropdown-menu li a:hover {
            background-color: #f1f1f1;
        }

        /* Ensure the sidebar layout adjusts for the dropdown */
        .sidebar-menu .menu {
            display: block;
            transition: height 0.3s ease; /* Smooth transition */
        }

        .sidebar-item .dropdown-menu {
            display: block;
            margin-left: 0; /* Align to the left */
        }
        body[data-bs-theme="light"] .sidebar-item .dropdown-menu {
            background-color: #f8f9fa; /* Light background for dropdown */
            color: #000; /* Dark text */
            border: 1px solid #ddd;
        }

        body[data-bs-theme="dark"] .sidebar-item .dropdown-menu {
            background-color: #343a40; /* Dark background for dropdown */
            color: #fff; /* Light text */
            border: 1px solid #454d55;
        }

        body[data-bs-theme="dark"] .sidebar-item .dropdown-menu li a:hover {
            background-color: #565e68; /* Subtle hover effect in dark mode */
            color: #fff;
        }

    </style>

    

    <meta name="csrf-token" content="{{csrf_token()}}" />

    <style>
        /* Hide scrollbar track */
        ::-webkit-scrollbar {
            width: 0.5em;
        }

        /* Hide scrollbar handle */
        ::-webkit-scrollbar-thumb {
            background-color: transparent;
        }

        #div-container-zakatmaal,
        #div-container-zakatfitrah,
        #div-container-zakatpenghasilan {
            display: none;
        }

        #input-container-zakatmaal.show,
        #input-container-zakatfitrah.show,
        #input-container-zakatpenghasilan.show {
            display: block;
        }
    </style>

</head>

<body>

    <script src="{{asset('assets/static/js/initTheme.js')}}"></script>

    <div id="spinner" class="spinner-border text-primary" style="
                width: 3rem;
                height: 3rem;
                position: fixed;
                top: 50%;
                left: 50%;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                visibility: hidden;
            " role="status">
    </div>
    <div id="app" style="display:none;">

        {{-- SIDEBAR --}}

        @include('admin.layouts.sidebar')

        {{-- END OF SIDEBAR --}}

        <div id="main">
            <header class="mb-1">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="#" class="burger-btn d-block d-xl-none">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                    <h6 class="text-end" id="realtime-clock"></h6>
                    <a href="#" class="btn icon btn-sm" id="btn-fullscreen" onclick="toggleFullscreen()">
                        <i class="bi bi-fullscreen"></i>
                    </a>
                </div>
            </header>
            @yield('admin-master')

            <footer>
                {{-- <div class="footer clearfix mb-0 text-muted">

                </div> --}}
            </footer>
        </div>
    </div>

    <!-- <script src="{{asset('assets/extensions/jquery/jquery.min.js')}}"></script> -->
    <script>
        function updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayOfWeek = days[now.getDay()];
            const date = now.getDate().toString().padStart(2, '0');
            const month = (now.getMonth() + 1).toString().padStart(2, '0'); // Bulan dimulai dari 0, jadi tambahkan 1
            const year = now.getFullYear();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');

            const formattedTime = `${dayOfWeek} | ${date}-${month}-${year} , ${hours}:${minutes}:${seconds}`;

            const clockElement = document.getElementById('realtime-clock');
            clockElement.textContent = formattedTime;
        }

        // Panggil updateClock setiap detik
        setInterval(updateClock, 1000);

        // Panggil updateClock untuk pertama kali saat halaman dimuat
        updateClock();
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
                var spinner = document.getElementById("spinner");
                var websiteContent = document.getElementById("app");

                // Tampilkan spinner saat halaman dimuat
                spinner.style.visibility = "visible";

                // Setelah 5 detik, sembunyikan spinner dan tampilkan konten halaman
                setTimeout(function () {
                    spinner.style.visibility = "hidden";
                    websiteContent.style.display = "block";
                }, 250); // 5000 milidetik = 5 detik
            });
    </script>
    

    <script src="{{asset('assets/static/js/components/dark.js')}}"></script>
    <script src="{{asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>

    <!-- <script src="{{asset('assets/compiled/js/app.js')}}"></script> -->

    

    <!-- <script src="{{asset('assets/extensions/simple-datatables/umd/simple-datatables.js')}}"></script>
    <script src="{{asset('assets/static/js/pages/simple-datatables.js')}}"></script> -->

    <script src="{{asset('assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>

    <script
        src="{{asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js')}}">
    </script>
    <script
        src="{{asset('assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js')}}">
    </script>
    <script src="{{asset('assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js')}}"></script>
    <script
        src="{{asset('assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js')}}">
    </script>
    <script src="{{asset('assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js')}}">
    </script>
    <script src="{{asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js')}}">
    </script>
    <script src="{{asset('assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js')}}">
    </script>
    <script src="{{asset('assets/extensions/filepond/filepond.js')}}"></script>
    <script src="{{asset('assets/extensions/toastify-js/src/toastify.js')}}"></script>
    <script src="{{asset('assets/static/js/pages/filepond.js')}}"></script>

    <script src="{{asset('assets/extensions/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('assets/static/js/pages/date-picker.js')}}"></script>

    @if(session()->has('error') || session()->has('success'))
    <script>
        @if(session()->has('error'))
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "{{ session('error') }}"
            });
            @elseif(session()->has('success'))
            Swal.fire({
                icon: "success",
                title: "Success",
                text: "{{ session('success') }}"
            });
            @endif
    </script>
    @endif

    <script>
        function toggleFullscreen() {
            const elem = document.documentElement;
            const btn = document.getElementById('btn-fullscreen');
            const textSpan = document.getElementById('fullscreen-text');
            if (!document.fullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.mozRequestFullScreen) { // Firefox
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) { // Chrome, Safari, and Opera
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { // IE/Edge
                    elem.msRequestFullscreen();
                }
                btn.innerHTML = '<i class="bi bi-fullscreen-exit"></i>'; // Mengubah ikon tombol
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) { // Firefox
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) { // Chrome, Safari, and Opera
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { // IE/Edge
                    document.msExitFullscreen();
                }
                btn.innerHTML = '<i class="bi bi-fullscreen"></i>'; // Mengubah ikon tombol kembali
            }
        }
    </script>

</body>
</html>