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
                <table class="table table-striped" id="barangTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Deskripsi</th>
                            <th>Stock</th>
                            <th>Harga Jual</th>
                            <th>Harga Modal</th>
                            <th>Action</th>
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
                <form action="/admin/tambahbarang" method="POST">
                    @csrf
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="nama" name="nama" type="text" placeholder="Nama Barang" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-basket-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="deskripsi" name="deskripsi" type="text" placeholder="Deskripsi Barang" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-card-list"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="stock" name="stock" type="number" placeholder="Stock Barang" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-plus-slash-minus"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                        <input id="harga" name="harga" type="number" placeholder="Harga Barang" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-cash"></i>
                            </div>
                        </div>
                    </div>
            </div>
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

<!-- Modal Edit -->
<div class="modal fade text-left modal-borderless modal-md" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-form-edit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h3>Edit Barang</h3>
                    <p class="text-subtitle text-muted">
                        Tambahkan keterangan setiap mengubah data, agar memudahkan pembukuan
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('barang.update') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="ID Barang" id="id_barang"
                                name="id_barang" required autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-file-earmark-binary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Nama Barang" name="nama_barang" id="nama_barang" required
                                autocomplete="default">
                            <div class="form-control-icon">
                                <i class="bi bi-basket-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Deskripsi" id="fdeskripsi" name="fdeskripsi" required
                                autocomplete="default">
                            <div class="form-control-icon">
                                <i class="bi bi-card-list"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Stock" id="fstock" name="fstock" required
                                autocomplete="default">
                            <div class="form-control-icon">
                                <i class="bi bi-plus-slash-minus"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Harga Jual" id="fhargaj" name="fhargaj" required
                                autocomplete="off">
                            <div class="form-control-icon">
                                <i class="bi bi-cash"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Harga Modal" id="fhargam" name="fhargam" required
                                autocomplete="off">
                            <div class="form-control-icon">
                                <i class="bi bi-cash"></i>
                            </div>
                        </div>
                    </div>
                </div>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap JS (Optional) -->
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
        $('#barangTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('barang.data') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'nama', name: 'nama', searchable: true },
                { data: 'deskripsi', name: 'deskripsi',searchable: true },
                { data: 'stock', name: 'stock' },
                {
                    data: 'harga_jual',
                    name: 'harga_jual',
                    searchable: false,
                    render: function (data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'harga_modal',
                    name: 'harga_modal',
                    searchable: false,
                    render: function (data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-sm btn-primary edit-btn"
                                    data-bs-toggle="modal" data-bs-target="#modal-form-edit"
                                    data-id="${row.id}"
                                    data-nama="${row.nama}"
                                    data-deskripsi="${row.deskripsi}"
                                    data-stock="${row.stock}"
                                    data-hargaj="${row.harga_jual}"
                                    data-hargam="${row.harga_modal}">
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
<!-- passing data dari btn ke modal  -->
<script>
    $(document).on('click', '.edit-btn', function () {
        // Retrieve data from the button
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const deskripsi = $(this).data('deskripsi');
        const hargaj = $(this).data('hargaj');
        const hargam = $(this).data('hargam');
        const stock = $(this).data('stock');
        // Populate the modal form fields
        $('#id_barang').val(id);
        $('#nama_barang').val(nama);
        $('#fdeskripsi').val(deskripsi);
        $('#fhargaj').val(hargaj);
        $('#fhargam').val(hargam);
        $('#fstock').val(stock);

        // Show the modal
        $('#modal-form-edit').modal('show');
    });
</script>
<!-- Delete Data -->
<script>
     $('#barangTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');

        // Set the item name in the modal
        $('#itemName').text(name);

        // Set the form action to the correct delete URL
        $('#deleteForm').attr('action', '/admin/barang/' + id);

        // Show the delete confirmation modal
        $('#deleteModal').modal('show');
    });
</script>
@endsection
