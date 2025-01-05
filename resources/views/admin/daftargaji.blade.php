@extends('admin.layouts.admin-master')
@section('admin-master')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a>Master Data</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{$menu}}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn icon icon-left btn-light block" data-bs-toggle="modal"
                    data-bs-target="#modal-form">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-edit">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Tambah {{$menu}}
                </button>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="gajiTable">
                    <thead>
                        <tr>
                            <th>ID Karyawan</th>
                            <th>Nama</th>
                            <th>Bagian</th>
                            <th>Jumlah Hadir</th>
                            <th>Gaji Per Hari</th>
                            <th>Absen</th>
                            <th>Bonus</th>
                            <th>Gaji Bersih</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah -->
<div class="modal fade text-left modal-borderless" id="modal-form" tabindex="-1" role="dialog"
    aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah {{$menu}}</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('daftargaji.store') }}" method="POST">
                    @csrf
                    <!-- Dropdown for Nama Karyawan -->
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <select id="nama" name="id_karyawan" class="form-control" onchange="updateFields()">
                                <option value="" disabled selected>Pilih Nama Karyawan</option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}" data-nama="{{ $karyawan->nama }} "
                                        data-bagian="{{ $karyawan->hakAkses->hakakses }}">
                                        {{ $karyawan->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-control-icon">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Hidden Input for Nama -->
                    <input type="hidden" id="hidden-nama" name="nama" value="">

                    <!-- Auto-filled Bagian -->
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="bagian" name="bagian" type="text" placeholder="Bagian" class="form-control"
                                autocomplete="off" readonly />
                            <div class="form-control-icon">
                                <i class="bi bi-card-list"></i>
                            </div>
                        </div>
                    </div>

                    <!-- User Input Fields -->
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="gaji_perhari" name="gaji_perhari" type="number" placeholder="Gaji Perhari"
                                class="form-control" autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-plus-slash-minus"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Inputs for jumlah_hadir and absen -->
                    <input type="hidden" name="jumlah_hadir" value="0">
                    <input type="hidden" name="absen" value="0">
                    <input type="hidden" name="gaji_bersih" value="0">
                    <input type="hidden" name="bonus" value="0">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" id="submit-btn" class="btn btn-primary ms-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Tambah {{$menu}} Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete-->
<div class="modal fade text-left modal-borderless modal-md" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="deleteModalLabel">Delete Item</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade text-left modal-borderless modal-md" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-form-edit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h3>Edit Gaji</h3>
                    {{-- <p class="text-subtitle text-muted">
                        Tambahkan keterangan setiap mengubah data, agar memudahkan pembukuan
                    </p> --}}
                </div>
            </div>
            <form method="POST" action="{{ route('daftargaji.update') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="ID Karyawan" id="id_karyawan"
                                name="id_karyawan" required autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-file-earmark-binary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Nama Karyawan" name="nama_karyawan" id="nama_karyawan" required
                                autocomplete="default">
                            <div class="form-control-icon">
                                <i class="bi bi-basket-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="bagian" id="fbagian" name="fbagian" required
                                autocomplete="default">
                            <div class="form-control-icon">
                                <i class="bi bi-card-list"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Gaji Perhari" id="gaji_perhari" name="gaji_perhari" required
                                autocomplete="default">
                            <div class="form-control-icon">
                                <i class="bi bi-cash"></i>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="jumlah_hadir" value="0">
                    <input type="hidden" name="absen" value="0">
                    <input type="hidden" name="gaji_bersih" value="0">
                    <input type="hidden" name="bonus" value="0">
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        Selesai Edit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Mengubah format mata uang rupiah  -->
<script>
    function formatRupiah(amount) {
    if (!amount) return 'Rp 0'; // Handle empty or null values
    return 'Rp ' + parseInt(amount, 10).toLocaleString('id-ID', { minimumFractionDigits: 0 });
    }
</script>
<!-- initialisasi DT -->
<script>
       $(document).ready(function () {
        $('#gajiTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('daftargaji.data') }}",
            columns: [
                { data: 'id_karyawan', name: 'id_karyawan' },
                { data: 'nama', name: 'nama' },
                { data: 'bagian', name: 'bagian' },
                { data: 'jumlah_hadir', name: 'jumlah_hadir' },
                { data: 'gaji_perhari', name: 'gaji_perhari' },
                { data: 'absen', name: 'absen' },
                { data: 'bonus', name: 'bonus' },
                { data: 'gaji_bersih', name: 'gaji_bersih' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-sm btn-primary edit-btn"
                                    data-bs-toggle="modal" data-bs-target="#modal-form-edit"
                                    data-id="${row.id_karyawan}"
                                    data-nama="${row.nama}"
                                    data-bagian="${row.bagian}"
                                    data-gajiharian="${row.gaji_perhari}"
                                    data-bonus="${row.bonus}">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn"
                                    data-id="${row.id}" data-bs-toggle="modal"
                                    data-bs-target="#modal-delete">
                                Delete
                            </button>
                        `;
                    },
                },
            ],
            pageLength: 10, // Set number of rows per page
            lengthChange: false, // Optional: hide the page length dropdown
            paging: true, // Ensure pagination is enabled
            info: true, // Display info like "Showing X to Y of Z entries"
        });
    });
</script>
{{-- Update Bagian --}}
<script>
    function updateFields() {
        const select = document.getElementById("nama");
        const selectedOption = select.options[select.selectedIndex];

        // Update the hidden input for 'nama'
        const nama = selectedOption.getAttribute("data-nama");
        document.getElementById("hidden-nama").value = nama;

        // Update the 'bagian' field
        const bagian = selectedOption.getAttribute("data-bagian");
        document.getElementById("bagian").value = bagian;
    }

</script>

<!-- passing data dari btn ke modal  -->
<script>
    $(document).on('click', '.edit-btn', function () {
        // Retrieve data from the button
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const bagian = $(this).data('bagian');
        const bonus = $(this).data('bonus');
        const gajiharian = $(this).data('gajiharian');
        // Populate the modal form fields
        $('#id_karyawan').val(id);
        $('#nama_karyawan').val(nama);
        $('#fbagian').val(bagian);
        $('#fbonus').val(bonus);
        $('#gaji_perhari').val(gajiharian);

        // Show the modal
        $('#modal-form-edit').modal('show');
    });
</script>
<!-- Delete Data -->
<script>
     $('#gajiTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id_karyawan');
        var name = $(this).data('nama');

        // Set the item name in the modal
        $('#itemName').text(nama);

        // Set the form action to the correct delete URL
        $('#deleteForm').attr('action', '/admin/daftargaji/' + id);

        console.log('Opening delete modal');
        $('#modal-delete').modal('show');

    });
</script>
@endsection
