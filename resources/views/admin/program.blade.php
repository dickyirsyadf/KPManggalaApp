@extends('admin.layouts.admin-master')
@section('admin-master')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <h6><b>{{$menu}}</b></h6>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h2 class="mb-4">Purchase Order</h2>

    <!-- Purchase Order Details -->
    <form id="purchaseOrderForm">
        <div class="mb-3 row">
            <label for="orderNumber" class="col-sm-2 col-form-label">Order Number</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="orderNumber" placeholder="PO-12345" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="orderDate" class="col-sm-2 col-form-label">Order Date</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" id="orderDate" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="supplier" class="col-sm-2 col-form-label">Supplier</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="supplier" placeholder="Supplier Name" required>
            </div>
        </div>

        <!-- Item List -->
        <div>
            <h4>Items</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="itemTableBody">
                    <tr>
                        <td><input type="text" class="form-control" placeholder="Item Name" required></td>
                        <td><input type="number" class="form-control" placeholder="Quantity" min="1" required></td>
                        <td><input type="number" class="form-control" placeholder="Unit Price" step="0.01" required></td>
                        <td class="total">$0.00</td>
                        <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" id="addItemButton">Add Item</button>
        </div>

        <!-- Submit Button -->
        <div class="mt-4">
            <button type="submit" class="btn btn-success">Submit Purchase Order</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('addItemButton').addEventListener('click', function() {
        const tbody = document.getElementById('itemTableBody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" class="form-control" placeholder="Item Name" required></td>
            <td><input type="number" class="form-control" placeholder="Quantity" min="1" required></td>
            <td><input type="number" class="form-control" placeholder="Unit Price" step="0.01" required></td>
            <td class="total">$0.00</td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        `;
        tbody.appendChild(newRow);
    });

    document.getElementById('itemTableBody').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    document.getElementById('purchaseOrderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Purchase Order Submitted!');
    });
</script>
@endsection
