@extends('layouts.app')

@section('title', 'Pembelian - Edit')
@php
    // dd($invoice);
@endphp
@section('content')
    <div class="container mt-4">

        <div class="container mt-3">
            <div class="breadcrumbs-container text-white">
                {!! Breadcrumbs::render('PembelianEdit', $invoice->id) !!}
            </div>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                @include('components.feedback', ['type' => 'error', 'message' => $error])
            @endforeach
        @endif

        <div class="col bg-white rounded-lg shadow my-4 mx-2 w-fit d-flex align-items-center gap-3">
            <div class="btn bg-green-900 text-white hover:bg-green-400 hover:text-black rounded-lg my-3 mx-3">
                <a href="/pembelian">Kembali</a>
            </div>
            <span class="md:mx-40 mr-10 fw-bold">Edit Pembelian</span>
        </div>

        <div class="container shadow p-3 bg-white mb-3 rounded-lg">
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <div class="input-group">
                        <input id="searchInput" type="text" class="form-control" placeholder="Cari Produk"
                            aria-label="Pencarian">
                        <div class="input-group-append">
                            <span id="searchDelete"
                                class="btn bg-red-800 text-white hover:bg-red-400 hover:text-black from-group-view">X</span>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6"></div>

                <div class="col-md-6 md:sm-5">
                    <h1 class="fw-bold display-6 text-center">Daftar Produk</h1>
                    <table class="table table-auto" id="product-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="product-table-body">
                            @foreach ($products as $product)
                                <tr data-product-id="{{ $product->id }}">
                                    <td>{{ $product->name }}</td>
                                    <td>Rp {{ number_format($product->price2, 0, ',', '.') }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        <button class="btn btn-sm bg-blue-900 text-white hover:bg-blue-400 hover:text-black"
                                            onclick="addToInvoice({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price2 }})">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-md-6 mt-lg-6">
                    <h1 class="fw-bold display-6 text-center">Keranjang</h1>
                    <div class="table-responsive">
                        <table class="table table-auto" id="invoice-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="invoice-body">
                                <!-- Baris dinamis -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <h5>Total Bayar: Rp <span id="total-bayar">0</span></h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM PENGISIAN --}}
        <div class="container shadow p-3 bg-white mb-3 rounded-lg">
            <form action="{{ route('invoice.update', $invoice->id) }}" method="POST" enctype="multipart/form-data"
                id="KirimanForm">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label block mb-1 font-medium">Tanggal</label>
                            <input type="text" id="tanggal" name="tanggal" placeholder=""
                                value="{{ old('tanggal', $invoice->tanggal) }}"
                                class="form-control border rounded p-2 w-full" />
                        </div>

                        <div class="mb-3">
                            <label for="total" class="form-label block mb-1 font-medium">Total</label>
                            <input type="number" id="total-bayar-final" name="total" placeholder=""
                                class="form-control border rounded p-2 w-full bg-gray-200" readonly
                                value="{{ old('total', $invoice->total) }}" />
                        </div>

                        {{-- Simpan JSON produk sebagai string di hidden input --}}
                        <input type="hidden" id="json" name="product"
                            value="{{ old('product', $invoice->product) }}">

                        <input type="hidden" id="status" name="type" value="{{ $invoice->type }}">
                        <input type="hidden" name="updated_stocks" id="updatedStocks" value="">
                    </div>

                    <div class="col">
                        <div class="mb-3">
                            @include('components.form', [
                                'type' => 'textarea',
                                'label' => 'Deskripsi',
                                'name' => 'description',
                                'place' => 'Masukkan Deskripsi',
                                'value' => old('description', $invoice->description),
                            ])
                            <small id="wordCountInfo" class="text-sm text-gray-500">0 / 30 kata</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3 d-flex justify-between gap-2">
                    <button type="submit"
                        class="btn bg-blue-900 text-white hover:bg-blue-400 hover:text-black px-4 py-2 rounded">SIMPAN
                    </button>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Global variables
        const productStokMap = @json($products->pluck('stock', 'id'));
        const transactionStatus = "{{ $invoice->status }}";
        let invoice = {};
        let stockChanges = {};
        let productStocks = {};

        // Pagination variables
        let allRows = [];
        const rowsPerPage = 10;
        let paginationContainer;
        let currentPage = 1;

        // Initialize pagination elements
        function initPagination() {
            allRows = Array.from(document.querySelectorAll("#product-table-body tr"));
            paginationContainer = document.createElement('div');
            paginationContainer.id = 'pagination-container';
            paginationContainer.classList.add('mt-3', 'd-flex', 'justify-content-start', 'flex-wrap');

            // Add some basic styles
            const style = document.createElement('style');
            style.textContent = `
            #pagination-container {
                width: 100%;
                margin-top: 1rem;
            }
            #pagination-container button {
                min-width: 40px;
                margin: 0 2px;
            }
        `;
            document.head.appendChild(style);
        }

        // Render pagination controls
        function renderPagination(totalPages) {
            paginationContainer.innerHTML = '';

            if (totalPages <= 1) return;

            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'btn btn-sm btn-outline-primary';
            prevBtn.innerHTML = '&laquo;';
            prevBtn.disabled = currentPage === 1;
            prevBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    displayRows();
                    renderPagination(totalPages);
                }
            });
            paginationContainer.appendChild(prevBtn);

            // Page buttons
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = `btn btn-sm ${currentPage === i ? 'btn-primary' : 'btn-outline-primary'}`;
                btn.innerText = i;
                btn.addEventListener('click', () => {
                    currentPage = i;
                    displayRows();
                    renderPagination(totalPages);
                });
                paginationContainer.appendChild(btn);
            }

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'btn btn-sm btn-outline-primary';
            nextBtn.innerHTML = '&raquo;';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    displayRows();
                    renderPagination(totalPages);
                }
            });
            paginationContainer.appendChild(nextBtn);

            // Add to DOM if not already present
            if (!document.getElementById('pagination-container')) {
                document.getElementById('product-table').after(paginationContainer);
            }
        }

        // Display rows for current page
        function displayRows() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            allRows.forEach((row, index) => {
                // Only modify rows that aren't hidden by other filters
                if (window.getComputedStyle(row).display !== 'none') {
                    row.style.display = (index >= start && index < end) ? '' : 'none';
                }
            });
        }

        // Update pagination based on visible rows
        function updatePaginationDisplay() {
            const visibleRows = allRows.filter(row =>
                window.getComputedStyle(row).display !== 'none'
            );

            const totalPages = Math.ceil(visibleRows.length / rowsPerPage);

            if (totalPages > 1) {
                renderPagination(totalPages);
                displayRows();
            } else if (document.getElementById('pagination-container')) {
                document.getElementById('pagination-container').remove();
                // Show all rows when pagination is removed
                allRows.forEach(row => row.style.display = '');
            }
        }

        // Update product table display
        function updateProductTable() {
            document.querySelectorAll("#product-table-body tr").forEach(row => {
                const id = row.getAttribute('data-product-id');
                const stockCell = row.cells[2];
                const currentStock = productStocks[id] !== undefined ? productStocks[id] : parseInt(stockCell
                    .textContent);

                stockCell.textContent = currentStock;
                row.style.display = currentStock <= 0 ? 'none' : '';
            });
            updatePaginationDisplay();
        }

        // Load existing invoice data
        function loadExistingInvoice() {
            @if ($invoice->product)
                try {
                    const invoiceData = JSON.parse(@json($invoice->product));
                    if (invoiceData && invoiceData.items) {
                        invoiceData.items.forEach(item => {
                            invoice[item.id] = {
                                id: item.id,
                                name: item.name,
                                price: item.price,
                                qty: item.qty,
                                stok: productStokMap[item.id] ?? 0
                            };

                            if (productStokMap[item.id] !== undefined) {
                                productStocks[item.id] = productStokMap[item.id] - item.qty;
                            }
                        });
                        renderInvoice();
                        updateProductTable();
                    }
                } catch (error) {
                    console.error('Error parsing invoice data:', error);
                }
            @endif
        }

        // Track stock changes
        function updateStockChanges(id, initialStock, updatedStock) {
            stockChanges[id] = {
                id: id,
                initialStock: initialStock,
                updatedStock: updatedStock
            };
            updateStockHiddenField();
        }

        // Update hidden stock field
        function updateStockHiddenField() {
            document.getElementById('updatedStocks').value = JSON.stringify(Object.values(stockChanges));
        }

        // Add product to invoice
        function addToInvoice(id, name, price) {
            const currentStock = getCurrentStockFromTable(id);

            if (currentStock <= 0) {
                alert(`Stok ${name} kosong.`);
                return;
            }

            if (invoice[id]) {
                invoice[id].qty += 1;
            } else {
                invoice[id] = {
                    id: id,
                    name: name,
                    price: price,
                    qty: 1,
                    stok: currentStock
                };
            }

            const newStock = currentStock - 1;
            updateStockChanges(id, currentStock, newStock);
            updateProductStockInTable(id, newStock);
            renderInvoice();
        }

        // Update product stock in table
        function updateProductStockInTable(id, newStock) {
            const row = document.querySelector(`#product-table-body tr[data-product-id="${id}"]`);
            if (row) {
                row.cells[2].textContent = newStock;
                productStocks[id] = newStock;
                row.style.display = newStock <= 0 ? 'none' : '';
            }
        }

        // Remove product from invoice
        function removeFromInvoice(id) {
            if (invoice[id]) {
                invoice[id].qty -= 1;

                const currentStock = getCurrentStockFromTable(id);
                const newStock = currentStock + 1;

                updateStockChanges(id, currentStock, newStock);
                updateProductStockInTable(id, newStock);

                if (invoice[id].qty <= 0) {
                    delete invoice[id];
                }
                renderInvoice();
            }
        }

        // Change product quantity
        function changeQty(id, qty) {
            qty = parseInt(qty);
            const currentItem = invoice[id];

            if (!currentItem) return;

            const currentStock = getCurrentStockFromTable(id);

            if (qty <= 0) {
                const newStock = currentStock + currentItem.qty;
                updateStockChanges(id, currentStock, newStock);
                updateProductStockInTable(id, newStock);
                delete invoice[id];
            } else {
                const diff = qty - currentItem.qty;

                if (diff > currentStock) {
                    alert(`Stok tidak mencukupi. Hanya tersedia ${currentStock}`);
                    return;
                }

                const newStock = currentStock - diff;
                updateStockChanges(id, currentStock, newStock);
                updateProductStockInTable(id, newStock);
                currentItem.qty = qty;
            }

            renderInvoice();
        }

        // Get current stock from table
        function getCurrentStockFromTable(id) {
            const row = document.querySelector(`#product-table-body tr[data-product-id="${id}"]`);
            return row ? parseInt(row.cells[2].textContent) : 0;
        }

        // Render invoice
        function renderInvoice() {
            let body = '';
            let total = 0;
            const items = Object.values(invoice);

            for (const item of items) {
                let subTotal = item.price * item.qty;
                total += subTotal;
                body += `
                <tr>
                    <td>${item.name}</td>
                    <td>
                        <input type="number" ${transactionStatus !== 'done' ? '' : 'readonly'}
                            value="${item.qty}"
                            class="w-10 bg-gray-100 text-center"
                            onchange="changeQty(${item.id}, this.value)">
                    </td>
                    <td>Rp ${subTotal.toLocaleString('id-ID')}</td>
                    ${transactionStatus !== 'done' ? `
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="removeFromInvoice(${item.id})">
                                    <i class="bi bi-dash"></i>
                                </button>
                            </td>
                        ` : ''}
                </tr>
            `;
            }

            document.getElementById('invoice-body').innerHTML = body;
            document.getElementById('total-bayar').innerText = total.toLocaleString('id-ID');
            document.getElementById('total-bayar-final').value = total;
            document.getElementById('json').value = JSON.stringify({
                items: items.map(item => ({
                    id: item.id,
                    qty: item.qty,
                    name: item.name,
                    price: item.price,
                    subtotal: item.price * item.qty
                })),
                total: total
            });
        }

        // Initialize when DOM is loaded
        // Initialize when DOM is loaded
        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi productStocks
            document.querySelectorAll("#product-table-body tr").forEach(row => {
                const id = row.getAttribute('data-product-id');
                productStocks[id] = parseInt(row.cells[2].textContent);
            });

            // Load existing invoice data
            loadExistingInvoice();

            // Search produk
            const input = document.getElementById("searchInput");
            const clearBtn = document.getElementById("searchDelete");
            const rows = document.querySelectorAll("#product-table tbody tr");

            input?.addEventListener("keyup", function() {
                const keyword = input.value.toLowerCase();
                rows.forEach(row => {
                    const nama = row.cells[0].textContent.toLowerCase();
                    row.style.display = nama.includes(keyword) ? "" : "none";
                });
                updatePaginationDisplay();
            });

            clearBtn?.addEventListener("click", function() {
                input.value = '';
                input.dispatchEvent(new Event('keyup'));
            });

            // Simple Pagination Implementation - THIS IS WHAT YOU REQUESTED
            const allRows = Array.from(document.querySelectorAll("#product-table-body tr"));
            const rowsPerPage = 10;
            const paginationContainer = document.createElement('div');
            paginationContainer.classList.add('mt-3', 'd-flex', 'justify-content-start');

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

                allRows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? '' : 'none';
                });
            }

            function updatePaginationDisplay() {
                const visibleRows = allRows.filter(row => row.style.display !== 'none');
                const totalPages = Math.ceil(visibleRows.length / rowsPerPage);

                if (visibleRows.length > rowsPerPage) {
                    if (!document.contains(paginationContainer)) {
                        renderPagination(totalPages);
                    }
                    displayRows();
                } else if (document.contains(paginationContainer)) {
                    paginationContainer.remove();
                    // Show all rows when pagination is removed
                    allRows.forEach(row => row.style.display = '');
                }
            }

            // Initialize pagination
            updatePaginationDisplay();

            // Initialize flatpickr
            const elemenTanggal = document.querySelector("#tanggal");
            if (elemenTanggal) {
                flatpickr(elemenTanggal, {
                    locale: 'id',
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "j F Y",
                    defaultDate: elemenTanggal.value || "today",
                });
            }

            // Character counter for description
            const deskripsiTextarea = document.querySelector('textarea[name="description"]');
            const charCount = document.getElementById('wordCountInfo');
            const maxChar = 30;

            if (deskripsiTextarea && charCount) {
                deskripsiTextarea.addEventListener('input', function() {
                    let length = this.value.length;
                    if (length > maxChar) {
                        this.value = this.value.slice(0, maxChar);
                        length = maxChar;
                    }
                    charCount.textContent = `${length} / ${maxChar} karakter`;
                    charCount.classList.toggle('text-danger', length === maxChar);
                });
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .flatpickr-day.disabled,
        .flatpickr-disabled {
            background-color: #ffe5e5 !important;
            color: #d60000 !important;
            pointer-events: none !important;
            opacity: 1 !important;
        }
    </style>
@endpush
