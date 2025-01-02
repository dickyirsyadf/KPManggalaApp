@extends('admin.layouts.admin-master')
@section('admin-master')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        {{$menu}}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h2 class="text-xl font-semibold mb-2">Produk Tersedia</h2>
                <table class="table table-striped" id="barangTable">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div class="page-body mt-4">
    <div class="row">
        <section class="section" style="margin-top: -40px">
            <div class="card">
                <div class="card-body">
                    <div class="w-full md:w-1/2 px-2 mb-4">
                        <h2 class="text-xl font-semibold mb-2">Produk Dipilih</h2>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <!-- Cart items will be added here dynamically -->
                            </tbody>
                        </table>
                        <h3 class="text-xl font-semibold mt-4">Total: <span id="total-price">0.00</span></h3>
                        <button class="btn btn-sm btn-success checkout-btn" data-bs-toggle="modal" data-bs-target="#modal-form-checkout" data-total="">
                                Checkout
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- Modal Checkout -->
<div class="modal fade text-left modal-borderless modal-md" id="modal-form-checkout" tabindex="-1" role="dialog" aria-labelledby="modal-form-checkout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h3>Checkout Barang</h3>
                    <p class="text-subtitle text-muted">
                        Pastikan data sudah benar sebelum menyelesaikan transaksi.
                    </p>
                </div>
            </div>
            <form id="checkoutForm" method="POST" action="{{ route('penjualan.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="total-price-modal">Total Harga</label>
                        <input type="text" id="total-price-modal" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah-bayar">Jumlah Bayar</label>
                        <input type="number" id="jumlah-bayar" class="form-control" placeholder="Masukkan jumlah bayar" required>
                    </div>
                    <div class="form-group">
                        <label for="kembalian">Kembalian</label>
                        <input type="text" id="kembalian" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        Submit
                    </button>
                </div>
            </form>
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
             { data: 'nama', name: 'nama', searchable: true },
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
                 data: null,
                 render: function (data, type, row) {
                     return `
                         <button class="btn btn-sm btn-primary edit-btn add-to-cart"
                                 data-nama="${row.nama}"
                                 data-harga="${row.harga_jual}">
                             Tambah
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

<script>
    const cartItems = $('#cart-items');
    const totalPriceElement = $('#total-price');
    const modalTotalHarga = $('#modal-total-harga'); // Field for Total Harga in the modal
    const modalJumlahBayar = $('#jumlah-bayar'); // Input for Jumlah Bayar in the modal
    const modalKembalian = $('#kembalian'); // Field for Kembalian in the modal
    let totalPrice = 0;

    // Update total price element and modal fields
    function updateTotalPrice() {
        totalPriceElement.data('raw-total', totalPrice).text(formatRupiah(totalPrice));
        $('#total-price-modal').val(totalPrice); // Pass raw total to modal input
    }

    $('.checkout-btn').on('click', function () {
        const rawTotal = totalPriceElement.data('raw-total'); // Get raw total
        $('#total-price-modal').val(rawTotal); // Set raw total in modal
    });


    // Calculate and update Kembalian in the modal
    modalJumlahBayar.on('input', function () {
        const jumlahBayar = parseInt($(this).val()) || 0; // Get input value or default to 0
        const rawTotal = parseInt($('#total-price-modal').val()) || 0; // Get the total from modal
        const kembalian = jumlahBayar - rawTotal; // Calculate change
        modalKembalian.val(formatRupiah(kembalian > 0 ? kembalian : 0)); // Update kembalian field
    });
</script>
{{-- Cart manipulation --}}
<script>
    // Add to cart button functionality
    $('#barangTable').on('click', '.add-to-cart', function () {
        const product = $(this).data('nama');
        const price = parseInt($(this).data('harga'));
        const existingRow = cartItems.find(`tr[data-product="${product}"]`);

        if (existingRow.length > 0) {
            // Update quantity and total for existing item
            const quantityInput = existingRow.find('.cart-quantity');
            const totalCell = existingRow.find('.cart-total');
            const quantity = parseInt(quantityInput.val()) + 1;
            const totalItemPrice = quantity * price;

            quantityInput.val(quantity);
            totalCell.text(formatRupiah(totalItemPrice));
        } else {
            // Add a new row for the item
            const cartItem = `
                <tr data-product="${product}" data-price="${price}">
                    <td>${product}</td>
                    <td>${formatRupiah(price)}</td>
                    <td>
                        <div class="quantity-controls">
                            <button class="btn btn-sm btn-secondary decrease-quantity">-</button>
                            <input type="number" class="cart-quantity" value="1" min="1" style="width: 50px; text-align: center;" />
                            <button class="btn btn-sm btn-secondary increase-quantity">+</button>
                        </div>
                    </td>
                    <td class="cart-total">${formatRupiah(price)}</td>
                    <td>
                        <button class="btn btn-sm btn-danger remove-from-cart">Hapus</button>
                    </td>
                </tr>`;
            cartItems.append(cartItem);
        }

        // Update total price
        totalPrice += price;
        updateTotalPrice();
    });

    // Update quantity and total price on quantity change
    cartItems.on('click', '.increase-quantity, .decrease-quantity', function () {
        const row = $(this).closest('tr');
        const price = parseInt(row.data('price'));
        const quantityInput = row.find('.cart-quantity');
        const totalCell = row.find('.cart-total');
        let quantity = parseInt(quantityInput.val());

        if ($(this).hasClass('increase-quantity')) {
            quantity++;
        } else if ($(this).hasClass('decrease-quantity') && quantity > 1) {
            quantity--;
        }

        // Update quantity and total price
        quantityInput.val(quantity);
        const totalItemPrice = quantity * price;
        totalCell.text(formatRupiah(totalItemPrice));

        // Recalculate total price
        recalculateTotalPrice();
    });

    // Remove item from cart functionality
    cartItems.on('click', '.remove-from-cart', function () {
        const row = $(this).closest('tr');
        row.remove();

        // Recalculate total price
        recalculateTotalPrice();
    });

    // Recalculate total price for all items in the cart
    function recalculateTotalPrice() {
        totalPrice = 0;
        cartItems.find('tr').each(function () {
            const row = $(this);
            const quantity = parseInt(row.find('.cart-quantity').val());
            const price = parseInt(row.data('price'));
            totalPrice += quantity * price;
        });
        updateTotalPrice();
    }
</script>
{{-- submit modal penjualan --}}
<script>
    // Submit button functionality
    $('#modal-form-checkout').on('submit', function (e) {
        e.preventDefault();

        const jumlahBayar = parseInt(modalJumlahBayar.val());
        const kembalian = jumlahBayar - totalPrice;

        if (jumlahBayar < totalPrice) {
            alert('Jumlah bayar tidak cukup!');
            return;
        }

        const formData = {
            id: generateTransactionId(),
            id_karyawan: '{{ auth()->user()->id }}', // Get logged-in user's ID
            tgl_penjualan: new Date().toISOString().split('T')[0],
            total_bayar: totalPrice,
            bayar:jumlahBayar,
            kembalian:kembalian,
            detail_penjualans: []
        };

        // Gather cart items
        cartItems.find('tr').each(function () {
            const row = $(this);
            const product = row.data('product');
            const price = parseInt(row.data('price'));
            const quantity = parseInt(row.find('.cart-quantity').val());
            formData.detail_penjualans.push({
                product,
                harga_jual: price,
                qty: quantity,
                subtotal: price * quantity
            });
        });
        // Get the CSRF token from the page
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        // Include CSRF token in the AJAX request
        $.ajax({
            url: '{{ route("penjualan.store") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken, // Include CSRF token in the request headers
            },
            success: function (response) {
                alert('Transaksi berhasil! Kembalian: ' + formatRupiah(kembalian));
                $('#modal-form-checkout').modal('hide');
                cartItems.empty();
                totalPrice = 0;
                updateTotalPrice();
            },
            error: function () {
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        });
    });
</script>
{{-- Generate a unique transaction ID --}}
<script>
    function generateTransactionId() {
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const random = Math.floor(10000 + Math.random() * 90000);
        return `TRS${day}${month}${year}${random}`;
    }
</script>
@endsection
