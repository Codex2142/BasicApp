@extends('layouts.app')

@section('title', 'Kalkulator')

@section('content')
    <div class="container shadow p-3 bg-white mb-3 rounded-lg mt-4">
        <div class="row">
            <!-- Kolom Produk -->
            <div class="col-12 col-md-6 mb-3">
                <div class="input-group mb-3">
                    <input id="searchInput" type="text" class="form-control" placeholder="Cari Produk" aria-label="Pencarian">
                    <div class="input-group-append">
                        <span id="searchDelete" class="btn btn-danger from-group-view">X</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6"></div>
            <div class="col-md-6">
                <h1 class="fw-bold display-6">Daftar Produk</h1>
                <table class="table table-auto" id="product-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body">
                        @foreach ($result as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>Rp {{ number_format($product->price2, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary"
                                        onclick="addToInvoice({{ $product->id }}, '{{ $product->name }}', {{ $product->price2 }})">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Kolom Invoice -->
            <div class="col-md-6">
                <h1 class="fw-bold display-6">Keranjang</h1>
                <div class="table-responsive">
                    <table class="table table-auto" id="invoice-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="invoice-body">
                            <!-- Diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <h5>Total Bayar: Rp <span id="total-bayar">0</span></h5>
                </div>
                <input type="hidden" id="json" name="product">
                <input type="hidden" id="total-bayar-final" name="total">
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById("searchInput");
            const clearBtn = document.getElementById("searchDelete");
            const rows = document.querySelectorAll("#product-table tbody tr");

            input.addEventListener("keyup", function() {
                const keyword = input.value.toLowerCase();
                rows.forEach(row => {
                    const nama = row.cells[0].textContent.toLowerCase();
                    row.style.display = nama.includes(keyword) ? "" : "none";
                });
            });

            clearBtn.addEventListener("click", function() {
                input.value = '';
                input.dispatchEvent(new Event('keyup'));
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const rows = Array.from(document.querySelectorAll("#product-table-body tr"));
            const rowsPerPage = 10;
            const paginationContainer = document.createElement('div');
            paginationContainer.classList.add('mt-3');

            let currentPage = 1;

            function renderPagination(totalPages) {
                paginationContainer.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.className = 'btn btn-sm btn-outline-primary mx-1 mb-3';
                    btn.innerText = i;
                    btn.addEventListener('click', () => {
                        currentPage = i;
                        displayRows();
                    });
                    paginationContainer.appendChild(btn);
                }

                document.getElementById('product-table').after(paginationContainer);
            }

            function displayRows() {
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                rows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? '' : 'none';
                });
            }

            if (rows.length > 0) {
                const totalPages = Math.ceil(rows.length / rowsPerPage);
                renderPagination(totalPages);
                displayRows();
            }
        });
        let invoice = {};

        function addToInvoice(id, name, price) {
            if (invoice[id]) {
                invoice[id].qty += 1;
            } else {
                invoice[id] = {
                    name,
                    price,
                    qty: 1
                };
            }
            renderInvoice();
        }

        function removeFromInvoice(id) {
            if (invoice[id]) {
                invoice[id].qty -= 1;
                if (invoice[id].qty <= 0) {
                    delete invoice[id];
                }
            }
            renderInvoice();
        }

        function changeQty(id, qty) {
            qty = parseInt(qty);
            if (qty > 0) {
                invoice[id].qty = qty;
            } else {
                delete invoice[id];
            }
            renderInvoice();
        }

        function renderInvoice() {
            let body = '';
            let total = 0;

            for (const [id, item] of Object.entries(invoice)) {
                let subTotal = item.price * item.qty;
                total += subTotal;

                body += `
                <tr>
                    <td>${item.name}</td>
                    <td>
                        <input type="number"
                            value="${item.qty}"
                            class="form-control text-center"
                            style="min-width: 70px;"
                            onchange="changeQty(${id}, this.value)">
                    </td>
                    <td>Rp ${subTotal.toLocaleString('id-ID')}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="removeFromInvoice(${id})"><i class="bi bi-dash"></i></button>
                    </td>
                </tr>
            `;
            }

            document.getElementById('invoice-body').innerHTML = body;
            document.getElementById('total-bayar').innerText = total.toLocaleString('id-ID');
            document.getElementById('total-bayar-final').value = total;

            // Generate JSON string
            const jsonInput = {};
            for (const [id, item] of Object.entries(invoice)) {
                jsonInput[id] = item.qty;
            }
            document.getElementById('json').value = JSON.stringify(jsonInput);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById("searchInput");
            const clearBtn = document.getElementById("searchDelete");
            const rows = document.querySelectorAll("#product-table tbody tr");

            input.addEventListener("keyup", function() {
                const keyword = input.value.toLowerCase();
                rows.forEach(row => {
                    const nama = row.cells[0].textContent.toLowerCase();
                    row.style.display = nama.includes(keyword) ? "" : "none";
                });
            });

            clearBtn.addEventListener("click", function() {
                input.value = '';
                input.dispatchEvent(new Event('keyup'));
            });
        });
    </script>
@endpush
