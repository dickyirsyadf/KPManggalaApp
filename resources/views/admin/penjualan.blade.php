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
                        <button class="bg-green-500 text-white px-4 py-2 rounded mt-4" id="checkout">Checkout</button>
                    </div>
                </div>
            </div>
        </section>
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
                 data: 'harga',
                 name: 'harga',
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
                                 data-harga="${row.harga}">
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
    let totalPrice = 0;
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
        totalPriceElement.data('raw-total', totalPrice).text(formatRupiah(totalPrice));
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

        // Recalculate total price for all items
        recalculateTotalPrice();
    });

    // Remove item from cart functionality
    cartItems.on('click', '.remove-from-cart', function () {
        const row = $(this).closest('tr');
        row.remove();

        // Recalculate total price for all items
        recalculateTotalPrice();
    });

    // Checkout button functionality
    $('#checkout').on('click', function () {
        alert('Checkout - Total: ' + formatRupiah(totalPrice));
        cartItems.empty();
        totalPrice = 0;
        totalPriceElement.data('raw-total', totalPrice).text(formatRupiah(totalPrice));
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
        totalPriceElement.data('raw-total', totalPrice).text(formatRupiah(totalPrice));
    }
</script>
@endsection
