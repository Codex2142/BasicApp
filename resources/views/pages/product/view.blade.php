@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="container mt-4">
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

    <div class="col bg-white rounded-lg shadow my-4 mx-2 w-fit d-flex align-items-center gap-3">
        <div class="btn btn-success rounded-lg my-3 mx-3">
            <a href="/produk/tambah">Tambah</a>
        </div>
        <div class="input-group">
            <input id="searchInput" type="text" class="form-control" placeholder="Cari" aria-label="Recipient's username" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <span id="searchDelete" class="btn btn-danger from-group-view">X</span>
            </div>
        </div>
    </div>
    {{-- TABLE GENERATOR --}}
    @include('components.table', [
        'head' => 'Semua Produk',   {{-- JUDUL TABEL --}}
        'table' => 'Products',      {{-- TABEL DATABASE --}}
        'sortBy' => 'name',         {{-- PENGURUTAN --}}
    ])
</div>
<div class="btn btn-success button-fixed-corner">
    <a href="/produk/tambah"><i class="bi bi-plus"></i></a>
</div>
@endsection

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
</script>

