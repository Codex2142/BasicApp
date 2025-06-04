@extends('layouts.app')

@section('title', 'Produk - Tambah')

@section('content')
    <div class="container mt-4">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                @include('components.feedback', [
                    'type' => 'error',
                    'message' => $error,
                ])
            @endforeach
        @endif

        <div class="col bg-white rounded-lg shadow my-4 mx-2 w-fit d-flex align-items-center gap-3">
            <div class="btn btn-success rounded-lg my-3 mx-3">
                <a href="/produk">Kembali</a>
            </div>
            <span class="md:mx-40 mr-10 fw-bold"> Tambah Produk</span>
        </div>

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
                        'addon' => 'autocomplete="off"',
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
                    @include('components.form', [
                        'type' => 'submit',
                        'label' => 'Simpan',
                    ])
                </div>
            </div>
        </form>
    </div>
@endsection
