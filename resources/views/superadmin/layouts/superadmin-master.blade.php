<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SI-KUPA </title>

    <link rel="stylesheet" href="{{asset('assets/extensions/simple-datatables/style.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/extensions/sweetalert2/sweetalert2.min.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/compiled/css/app.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/compiled/css/app-dark.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/compiled/css/iconly.css')}}">

    <link rel="stylesheet" href="{{asset('assets/extensions/filepond/filepond.css')}}" />
    <link rel="stylesheet"
        href="{{asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/extensions/toastify-js/src/toastify.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/extensions/flatpickr/flatpickr.min.css')}}">

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

        @include('superadmin.layouts.sidebar')
        <div id="main">
            <header class="mb-1">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="#" class="burger-btn d-block d-xl-none">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                    <div class=""></div>
                    <a href="#" class="btn icon btn-sm" id="btn-fullscreen" onclick="toggleFullscreen()">
                        <i class="bi bi-fullscreen"></i>
                    </a>
                </div>
            </header>
            @yield('superadmin-master')

            <footer>
                {{-- <div class="footer clearfix mb-0 text-muted">

                </div> --}}
            </footer>
        </div>
    </div>

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

    <script src="{{asset('assets/compiled/js/app.js')}}"></script>

    <script src="{{asset('assets/extensions/jquery/jquery.min.js')}}"></script>

    <script src="{{asset('assets/extensions/simple-datatables/umd/simple-datatables.js')}}"></script>
    <script src="{{asset('assets/static/js/pages/simple-datatables.js')}}"></script>

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
