@extends('layouts.app')

@section('title', 'Kiriman - Tambah')

@section('content')
    <div class="container mt-4 ">
        <div class="container mt-3">
            <div class="breadcrumbs-container text-white">
                {!! Breadcrumbs::render('TransactionAdd') !!}
            </div>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                @include('components.feedback', [
                    'type' => 'error',
                    'message' => $error,
                ])
            @endforeach
        @endif

        <div class="col bg-white rounded-lg shadow my-4 mx-2 w-fit d-flex align-items-center gap-3">
            <div class="btn bg-green-900 text-white hover:bg-green-400 hover:text-black rounded-lg my-3 mx-3">
                <a href="/Kiriman">Kembali</a>
            </div>
            <span class="md:mx-40 mr-10 fw-bold"> Tambah Kiriman</span>
        </div>

        <div class="container shadow p-3 bg-white mb-3 rounded-lg">
            <div class="row">
                <!-- Kolom Produk -->
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
                    <table class="table table-auto table-hover" id="product-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="product-table-body">
                            @foreach ($result as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>Rp {{ number_format($product->price2, 0, ',', '.') }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        <button class="btn btn-sm bg-blue-900 text-white hover:bg-blue-400 hover:text-black"
                                            onclick="addToInvoice({{ $product->id }}, '{{ $product->name }}', {{ $product->price2 }}, {{ $product->stock }})">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Kolom Invoice -->
                <div class="col-md-6 mt-lg-6">
                    <h1 class="fw-bold display-6 text-center">Keranjang</h1>
                    <div class="table-responsive">
                        <table class="table table-auto table-hover" id="invoice-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Produk</th>
                                    <th>Stok</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
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
            <form action="{{ route('transaction.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            @include('components.form', [
                                'type' => 'text',
                                'label' => 'Tanggal',
                                'name' => 'tanggal',
                                'place' => 'Pilih Tanggal',
                                'value' => '',
                            ])
                        </div>
                        <div class="mb-3">
                            <label for="total" class="form-label block mb-1 font-medium">Total</label>
                            <input type="number" id="total-bayar-final" name="total" placeholder=""
                                class="form-control border rounded p-2 w-full bg-gray-200" readonly />
                        </div>

                        {{-- JSON INPUT --}}
                        <input type="hidden" id="json" name="product" class="d-none">
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            @include('components.form', [
                                'type' => 'textarea',
                                'label' => 'Deskripsi',
                                'name' => 'description',
                                'place' => 'Masukkan Deskripsi',
                                'value' => '',
                                'addon' => 'autocomplete="off"',
                            ])
                            <small id="wordCountInfo" class="text-sm text-gray-500">0 / 30 kata</small>
                        </div>

                        <div class="mb-3 d-none">
                            @include('components.form', [
                                'type' => 'radio',
                                'label' => 'Status',
                                'name' => 'status',
                                'place' => '',
                                'data' => [
                                    'pending' => 'Menunggu',
                                    'done' => 'Selesai',
                                ],
                                'value' => 'pending',
                            ])
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit"
                        class="btn bg-blue-900 text-white hover:bg-blue-400 hover:text-black px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let invoice = {};
        let productStocks = {};

        document.querySelectorAll("#product-table-body tr").forEach(row => {
            const id = parseInt(row.querySelector("button").getAttribute("onclick").match(/\d+/)[0]);
            const stock = parseInt(row.cells[2].innerText);
            productStocks[id] = stock;
        });

        function addToInvoice(id, name, price, stock) {
            if (invoice[id]) {
                if (invoice[id].qty < invoice[id].stock) {
                    invoice[id].qty += 1;
                    productStocks[id] -= 1;
                } else {
                    alert("Stok produk sudah maksimal.");
                    return;
                }
            } else {
                invoice[id] = {
                    name,
                    price,
                    qty: 1,
                    stock: stock
                };
                productStocks[id] -= 1;
            }
            renderInvoice();
            updateProductTable();
        }

        function removeFromInvoice(id) {
            if (invoice[id]) {
                invoice[id].qty -= 1;
                productStocks[id] += 1;

                if (invoice[id].qty <= 0) {
                    delete invoice[id];
                }
                renderInvoice();
                updateProductTable();
            }
        }

        function renderInvoice() {
            let body = '';
            let total = 0;
            let items = [];

            for (const [id, item] of Object.entries(invoice)) {
                let subTotal = item.price * item.qty;
                total += subTotal;

                body += `
                    <tr>
                        <td>${item.name}</td>
                        <td><input type="number" value="${item.qty}" max="${item.stock}" class="w-10 bg-gray-100 text-center" onchange="changeQty('${id}', this.value)"></td>
                        <td>Rp ${subTotal.toLocaleString('id-ID')}</td>
                        <td>
                            <button class="btn btn-sm bg-red-800 text-white hover:bg-red-400 hover:text-black" onclick="removeFromInvoice('${id}')"><i class="bi bi-dash"></i></button>
                        </td>
                    </tr>
                `;

                // Tambah ke array items
                items.push({
                    id: parseInt(id),
                    name: item.name,
                    price: item.price,
                    qty: item.qty,
                    subtotal: subTotal
                });
            }

            document.getElementById('invoice-body').innerHTML = body;
            document.getElementById('total-bayar').innerText = total.toLocaleString('id-ID');
            document.getElementById('total-bayar-final').value = total;

            // Bentuk JSON sesuai permintaan
            const jsonOutput = {
                items: items,
                total: total
            };
            document.getElementById('json').value = JSON.stringify(jsonOutput);
        }
        updateProductTable();

        function changeQty(id, qty) {
            qty = parseInt(qty);
            if (qty > invoice[id].stock) {
                alert(`Jumlah melebihi stok maksimal (${invoice[id].stock})`);
                renderInvoice();
                return;
            }

            if (qty > 0) {
                const diff = qty - invoice[id].qty;
                if (diff > 0 && diff <= productStocks[id]) {
                    invoice[id].qty = qty;
                    productStocks[id] -= diff;
                } else if (diff < 0) {
                    invoice[id].qty = qty;
                    productStocks[id] += Math.abs(diff);
                } else if (diff > productStocks[id]) {
                    alert(`Stok tidak cukup. Sisa stok: ${productStocks[id]}`);
                    renderInvoice();
                    return;
                }
            } else {
                productStocks[id] += invoice[id].qty;
                delete invoice[id];
            }

            renderInvoice();
            updateProductTable();
        }



        function updateProductTable() {
            document.querySelectorAll("#product-table-body tr").forEach(row => {
                const id = parseInt(row.querySelector("button").getAttribute("onclick").match(/\d+/)[0]);
                const stockCell = row.cells[2];
                const stock = productStocks[id];

                stockCell.innerText = stock;

                row.style.display = stock <= 0 ? 'none' : '';
            });
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

            const dateDisabled = @json($dateDisabled);
            const elemenTanggal = document.querySelector("#tanggal");
            if (elemenTanggal) {
                flatpickr(elemenTanggal, {
                    locale: 'id',
                    disable: dateDisabled,
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    defaultDate: "today",
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
                deskripsiTextarea.addEventListener('input', function() {
                    let length = this.value.length;

                    // Potong jika lebih dari 30 karakter
                    if (length > maxChar) {
                        this.value = this.value.slice(0, maxChar);
                        length = maxChar;
                    }

                    charCount.textContent = `${length} / ${maxChar} karakter`;

                    // Optional: warna merah jika penuh
                    if (length === maxChar) {
                        charCount.classList.add('text-danger');
                    } else {
                        charCount.classList.remove('text-danger');
                    }
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
