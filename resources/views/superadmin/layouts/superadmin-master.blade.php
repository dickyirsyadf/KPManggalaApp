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
    <link rel="stylesheet" href="{{asset('assets/extensions/toastify-js/src/toastify.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/extensions/flatpickr/flatpickr.min.css')}}">
    <!-- Styling -->
    <link rel="stylesheet" href="{{asset('assets/compiled/css/addons.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/compiled/css/app.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/compiled/css/app-dark.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/compiled/css/iconly.css')}}">
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
    </style>

</head>

<body>

    <div id="spinner" class="spinner-border text-primary" role="status">
    </div>
    <div id="app" style="display:none;">

        {{-- SIDEBAR --}}

        @include('superadmin.layouts.sidebar')

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
            {{-- CONTENT --}}
            @yield('admin-master')
            {{-- END CONTENT --}}
            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <p>2025 &copy; Mangony App</p>
                </div>
            </footer>
        </div>
    </div>

    {{-- Theme --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const adjustCardWidths = () => {
                const cards = document.querySelectorAll('.card');
                cards.forEach(card => {
                    card.style.width = '100%'; // Reset width for accurate recalculation
                });
            };

            // Call on load and attach to resize event
            adjustCardWidths();
            window.addEventListener('resize', adjustCardWidths);
        });
        document.addEventListener("DOMContentLoaded", function () {
            // Select all dropdown menus
            const dropdownMenus = document.querySelectorAll(".sidebar-item .dropdown-menu");

            // Ensure dropdowns are hidden on page load
            dropdownMenus.forEach(menu => {
                menu.style.display = "none";
            });

            // Add hover event listeners for better handling
            const dropdownItems = document.querySelectorAll(".sidebar-item.dropdown");

            dropdownItems.forEach(item => {
                item.addEventListener("mouseenter", () => {
                    const menu = item.querySelector(".dropdown-menu");
                    if (menu) menu.style.display = "block";
                });

                item.addEventListener("mouseleave", () => {
                    const menu = item.querySelector(".dropdown-menu");
                    if (menu) menu.style.display = "none";
                });
            });
        });
        // Sidebar toggle for mobile
        document.addEventListener("DOMContentLoaded", function () {
            const burgerBtn = document.querySelector(".burger-btn"); // Ensure this matches your button class
            const sidebar = document.querySelector(".sidebar"); // Ensure this matches your sidebar class

            if (burgerBtn && sidebar) {
                burgerBtn.addEventListener("click", () => {
                    sidebar.classList.toggle("active");
                });
            }
        });
        // Debounce resize for performance
        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }
        const handleResize = debounce(() => {
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.style.width = '100%';
            });
        }, 200);

        window.addEventListener('resize', handleResize);

        // Trigger on initial load
        handleResize();

    </script>
    {{-- Clock --}}
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
    {{-- Spinner Loading --}}
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
    {{-- Assets --}}
    <script src="{{asset('assets/static/js/initTheme.js')}}"></script>
    <script src="{{asset('assets/static/js/components/dark.js')}}"></script>
    <script src="{{asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('assets/compiled/js/app.js')}}"></script>
    <script src="{{asset('assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('assets/extensions/toastify-js/src/toastify.js')}}"></script>
    <script src="{{asset('assets/extensions/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('assets/static/js/pages/date-picker.js')}}"></script>
    {{-- SWAL --}}
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
    {{-- Fullscreen --}}
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
