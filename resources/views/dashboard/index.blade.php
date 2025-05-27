@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1>Ini dashboard</h1>

{{-- TABLE GENERATOR --}}
@include('components.table', [
    'head' => 'Semua Produk',   {{-- JUDUL TABEL --}}
    'table' => 'Products',      {{-- TABEL DATABASE --}}
    'sortBy' => 'name',         {{-- PENGURUTAN --}}
])

@endsection
