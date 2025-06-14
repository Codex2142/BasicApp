@extends('layouts.app')

@section('title', 'Kiriman - Edit')

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
                                class="form-control border rounded p-2 w-full" />
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
        const productStokMap = @json($products->pluck('stok', 'id'));
        const transactionStatus = "{{ $transaction->status }}";
        let invoice = {};
        let stockChanges = {};


        function updateStockHiddenField() {
            const stocksArray = Object.values(stockChanges);
            try {
                const jsonString = JSON.stringify(stocksArray);
                document.getElementById('updatedStocks').value = jsonString;
                console.log("Updated stocks:", jsonString); // Untuk debugging
            } catch (e) {
                console.error("Error serializing stock data:", e);
            }
        }

        function updateProductStock(id, change) {
            const rows = document.querySelectorAll("#product-table tbody tr");
            rows.forEach(row => {
                if (row.getAttribute('data-product-id') == id) {
                    const stokCell = row.cells[2];
                    let currentStock = parseInt(stokCell.textContent);
                    const newStock = currentStock + change;

                    stokCell.textContent = newStock;

                    // Simpan perubahan stok
                    if (!stockChanges[id]) {
                        stockChanges[id] = {
                            id: id,
                            initialStock: currentStock - change, // Stok awal sebelum perubahan
                            updatedStock: newStock
                        };
                    } else {
                        stockChanges[id].updatedStock = newStock;
                    }

                    // Update hidden form
                    document.getElementById('updatedStocks').value = JSON.stringify(Object.values(stockChanges));

                    // Update stok di objek invoice (jika ada)
                    if (invoice[id]) {
                        invoice[id].stok = newStock;
                    }
                }
            });
        }

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
                    id,
                    name,
                    price,
                    qty: 1,
                    stok: currentStock
                };
            }

            updateProductStock(id, -1);
            renderInvoice();
        }

        function getCurrentStockFromTable(id) {
            const rows = document.querySelectorAll("#product-table tbody tr");
            for (const row of rows) {
                // Asumsikan kolom nama ada di sel pertama (index 0)
                if (row.getAttribute('data-product-id') == id) {
                    // Asumsikan kolom stok ada di sel ketiga (index 2)
                    return parseInt(row.cells[2].textContent);
                }
            }
            return 0;
        }

        // function updateProductStock(id, change) {
        //     // Cari baris produk di tabel
        //     const rows = document.querySelectorAll("#product-table tbody tr");
        //     rows.forEach(row => {
        //         if (row.cells[0].textContent === invoice[id]?.name) {
        //             const stokCell = row.cells[2];
        //             let currentStock = parseInt(stokCell.textContent);
        //             stokCell.textContent = currentStock + change;

        //             // Update juga stok di objek produk jika diperlukan
        //             if (invoice[id]) {
        //                 invoice[id].stok += change;
        //             }
        //         }
        //     });
        // }


        function removeFromInvoice(id) {
            if (invoice[id]) {
                invoice[id].qty -= 1;
                updateProductStock(id, 1);

                if (invoice[id].qty <= 0) {
                    delete invoice[id];
                }
                renderInvoice();
            }
        }

        function changeQty(id, qty) {
            qty = parseInt(qty);
            if (qty <= 0) {
                // Kembalikan semua stok jika dihapus
                updateProductStock(id, invoice[id].qty);
                delete invoice[id];
            } else {
                const currentStock = getCurrentStockFromTable(id);
                const requestedQty = qty - (invoice[id]?.qty || 0);

                if (requestedQty > currentStock) {
                    alert(`Stok tidak mencukupi. Hanya tersedia ${currentStock}`);
                    return;
                }

                // Update stok di tabel produk
                updateProductStock(id, -requestedQty);
                invoice[id].qty = qty;
            }
            renderInvoice();
        }


        function renderInvoice() {
            let body = '';
            let total = 0;

            // Ubah invoice (object) jadi array supaya mudah looping
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
                        <button class="btn btn-sm btn-danger" onclick="removeFromInvoice(${item.id})"><i class="bi bi-dash"></i></button>
                    </td>
                ` : ''}
                </tr>
            `;
            }

            document.getElementById('invoice-body').innerHTML = body;
            document.getElementById('total-bayar').innerText = total.toLocaleString('id-ID');
            document.getElementById('total-bayar-final').value = total;

            // Update JSON produk di hidden input
            // Format harus sama seperti semula: { items: [...] }
            const jsonItems = items.map(item => ({
                id: item.id,
                qty: item.qty,
                name: item.name,
                price: item.price,
                subtotal: item.price * item.qty
            }));
            const jsonData = {
                items: jsonItems,
                total: total
            };
            document.getElementById('json').value = JSON.stringify(jsonData);
        }

        document.addEventListener("DOMContentLoaded", function() {
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
            });

            clearBtn?.addEventListener("click", function() {
                input.value = '';
                input.dispatchEvent(new Event('keyup'));
            });

            // Pagination produk
            const allRows = Array.from(document.querySelectorAll("#product-table-body tr"));
            const rowsPerPage = 10;
            const pagination = document.getElementById('pagination');
            let currentPage = 1;

            function showPage(page) {
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                allRows.forEach((row, idx) => {
                    row.style.display = idx >= start && idx < end ? '' : 'none';
                });
            }

            if (allRows.length > rowsPerPage) {
                // Buat pagination jika diperlukan (opsional)
            } else {
                showPage(1);
            }

            // Load data lama (old) dari input hidden product JSON
            const oldJson = document.getElementById('json').value;
            if (oldJson) {
                try {
                    const oldInvoiceData = JSON.parse(oldJson);

                    // Pastikan oldInvoiceData punya properti items berupa array
                    if (oldInvoiceData && Array.isArray(oldInvoiceData.items)) {
                        // Buat invoice object keyed by id
                        invoice = {};
                        oldInvoiceData.items.forEach(item => {
                            invoice[item.id] = {
                                id: item.id,
                                name: item.name,
                                price: item.price,
                                qty: item.qty,
                                stok: productStokMap[item.id] ?? 9999
                            };
                        });
                        renderInvoice();
                    } else {
                        invoice = {};
                    }
                } catch (error) {
                    console.error('JSON produk lama tidak valid:', error);
                    invoice = {};
                }
            }

            renderInvoice();

            // Button simpan permanen
            btnSelesai?.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
                modal.show();
            });

            // Konfirmasi simpan permanen dari modal
            document.getElementById('confirmSaveBtn')?.addEventListener('click', function() {
                document.getElementById('status').value = 'done';
                document.getElementById('KirimanForm').submit();
            });

            // Objek datedisabled menjadi array
            const dateDisabledRaw = @json($dateDisabled);
            let dateDisabled = Object.values(dateDisabledRaw);

            const elemenTanggal = document.querySelector("#tanggal");
            console.log("Elemen tanggal:", elemenTanggal);
            if (elemenTanggal) {
                flatpickr(elemenTanggal, {
                    locale: 'id',
                    disable: dateDisabled,
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    defaultDate: elemenTanggal.value || "today",
                });
            } else {
                console.warn("Elemen #tanggal tidak ditemukan.");
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const deskripsiTextarea = document.querySelector('textarea[name="description"]');
            const charCount = document.getElementById('wordCountInfo');
            const maxChar = 30;

            if (deskripsiTextarea && charCount) {
                function updateCharCount() {
                    let length = deskripsiTextarea.value.length;

                    if (length > maxChar) {
                        deskripsiTextarea.value = deskripsiTextarea.value.slice(0, maxChar);
                        length = maxChar;
                    }

                    charCount.textContent = `${length} / ${maxChar} karakter`;

                    if (length === maxChar) {
                        charCount.classList.add('text-danger');
                    } else {
                        charCount.classList.remove('text-danger');
                    }
                }

                deskripsiTextarea.addEventListener('input', updateCharCount);

                // Panggil sekali saat pertama kali halaman dibuka
                updateCharCount();
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
