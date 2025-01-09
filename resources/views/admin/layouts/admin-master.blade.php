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

        /* Prevent dropdown overflow issues */
        .sidebar-item {
            overflow: visible; /* Allow dropdown to appear outside parent */
        }

            /* Sidebar item with dropdown menu */
        .sidebar-item.dropdown {
            position: relative;
        }

        /* Initially, hide the dropdown */
        .sidebar-item .dropdown-menu {
            display: none; /* Hidden by default */
            position: absolute; /* Ensures it's independent of the layout flow */
            z-index: 1000; /* Stays above other elements */
            margin-top: 5px; /* Creates a small gap between menu and parent */
            width: 200px; /* Adjust width as needed */
        }

        /* Show the dropdown when hovering over the parent item */
        .sidebar-item.dropdown:hover .dropdown-menu {
            display: block; /* Make dropdown visible */
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
        /* Ensure body doesn't overflow */
        body {
            overflow-x: hidden;
        }
        /* Adjust for light mode */
        body[data-bs-theme="light"] .sidebar-item .dropdown-menu {
            background-color: #f2f6ff; /* Light background */
            color: #000; /* Dark text */
            border: 1px solid #ddd;
        }
        /* Adjust for dark mode */
        body[data-bs-theme="dark"] .sidebar-item .dropdown-menu {
            background-color: #343a40; /* Dark background */
            color: #fff; /* Light text */
            border: 1px solid #454d55;
        }
        /* Ensure dropdown hover background in light mode */
        body[data-bs-theme="light"] .sidebar-item .dropdown-menu li a:hover {
            background-color: #f2f6ff;
        }
        body[data-bs-theme="dark"] .sidebar-item .dropdown-menu li a:hover {
            background-color: #565e68; /* Subtle hover effect in dark mode */
            color: #fff;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
        /* Ensure cards and tables are responsive */
        .card {
            max-width: 100%;
            overflow-x: auto; /* Ensure wide content scrolls instead of breaking layout */
            box-sizing: border-box; /* Include padding in width calculations */
            margin: 0 auto; /* Center card content */
        }

        .card table {
            width: 100%; /* Ensure table adapts to parent width */
            table-layout: auto; /* Allow flexible column resizing */
            word-wrap: break-word; /* Handle long text in table cells */
        }

        table {
            border-collapse: collapse; /* Cleaner table borders */
            word-wrap: break-word; /* Break long text within cells */
            max-width: 100%; /* Prevent table from exceeding container width */
        }
        @media (max-width: 768px) {
            .card {
                padding: 1rem; /* Smaller padding for smaller screens */
            }

            .card table {
                font-size: 0.85rem; /* Adjust font size for better fit */
            }
        }
        /* Sidebar default (hidden) */
        .sidebar {
            display: none; /* Hidden by default */
            position: fixed; /* Fixed position for better behavior on toggle */
            width: 250px; /* Adjust as needed */
            height: 100%; /* Full height */
            background-color: var(--bs-body-bg); /* Match body background */
            z-index: 1050; /* Stay above other elements */
            overflow-y: auto; /* Allow scrolling if needed */
            transition: transform 0.3s ease; /* Smooth toggle effect */
            transform: translateX(-100%); /* Hidden off-screen */
        }

        /* Sidebar visible */
        .sidebar.active {
            display: block;
            transform: translateX(0); /* Slide into view */
        }

        /* For mobile: Full-width sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
            }
        }

        /* Prevent overflow in light and dark modes */
        body[data-bs-theme="light"] .card,
        body[data-bs-theme="dark"] .card {
            background-color: inherit; /* Match the theme's card background */
            color: inherit; /* Match the theme's text color */
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
    <script src="{{asset('assets/static/js/components/dark.js')}}"></script>
    <script src="{{asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    {{-- <script src="{{asset('assets/compiled/js/app.js')}}"></script> --}}
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
