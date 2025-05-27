@extends('layouts.app')

@section('title', 'Produk - Tambah')

@section('content')
    <div class="container mt-4">
    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nama Produk -->
        <div class="mb-3">
            <div class="mb-3">
                @include('components.form', [
                    'type' => 'text',
                    'label' => 'Nama',
                    'name' => 'name',
                    'place' => 'Masukkan Nama',
                    'value' => '',

                ])
            </div>
        </div>

        <!-- Harga Beli -->
        <div class="mb-3">
            <div class="mb-3">
                @include('components.form', [
                    'type' => 'number',
                    'label' => 'Harga Beli',
                    'name' => 'price1',
                    'place' => 'masukkan Harga',
                    'value' => '',
                ])
            </div>
        </div>

        <!-- Harga Beli -->
        <div class="mb-3">
            <div class="mb-3">
                @include('components.form', [
                    'type' => 'number',
                    'label' => 'Harga Grosir',
                    'name' => 'price2',
                    'place' => 'masukkan Harga',
                    'value' => '',
                ])
            </div>
        </div>

        <div class="mb-3">
            <div class="mb-3">
                @include('components.form', [
                    'type' => 'file',
                    'label' => 'Masukkan Gambar',
                    'name' => 'photo',
                    'place' => '',
                ])
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mb-3">
            <div class="mb-3">
                @include('components.form', [
                    'type' => 'textarea',
                    'label' => 'Deskripsi',
                    'name' => 'description',
                    'place' => 'Masukkan Deskripsi',
                    'value' => '',
                ])
            </div>
        </div>

        <!-- Submit -->
        <div class="mb-3">
            <div class="mb-3">
                @include('components.form',[
                    'type' => 'submit',
                    'label' => 'Simpan'
                ])
            </div>
        </div>
    </form>
</div>
@endsection
