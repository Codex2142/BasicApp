@extends('layouts.app')

@section('title', 'Pembelian')
@php
    // dd($invoice);
@endphp
@section('content')
    <div class="container mt-4">

        <div class="container mt-3">
            <div class="breadcrumbs-container text-white">
                {!! Breadcrumbs::render('Pembelian') !!}
            </div>
        </div>

        @if (session('success'))
            @include('components.feedback', [
                'type' => 'success',
                'message' => session('success'),
            ])
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                @include('components.feedback', [
                    'type' => 'error',
                    'message' => $error,
                ])
            @endforeach
        @endif

        <div class="bg-white rounded-lg shadow my-4 mx-2 p-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">

                <!-- Kiri: Tombol Tambah + Pencarian -->
                <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">

                    <!-- Tombol Tambah -->
                    <a href="/pembelian/tambah/retail"
                        class="btn bg-green-900 text-white hover:bg-green-400 hover:text-black rounded-lg d-flex">
                        <i class="bi bi-plus"></i> <p>Retail</p>
                    </a>

                    <!-- Tombol Tambah -->
                    <a href="/pembelian/tambah/grosir"
                        class="btn bg-green-900 text-white hover:bg-green-400 hover:text-black rounded-lg d-flex">
                        <i class="bi bi-plus"></i> <p>Grosir</p>
                    </a>

                    <!-- Input Cari -->
                    <div class="input-group">
                        <input id="searchInput" type="text" class="form-control" placeholder="Cari Produk"
                            aria-label="Pencarian">
                        <div class="input-group-append">
                            <span id="searchDelete"
                                class="btn bg-red-800 text-white hover:bg-red-400 hover:text-black from-group-view">X</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto mb-40" id="tableGenerator">
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <h1 class="text-2xl font-semibold mb-4 my-3 mx-3">Pembelian</h1>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>

                            {{-- NAMA NAMA KOLOM DINAMIS --}}
                            @php
                                $label = ['Tanggal', 'Produk', 'Jumlah', 'Subtotal', 'Total', 'Deskripsi', 'type'];
                            @endphp
                            @foreach ($label as $l)
                                <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    {{ $l }}
                                </th>
                            @endforeach
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        {{-- LOOPING BARIS --}}
                        @if ($invoice)
                            @foreach ($invoice as $d)
                                <tr>
                                    <td class="px-4 py-3 fw-bold">{{ $loop->iteration }}</td>

                                    <td class="px-4 py-3">{{ $d['tanggal'] }}</td>
                                    <td class="px-4 py-3">{{ $d['product'] }}</td>
                                    <td class="px-4 py-3">{{ $d['qty'] }}</td>
                                    <td class="px-4 py-3">Rp {{ number_format($d['subtotal'] , 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">Rp {{ number_format($d['total'], 0, ',', '.')  }}</td>
                                    <td class="px-4 py-3">{{ $d['description'] }}</td>
                                    <td class="px-4 py-3">{{ $d['type'] }}</td>

                                    <td class="px-4 py-3">
                                        <div class="flex justify-around space-x-2">
                                            <span
                                                class="bg-yellow-700 text-white hover:bg-yellow-400 hover:text-white rounded-lg py-2 px-3">
                                                <a href="{{ route('invoice.edit', $d['idInvoice']) }}">
                                                    <button class="hover:underline"><i
                                                            class="bi bi-pencil-square"></i></button>
                                                </a>
                                            </span>
                                            <span
                                                class="bg-red-800 text-white hover:bg-red-400 hover:text-black rounded-lg py-2 px-3">
                                                <button class="text-white hover:underline btn-table-delete"
                                                    data-id="{{ $d['idInvoice'] }}"><i class="bi bi-trash"></i></button>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ count($label) + 2 }}">
                                    <p class="text-center py-2 text-gray-600">Tidak ada Data</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-100 border-t text-center">
                {{ $invoice->links() }}
            </div>
        </div>
        @include('components.modal-delete')
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById('searchInput');
        const searchDelete = document.getElementById('searchDelete');
        const table = document.querySelector("table");
        const rows = table.querySelectorAll("tbody tr");

        // Event: ketika input diketik
        searchInput.addEventListener("keyup", function () {
            const query = this.value.toLowerCase();
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });

        // Event: tombol hapus diklik
        searchDelete.addEventListener("click", function () {
            searchInput.value = "";
            rows.forEach(row => row.style.display = "");
        });
    });

    let selectedDeleteUrl = '';

    // Untuk table delete
    document.querySelectorAll('.btn-table-delete').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            selectedDeleteUrl = `/pembelian/${id}`; // atau gunakan route jika perlu
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        });
    });

    // Saat tombol konfirmasi "Ya, Hapus" ditekan
    document.getElementById('confirmSaveBtn').addEventListener('click', function () {
        if (selectedDeleteUrl) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = selectedDeleteUrl;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>
@endpush
