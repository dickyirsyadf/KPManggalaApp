@extends('../layouts.admin-master')
@section('admin-master')

{{-- Tambahkan CSRF Token Meta Tag --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- CSS Kustom untuk Tampilan Baru --}}
<style>
    /* Memberi efek modern pada kartu */
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: none;
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid #eee;
    }

    /* Mempercantik judul kartu */
    .card-title-custom {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
    }

    /* Gaya untuk tabel */
    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody tr:hover {
        background-color: #2a2e45;
    }

    /* Menambahkan border pada tabel */
    .table-bordered {
        border: 1px solid #e9ecef;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #e9ecef;
    }

    /* Gaya untuk kotak pencarian DataTables */
    .dataTables_wrapper .dataTables_filter {
        text-align: right; /* Memastikan posisi di kanan */
        padding-bottom: 10px; /* Memberi jarak bawah */
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 6px 12px; /* Padding diperkecil */
        margin-left: 0.5em;
        transition: all 0.2s ease-in-out;
        background-color: #fff;
        color: #333;
        max-width: 200px; /* Batasi lebar maksimum */
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #435ebe;
        box-shadow: 0 0 0 2px rgba(67, 94, 190, 0.2);
        outline: none;
    }


    /* Gaya untuk tombol-tombol */
    .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn-primary {
        background: linear-gradient(45deg, #435ebe, #5e72e4);
        border: none;
    }

    .btn-success {
        background: linear-gradient(45deg, #198754, #28a745);
        border: none;
    }

    .btn-danger {
        background: linear-gradient(45deg, #dc3545, #f5365c);
        border: none;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Tombol aksi di tabel */
    .action-btn {
        padding: 5px 10px;
        font-size: 0.8rem;
    }

    /* Gaya untuk keranjang belanja */
    #total-price-container {
        text-align: right;
        margin-top: 20px;
    }

    #total-price-text {
        font-size: 1.5rem;
        font-weight: 700;
        color: #435ebe;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-input {
        width: 50px !important;
        text-align: center;
        border-radius: 0 !important;
        border-left: none;
        border-right: none;
    }

    .quantity-btn {
        border-radius: 0;
        padding: 5px 10px;
    }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Kasir Penjualan</h3>
                <p class="text-subtitle text-muted">Lakukan transaksi penjualan produk.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            {{$menu}}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Kolom Produk -->
        <div class="col-lg-7">
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title-custom"><i class="bi bi-box-seam-fill me-2"></i>Produk Tersedia</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered" id="barangTable">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- DataTables akan mengisi bagian ini --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        <!-- Kolom Keranjang -->
        <div class="col-lg-5">
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title-custom"><i class="bi bi-cart-fill me-2"></i>Keranjang Belanja</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="min-height: 250px;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-items">
                                    {{-- Item keranjang akan ditambahkan di sini secara dinamis --}}
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div id="total-price-container">
                            <h5 class="text-muted">Total Belanja:</h5>
                            <h3 id="total-price-text">Rp 0</h3>
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-success btn-lg checkout-btn" data-bs-toggle="modal" data-bs-target="#modal-form-checkout">
                                <i class="bi bi-cart-check-fill me-2"></i> Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Modal Checkout -->
<div class="modal fade text-left modal-borderless modal-md" id="modal-form-checkout" tabindex="-1" role="dialog" aria-labelledby="modal-form-checkout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white"><i class="bi bi-cash-coin me-2"></i>Formulir Pembayaran</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="checkoutForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="total-price-modal" class="form-label">Total Harga</label>
                        <input type="text" id="total-price-modal" class="form-control form-control-lg" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah-bayar" class="form-label">Jumlah Bayar</label>
                        <input type="number" id="jumlah-bayar" class="form-control form-control-lg" placeholder="Masukkan jumlah bayar" required>
                    </div>
                    <div class="form-group">
                        <label for="kembalian" class="form-label">Kembalian</label>
                        <input type="text" id="kembalian" class="form-control form-control-lg" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <span>Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        <i class="bi bi-send-check-fill me-2"></i> Bayar Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Aset JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- Fungsi Bantuan ---
    function formatRupiah(amount) {
        if (isNaN(amount)) return 'Rp 0';
        return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
    }

    // --- Inisialisasi DataTable ---
    $(document).ready(function () {
     $('#barangTable').DataTable({
         processing: true,
         serverSide: true,
         ajax: "{{ route('barang.data') }}",
         columns: [
             { data: 'nama', name: 'nama' },
             { data: 'stock', name: 'stock' },
             { data: 'harga_jual', name: 'harga_jual', render: formatRupiah },
             {
                 data: null,
                 orderable: false,
                 searchable: false,
                 render: function (data, type, row) {
                     return `<button class="btn btn-sm btn-primary action-btn add-to-cart" data-id="${row.id}" data-nama="${row.nama}" data-harga="${row.harga_jual}"><i class="bi bi-plus-lg"></i></button>`;
                 },
             },
         ],
         language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
        }
     });
    });

    // --- Logika Keranjang ---
    const cartItems = $('#cart-items');
    const totalPriceElement = $('#total-price-text');
    let cart = {};

    function updateCartView() {
        cartItems.empty();
        let totalPrice = 0;
        if (Object.keys(cart).length === 0) {
            cartItems.html('<tr><td colspan="4" class="text-center text-muted py-4">Keranjang masih kosong</td></tr>');
        } else {
            for (const id in cart) {
                const item = cart[id];
                const totalItemPrice = item.quantity * item.price;
                totalPrice += totalItemPrice;
                const cartItemHTML = `
                    <tr data-id-barang="${id}">
                        <td>
                            <div class="fw-bold">${item.name}</div>
                            <small class="text-muted">${formatRupiah(item.price)}</small>
                        </td>
                        <td class="text-center">
                            <div class="quantity-controls">
                                <button class="btn btn-sm btn-outline-secondary quantity-btn change-quantity" data-change="-1">-</button>
                                <input type="number" class="form-control form-control-sm quantity-input" value="${item.quantity}" readonly>
                                <button class="btn btn-sm btn-outline-secondary quantity-btn change-quantity" data-change="1">+</button>
                            </div>
                        </td>
                        <td class="fw-bold">${formatRupiah(totalItemPrice)}</td>
                        <td><button class="btn btn-sm btn-danger action-btn remove-from-cart"><i class="bi bi-trash-fill"></i></button></td>
                    </tr>`;
                cartItems.append(cartItemHTML);
            }
        }
        totalPriceElement.text(formatRupiah(totalPrice));
        $('#total-price-modal').val(formatRupiah(totalPrice));
    }

    $('#barangTable').on('click', '.add-to-cart', function () {
        const id = $(this).data('id');
        const name = $(this).data('nama');
        const price = parseInt($(this).data('harga'));

        if (cart[id]) {
            cart[id].quantity++;
        } else {
            cart[id] = { name: name, price: price, quantity: 1 };
        }
        updateCartView();
    });

    cartItems.on('click', '.change-quantity', function () {
        const id = $(this).closest('tr').data('id-barang');
        const change = parseInt($(this).data('change'));

        if (cart[id]) {
            cart[id].quantity += change;
            if (cart[id].quantity <= 0) {
                delete cart[id];
            }
        }
        updateCartView();
    });

    cartItems.on('click', '.remove-from-cart', function () {
        const id = $(this).closest('tr').data('id-barang');
        delete cart[id];
        updateCartView();
    });

    // Panggil pertama kali untuk menampilkan pesan keranjang kosong
    updateCartView();

    // --- Logika Modal Checkout ---
    $('#jumlah-bayar').on('input', function () {
        const totalRaw = totalPriceElement.text().replace(/[^0-9]/g, '');
        const total = parseInt(totalRaw) || 0;
        const bayar = parseInt($(this).val()) || 0;
        const kembalian = bayar - total;
        $('#kembalian').val(formatRupiah(kembalian > 0 ? kembalian : 0));
    });

    // --- Aksesibilitas: Fokus pada input modal saat ditampilkan ---
    $('#modal-form-checkout').on('shown.bs.modal', function () {
        $('#jumlah-bayar').focus();
    });

    // --- Logika Pengiriman Form ---
    $('#checkoutForm').on('submit', function (e) {
        e.preventDefault();

        const totalRaw = totalPriceElement.text().replace(/[^0-9]/g, '');
        const totalBayar = parseInt(totalRaw);
        const jumlahBayar = parseInt($('#jumlah-bayar').val());

        if (Object.keys(cart).length === 0) {
            Swal.fire('Gagal!', 'Keranjang belanja masih kosong.', 'error');
            return;
        }

        if (isNaN(jumlahBayar) || jumlahBayar < totalBayar) {
            Swal.fire('Gagal!', 'Jumlah bayar tidak mencukupi.', 'error');
            return;
        }

        const detailPenjualan = Object.keys(cart).map(id => {
            return {
                id_barang: id,
                qty: cart[id].quantity,
                subtotal: cart[id].quantity * cart[id].price
            };
        });

        const formData = {
            _token: $('input[name="_token"]').val(),
            total_bayar: totalBayar,
            bayar: jumlahBayar,
            detail_penjualan: detailPenjualan
        };

        $.ajax({
            url: '{{ route("penjualan.store") }}',
            method: 'POST',
            data: formData,
            success: function (response) {
                if (document.activeElement) {
                    document.activeElement.blur();
                }
                $('#modal-form-checkout').modal('hide');
                $('#modal-form-checkout').one('hidden.bs.modal', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Transaksi Berhasil!',
                        text: 'Kembalian Anda: ' + formatRupiah(response.kembalian),
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                });
            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Terjadi kesalahan pada server.';
                Swal.fire('Error!', errorMessage, 'error');
            }
        });
    });
</script>
@endsection
