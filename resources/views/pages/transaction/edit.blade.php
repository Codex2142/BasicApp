@extends('layouts.app')

@section('title', 'Kiriman - Edit')
@php
    // dd($dateDisabled);
@endphp
@section('content')
    <div class="container mt-4">

        <div class="container mt-3">
            <div class="breadcrumbs-container text-white">
                {!! Breadcrumbs::render('TransactionEdit', $transaction) !!}
            </div>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                @include('components.feedback', ['type' => 'error', 'message' => $error])
            @endforeach
        @endif

        <div class="col bg-white rounded-lg shadow my-4 mx-2 w-fit d-flex align-items-center gap-3">
            <div class="btn bg-green-900 text-white hover:bg-green-400 hover:text-black rounded-lg my-3 mx-3">
                <a href="/Kiriman">Kembali</a>
            </div>
            <span class="md:mx-40 mr-10 fw-bold">{{ $transaction->status == 'done' ? 'Detail' : 'Edit' }} Kiriman</span>
        </div>

        <div class="container shadow p-3 bg-white mb-3 rounded-lg">
            <div class="row">
                @if ($transaction->status != 'done')
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
                @endif

                <div class="col-12 col-md-6 {{ $transaction->status == 'done' ? 'd-none' : '' }}"></div>

                @if ($transaction->status != 'done')
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
                                            <button
                                                class="btn btn-sm bg-blue-900 text-white hover:bg-blue-400 hover:text-black"
                                                onclick="addToInvoice({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price2 }})">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="col-md-6 mt-lg-6">
                    <h1 class="fw-bold display-6 text-center">Keranjang</h1>
                    <div class="table-responsive">
                        <table class="table table-auto" id="invoice-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    @if ($transaction->status != 'done')
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="invoice-body">
                                <!-- Baris dinamis -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end d-none">
                        <h5>Total Bayar: Rp <span id="total-bayar">0</span></h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM PENGISIAN --}}
        <div class="container shadow p-3 bg-white mb-3 rounded-lg">
            <form action="{{ route('transaction.update', $transaction->id) }}" method="POST" enctype="multipart/form-data"
                id="KirimanForm">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label block mb-1 font-medium">Tanggal</label>
                            <input type="text" id="tanggal" name="tanggal" placeholder=""
                                value="{{ old('tanggal', $transaction->tanggal) }}"
                                class="form-control border rounded p-2 w-full"
                                {{ $transaction->status == 'done' ? 'readonly' : '' }} />
                        </div>

                        <div class="mb-3">
                            <label for="total" class="form-label block mb-1 font-medium">Total</label>
                            <input type="number" id="total-bayar-final" name="total" placeholder=""
                                class="form-control border rounded p-2 w-full bg-gray-200" readonly
                                value="{{ old('total', $transaction->total) }}" />
                        </div>

                        {{-- Simpan JSON produk sebagai string di hidden input --}}
                        <input type="hidden" id="json" name="product"
                            value="{{ old('product', $transaction->product) }}">

                        <input type="hidden" id="status" name="status" value="pending">
                        <input type="hidden" name="updated_stocks" id="updatedStocks" value="">
                    </div>

                    <div class="col">
                        <div class="mb-3">
                            @include('components.form', [
                                'type' => 'textarea',
                                'label' => 'Deskripsi',
                                'name' => 'description',
                                'place' => 'Masukkan Deskripsi',
                                'value' => old('description', $transaction->description),
                                'addon' => $transaction->status == 'done' ? 'readonly' : '',
                            ])
                            <small id="wordCountInfo" class="text-sm text-gray-500">0 / 30 kata</small>
                        </div>
                    </div>
                </div>

                @if ($transaction->status != 'done')
                    <div class="mb-3 d-flex justify-between gap-2">
                        <button type="submit"
                            class="btn bg-blue-900 text-white hover:bg-blue-400 hover:text-black px-4 py-2 rounded">SIMPAN
                            SEMENTARA</button>
                        @if (Auth::user()->role === 'admin')
                            <button type="button" id="btnSelesai"
                                class="btn bg-red-800 text-white hover:bg-red-400 hover:text-black	 px-4 py-2 rounded">SIMPAN
                                PERMANEN</button>
                        @endif
                    </div>
                @endif
            </form>
        </div>
    </div>

    @include('components.modal-save')
@endsection

@push('scripts')
    <script>
        // Inisialisasi variabel global
        const productStokMap = @json($products->pluck('stock', 'id'));
        const transactionStatus = "{{ $transaction->status }}";
        let invoice = {};
        let stockChanges = {};
        let productStocks = {};

        // Fungsi untuk update tampilan tabel produk
        function updateProductTable() {
            document.querySelectorAll("#product-table-body tr").forEach(row => {
                const id = row.getAttribute('data-product-id');
                const stockCell = row.cells[2];

                const currentStock = productStocks[id] !== undefined ? productStocks[id] : parseInt(stockCell
                    .textContent);

                stockCell.textContent = currentStock;
                row.style.display = currentStock <= 0 ? 'none' : '';
            });
        }

        // Fungsi untuk memuat data invoice yang sudah ada - DIPERBAIKI
        function loadExistingInvoice() {
            @if ($transaction->product)
                try {
                    const invoiceData = JSON.parse(@json($transaction->product));
                    if (invoiceData && invoiceData.items) {
                        // Clear existing invoice
                        invoice = {};

                        // Pertama, inisialisasi semua stok dengan nilai dari database
                        document.querySelectorAll("#product-table-body tr").forEach(row => {
                            const id = row.getAttribute('data-product-id');
                            productStocks[id] = parseInt(row.cells[2].textContent);
                        });

                        // Kemudian load item yang ada di invoice
                        invoiceData.items.forEach(item => {
                            invoice[item.id] = {
                                id: item.id,
                                name: item.name,
                                price: item.price,
                                qty: item.qty,
                                stok: productStokMap[item.id] ?? 0
                            };

                            // Untuk status 'done', tetap tampilkan produk tanpa update stok
                            if (transactionStatus !== 'done' && productStokMap[item.id] !== undefined) {
                                // Jangan kurangi stok di sini, karena kita ingin tampilkan stok aktual
                                // productStocks[item.id] = productStokMap[item.id] - item.qty;
                            }
                        });

                        renderInvoice();

                        // Hanya update product table jika status bukan 'done'
                        if (transactionStatus !== 'done') {
                            updateProductTable();
                        }
                    }
                } catch (error) {
                    console.error('Error parsing invoice data:', error);
                }
            @endif
        }

        // Fungsi untuk melacak perubahan stok
        function updateStockChanges(id, initialStock, updatedStock) {
            if (!stockChanges[id]) {
                stockChanges[id] = {
                    id: id,
                    initialStock: initialStock,
                    updatedStock: updatedStock
                };
            } else {
                stockChanges[id].updatedStock = updatedStock;
            }
            updateStockHiddenField();
        }

        // Fungsi untuk mengupdate hidden field stok
        function updateStockHiddenField() {
            const stocksArray = Object.values(stockChanges);
            try {
                const jsonString = JSON.stringify(stocksArray);
                document.getElementById('updatedStocks').value = jsonString;
            } catch (e) {
                console.error("Error serializing stock data:", e);
            }
        }

        // Fungsi untuk menambahkan produk ke invoice
        function addToInvoice(id, name, price) {
            if (transactionStatus === 'done') return;

            // Ambil stok aktual dari tabel, bukan dari productStocks
            const currentStock = parseInt(document.querySelector(
                `#product-table-body tr[data-product-id="${id}"] td:nth-child(3)`).textContent);

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

        // Fungsi pembantu untuk mengupdate stok di tabel
        function updateProductStockInTable(id, newStock) {
            if (transactionStatus === 'done') return;

            const row = document.querySelector(`#product-table-body tr[data-product-id="${id}"]`);
            if (row) {
                row.cells[2].textContent = newStock;
                productStocks[id] = newStock;

                // Tampilkan kembali row jika stok > 0
                row.style.display = newStock <= 0 ? 'none' : '';
            }
        }


        // Fungsi untuk menghapus produk dari invoice
        function removeFromInvoice(id) {
            if (transactionStatus === 'done') return;

            if (invoice[id]) {
                invoice[id].qty -= 1;

                // Dapatkan stok saat ini dari tabel
                const currentStock = getCurrentStockFromTable(id);
                const newStock = currentStock + 1;

                // Catat perubahan stok
                updateStockChanges(id, currentStock, newStock);

                // Update tampilan
                updateProductStockInTable(id, newStock);

                if (invoice[id].qty <= 0) {
                    delete invoice[id];
                }
                renderInvoice();
            }
        }

        // Fungsi untuk mengubah quantity
        function changeQty(id, qty) {
            if (transactionStatus === 'done') return;

            qty = parseInt(qty);
            const currentItem = invoice[id];

            if (!currentItem) return;

            const currentStock = getCurrentStockFromTable(id);

            if (qty <= 0) {
                // Kembalikan stok
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

        // Fungsi untuk mendapatkan stok saat ini dari tabel
        function getCurrentStockFromTable(id) {
            const row = document.querySelector(`#product-table-body tr[data-product-id="${id}"]`);
            if (row) {
                return parseInt(row.cells[2].textContent);
            }
            return 0;
        }

        // Fungsi untuk render invoice - DIPERBAIKI
        function renderInvoice() {
            let body = '';
            let total = 0;

            // Ubah invoice (object) jadi array supaya mudah looping
            const items = Object.values(invoice);

            for (const item of items) {
                let subTotal = item.price * item.qty;
                total += subTotal;

                // Tampilkan input yang berbeda berdasarkan status
                const quantityInput = transactionStatus === 'done' ?
                    `<span>${item.qty}</span>` :
                    `<input type="number" value="${item.qty}" class="w-10 bg-gray-100 text-center" onchange="changeQty(${item.id}, this.value)">`;

                const actionButton = transactionStatus !== 'done' ?
                    `<td><button class="btn btn-sm btn-danger" onclick="removeFromInvoice(${item.id})"><i class="bi bi-dash"></i></button></td>` :
                    '';

                body += `
                <tr>
                    <td>${item.name}</td>
                    <td>${quantityInput}</td>
                    <td>Rp ${subTotal.toLocaleString('id-ID')}</td>
                    ${actionButton}
                </tr>
            `;
            }

            document.getElementById('invoice-body').innerHTML = body;
            document.getElementById('total-bayar').innerText = total.toLocaleString('id-ID');
            document.getElementById('total-bayar-final').value = total;

            // Update JSON produk di hidden input
            const jsonItems = items.map(item => ({
                id: item.id,
                qty: item.qty,
                name: item.name,
                price: item.price,
                subtotal: item.price * item.qty
            }));

            document.getElementById('json').value = JSON.stringify({
                items: jsonItems,
                total: total
            });
        }

        // Initialize when DOM is loaded
        document.addEventListener("DOMContentLoaded", function() {
            const productStocks = {};
            const rowsPerPage = 10;
            let currentPage = 1;
            const paginationContainer = document.createElement('div');
            paginationContainer.classList.add('mt-3', 'd-flex', 'justify-content-start');

            const allRows = Array.from(document.querySelectorAll("#product-table-body tr"));

            // Inisialisasi stok dan sembunyikan yang <= 0
            allRows.forEach(row => {
                const id = row.getAttribute('data-product-id');
                const stok = parseInt(row.cells[2].textContent);

                if (stok <= 0) {
                    row.classList.add('hide-stock');
                    row.style.display = 'none';
                }

                productStocks[id] = stok;
            });

            // Load invoice lama
            loadExistingInvoice?.();

            // Pencarian dengan debounce
            const input = document.getElementById("searchInput");
            const clearBtn = document.getElementById("searchDelete");
            let debounceTimer;

            input?.addEventListener("keyup", function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const keyword = input.value.toLowerCase();
                    allRows.forEach(row => {
                        const nama = row.cells[0].textContent.toLowerCase();
                        if (nama.includes(keyword)) {
                            row.classList.remove('hide-search');
                        } else {
                            row.classList.add('hide-search');
                        }
                    });
                    updatePaginationDisplay();
                }, 300);
            });

            clearBtn?.addEventListener("click", function() {
                input.value = '';
                input.dispatchEvent(new Event('keyup'));
            });

            function getVisibleRows() {
                return allRows.filter(row =>
                    !row.classList.contains('hide-stock') &&
                    !row.classList.contains('hide-search')
                );
            }

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
                const visibleRows = getVisibleRows();
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                allRows.forEach(row => row.style.display = 'none');
                visibleRows.forEach((row, index) => {
                    if (index >= start && index < end) {
                        row.style.display = '';
                    }
                });
            }

            function updatePaginationDisplay() {
                const visibleRows = getVisibleRows();
                const totalPages = Math.ceil(visibleRows.length / rowsPerPage);

                if (visibleRows.length > rowsPerPage) {
                    if (!document.contains(paginationContainer)) {
                        renderPagination(totalPages);
                    }
                    displayRows();
                } else {
                    if (document.contains(paginationContainer)) {
                        paginationContainer.remove();
                    }
                    allRows.forEach(row => {
                        if (!row.classList.contains('hide-stock') &&
                            !row.classList.contains('hide-search')) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            }

            updatePaginationDisplay();

            // Flatpickr
            const dateDisabledRaw = @json($dateDisabled);
            let dateDisabled = Object.values(dateDisabledRaw);
            const elemenTanggal = document.querySelector("#tanggal");
            if (elemenTanggal) {
                const isReadonly = "{{ $transaction->status }}" === 'done';
                const flatpickrConfig = {
                    locale: 'id',
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "j F Y",
                    disable: dateDisabled,
                    defaultDate: elemenTanggal.value || "today",
                    clickOpens: !isReadonly,
                    allowInput: !isReadonly,
                    disableMobile: true
                };
                const fp = flatpickr(elemenTanggal, flatpickrConfig);
                if (isReadonly) {
                    fp.close();
                    elemenTanggal.style.backgroundColor = '#f8f9fa';
                    elemenTanggal.readOnly = true;
                    elemenTanggal.classList.add('bg-light');
                }
            }

            // Counter Deskripsi
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

            // Simpan selesai
            document.getElementById('btnSelesai')?.addEventListener('click', function() {
                document.getElementById('status').value = 'done';
                document.getElementById('KirimanForm').submit();
            });
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
