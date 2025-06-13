@extends('layouts.app')

@section('title', 'Produk - Tambah')

@section('content')
    <div class="container mt-4">

        <div class="container mt-3">
            <div class="breadcrumbs-container text-white">
                {!! Breadcrumbs::render('ProductAdd') !!}
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
                <a href="/produk">Kembali</a>
            </div>
            <span class="md:mx-40 mr-10 fw-bold"> Tambah Produk</span>
        </div>

        <div class="container shadow p-3 bg-white mb-3 rounded-lg">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
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
                    </div>
                    <div class="col-md-6">
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
                    </div>

                    <div class="col-md-6">
                        <!-- Harga Pasar -->
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
                    </div>
                    <div class="col-md-6">
                        <!-- Harga Grosir -->
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
                    </div>

                    <div class="col-md-6">
                        <!-- Stock -->
                        <div class="mb-3">
                            <div class="mb-3">
                                @include('components.form', [
                                    'type' => 'number',
                                    'label' => 'Stok',
                                    'name' => 'stock',
                                    'place' => 'masukkan stok',
                                    'value' => '',
                                ])
                            </div>
                        </div>
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
                        <button class="btn bg-blue-900 text-white hover:bg-blue-400 hover:text-black submit" type="submit">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
