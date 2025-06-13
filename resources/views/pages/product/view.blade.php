@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="container mt-4">

    <div class="container mt-3">
        <div class="breadcrumbs-container text-white">
            {!! Breadcrumbs::render('Product') !!}
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
            @include('components.feedback',[
                'type' =>'error',
                'message' => $error,
            ])
        @endforeach
    @endif


    <div class="bg-white rounded-lg shadow my-4 mx-2 p-4">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">

            <!-- Kiri: Tombol Tambah + Pencarian -->
            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">

                <!-- Tombol Tambah -->
                <a href="/produk/tambah" class="btn bg-green-900 text-white hover:bg-green-400 hover:text-black rounded-lg">
                    Tambah
                </a>

                <!-- Input Cari -->
                <div class="input-group">
                    <input id="searchInput" type="text" class="form-control" placeholder="Cari" aria-label="Pencarian">
                    <div class="input-group-append">
                        <span id="searchDelete" class="btn bg-red-800 text-white hover:bg-red-400 hover:text-black from-group-view">X</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE GENERATOR --}}
    @include('components.table', [
        'head' => 'Semua Produk',   {{-- JUDUL TABEL --}}
        'table' => 'products',      {{-- TABEL DATABASE --}}
        'sortBy' => 'name',         {{-- PENGURUTAN --}}
    ])
</div>
<div class="btn bg-green-900 text-white hover:bg-green-400 hover:text-black button-fixed-corner">
    <a href="/produk/tambah"><i class="bi bi-plus"></i></a>
</div>
@include('components.modal-delete')
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
            selectedDeleteUrl = `/produk/${id}`; // atau gunakan route jika perlu
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

