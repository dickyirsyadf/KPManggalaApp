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
                <h2>Data Karyawan</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="userTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Karyawan</th>
                            <th>Email</th>
                            <th>No Handphone</th>
                            <th>hakakses</th>
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
                <form action="/admin/tambahkaryawan" method="POST">
                    @csrf
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="nama" name="nama" type="text" placeholder="Nama Karyawan" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="email" name="email" type="text" placeholder="Email" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-card-list"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="no_hp" name="no_hp" type="number" placeholder="No Handphone" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-phone"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <select class="form-control" id="hakakses" name="id_hakakses" required>
                                <option value="">Select Hak Akses</option>
                                @foreach($hakakses as $hak)
                                    <option value="{{ $hak->id }}">{{ $hak->hakakses }}</option>
                                @endforeach
                            </select>
                            <div class="form-control-icon">
                                <i class="bi bi-phone"></i>
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
                    <h3>Edit {{$menu}}</h3>
                    <p class="text-subtitle text-muted">
                        Tambahkan keterangan setiap mengubah data, agar memudahkan pembukuan
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('karyawan.update') }}">
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
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Email" id="femail" name="femail" required
                                autocomplete="default">
                            <div class="form-control-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="No Handphone" id="fno_hp" name="fno_hp" required
                                autocomplete="default">
                            <div class="form-control-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <select class="form-control" id="fhakakses" name="fhakakses" required>
                                <option value="">Select Hak Akses</option>
                                @foreach($hakakses as $hak)
                                    <option value="{{ $hak->id }}">{{ $hak->hakakses }}</option>
                                @endforeach
                            </select>
                            <div class="form-control-icon">
                                <i class="bi bi-chat-right-text"></i>
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
<div class="modal fade text-left modal-borderless modal-md" id="modal-delete" tabindex="-1" role="dialo" aria-labelledby="modal-delete" aria-hidden="true">
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
<!-- initialisasi DT -->
<script>
       $(document).ready(function () {
        $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('karyawan.data') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'nama', name: 'nama', searchable: true },
                { data: 'email', name: 'email',searchable: true },
                { data: 'no_hp', name: 'no_hp' },
                { data: 'hakakses', name: 'hakakses', searchable: true, orderable: true },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-sm btn-primary edit-btn" 
                                    data-bs-toggle="modal" data-bs-target="#modal-form-edit"
                                    data-id="${row.id}"
                                    data-nama="${row.nama}"
                                    data-email="${row.email}"
                                    data-no_hp="${row.no_hp}"
                                    data-hakakses="${row.id_hakakses}">
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
        const email = $(this).data('email');
        const hakaksesId = $(this).data('hakakses');
        const no_hp = $(this).data('no_hp');
        // Populate the modal form fields
        $('#id_karyawan').val(id);
        $('#nama_karyawan').val(nama);
        $('#femail').val(email);
        $('#fno_hp').val(no_hp);

        $.ajax({
            url: '/admin/karyawan/get-hakakses/' + hakaksesId,
            type: 'GET',
            success: function (response) {
                if (response.hakakses) {
                    // Set the hakakses dropdown to the correct value (ID of HakAkses)
                    $('#fhakakses').val(hakaksesId); // Pre-select the hakakses dropdown option
                } else {
                    // Optionally handle the case where hakakses data is not found
                    console.error('HakAkses data not found');
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching HakAkses data: " + error);
            }
        });

        // Show the modal
        $('#modal-form-edit').modal('show');
    });
</script>
<!-- Delete Data -->
<script>
     $('#userTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');

        // Set the item name in the modal
        $('#itemName').text(name);

        // Set the form action to the correct delete URL
        $('#deleteForm').attr('action', '/admin/karyawan/' + id);

        // Show the delete confirmation modal
        $('#deleteModal').modal('show');
    });
</script>
@endsection