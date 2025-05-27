@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="mb-3">
        <a href="/produk/tambah">
            <button type="button" class="btn btn-success">Tambah</button>
        </a>
    </div>
    {{-- TABLE GENERATOR --}}
    @include('components.table', [
        'head' => 'Semua Produk',   {{-- JUDUL TABEL --}}
        'table' => 'Products',      {{-- TABEL DATABASE --}}
        'sortBy' => 'name',         {{-- PENGURUTAN --}}
    ])
@endsection
