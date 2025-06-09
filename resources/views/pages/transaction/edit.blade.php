@extends('layouts.app')

@section('title', 'Transaksi - Edit')

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
            <div class="btn btn-success rounded-lg my-3 mx-3">
                <a href="/transaksi">Kembali</a>
            </div>
            <span class="md:mx-40 mr-10 fw-bold">{{ $transaction->status == 'done' ? 'Detail' : 'Edit' }} Transaksi</span>
        </div>

        <div class="container shadow p-3 bg-white mb-3 rounded-lg">
            <div class="row">
                @if ($transaction->status != 'done')
                    <div class="col-12 col-md-6 mb-3">
                        <div class="input-group">
                            <input id="searchInput" type="text" class="form-control" placeholder="Cari Produk"
                                aria-label="Pencarian">
                            <div class="input-group-append">
                                <span id="searchDelete" class="btn btn-danger from-group-view">X</span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-12 col-md-6 {{ $transaction->status == 'done' ? 'd-none' : '' }}"></div>

                @if ($transaction->status != 'done')
                    <div class="col-md-6 md:sm-5">
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
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>Rp {{ number_format($product->price2, 0, ',', '.') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary"
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
                    <h1 class="fw-bold display-6">Keranjang</h1>
                    <div class="table-responsive">
                        <table class="table table-auto" id="invoice-table">
                            <thead>
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
                id="transaksiForm">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            @include('components.form', [
                                'type' => 'date',
                                'label' => 'Tanggal',
                                'name' => 'tanggal',
                                'place' => '',
                                'value' => old('tanggal', $transaction->tanggal),
                                'addon' => $transaction->status == 'done' ? 'readonly' : '',
                            ])
                        </div>

                        <div class="mb-3">
                            <label for="total" class="form-label block mb-1 font-medium">Total</label>
                            <input type="number" id="total-bayar-final" name="total" placeholder=""
                                class="form-control border rounded p-2 w-full bg-gray-200" readonly
                                value="{{ old('total', $transaction->total) }}" />
                        </div>

                        <input type="hidden" id="json" name="product"
                            value="{{ old('product', $transaction->product) }}">
                        <input type="hidden" id="status" name="status" value="pending">
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
                        </div>
                    </div>
                </div>

                @if ($transaction->status != 'done')
                    <div class="mb-3 d-flex justify-between gap-2">
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded">SIMPAN SEMENTARA</button>
                        @if (Auth::user()->role === 'admin')
                            <button type="button" id="btnSelesai" class="btn btn-danger px-4 py-2 rounded">SIMPAN
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
        const transactionStatus = "{{ $transaction->status }}";
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
                if (invoice[id].qty <= 0) delete invoice[id];
            }
            renderInvoice();
        }

        function changeQty(id, qty) {
            qty = parseInt(qty);
            if (qty > 0) invoice[id].qty = qty;
            else delete invoice[id];
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
                    <td><input type="number" ${transactionStatus !== 'done' ? '' : 'readonly'} value="${item.qty}" class="w-10 bg-gray-100 text-center" onchange="changeQty(${id}, this.value)"></td>
                    <td>Rp ${subTotal.toLocaleString('id-ID')}</td>
                    ${transactionStatus !== 'done' ? `
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="removeFromInvoice(${id})"><i class="bi bi-dash"></i></button>
                                </td>
                            ` : ''}
                </tr>
            `;
            }

            document.getElementById('invoice-body').innerHTML = body;
            document.getElementById('total-bayar').innerText = total.toLocaleString('id-ID');
            document.getElementById('total-bayar-final').value = total;

            const jsonInput = {};
            for (const [id, item] of Object.entries(invoice)) {
                jsonInput[id] = item.qty;
            }
            document.getElementById('json').value = JSON.stringify(jsonInput);
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Search
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

            // Pagination
            const allRows = Array.from(document.querySelectorAll("#product-table-body tr"));
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
                document.getElementById('product-table')?.after(paginationContainer);
            }

            function displayRows() {
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                allRows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? '' : 'none';
                });
            }

            if (allRows.length > 0) {
                const totalPages = Math.ceil(allRows.length / rowsPerPage);
                renderPagination(totalPages);
                displayRows();
            }

            // Re-render invoice from old input
            const oldProductsJson = document.getElementById('json').value;
            if (oldProductsJson) {
                try {
                    const oldProducts = JSON.parse(oldProductsJson);
                    const productsFromBlade = @json(
                        $products->keyBy('id')->map(function ($p) {
                            return ['name' => $p->name, 'price' => $p->price2];
                        }));

                    for (const [id, qty] of Object.entries(oldProducts)) {
                        if (productsFromBlade[id]) {
                            invoice[id] = {
                                name: productsFromBlade[id].name,
                                price: productsFromBlade[id].price,
                                qty: qty
                            };
                        }
                    }
                    renderInvoice();
                } catch (e) {
                    console.error("Gagal parsing produk lama:", e);
                }
            }

            // SIMPAN PERMANEN: ubah status ke done dan tampilkan modal
            document.getElementById('btnSelesai')?.addEventListener('click', function() {
                document.getElementById('status').value = 'done';
                const myModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                myModal.show();
            });

            // Submit form setelah konfirmasi modal
            document.getElementById('confirmSaveBtn')?.addEventListener('click', function() {
                document.getElementById('transaksiForm').submit();
            });
        });
    </script>
@endpush
