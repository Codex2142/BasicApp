@extends('layouts.app')

@section('title', 'User - Tambah')

@section('content')
    <div class="container mt-4">

        <div class="container mt-3">
            <div class="breadcrumbs-container text-white">
                {!! Breadcrumbs::render('UserAdd') !!}
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
            <div class="btn btn-success rounded-lg my-3 mx-3">
                <a href="/user">Kembali</a>
            </div>
            <span class="md:mx-40 mr-10 fw-bold"> Tambah Produk</span>
        </div>

        <div class="container shadow rounded-md bg-white py-4">
            <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Firstname -->
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <div class="mb-3">
                                @include('components.form', [
                                    'type' => 'text',
                                    'label' => 'Nama Awal',
                                    'name' => 'firstname',
                                    'place' => 'Masukkan Nama',
                                    'value' => '',
                                    'addon' => 'autocomplete="off"',
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <div class="mb-3">
                                @include('components.form', [
                                    'type' => 'text',
                                    'label' => 'Nama Akhir',
                                    'name' => 'lastname',
                                    'place' => 'Masukkan Nama',
                                    'value' => '',
                                    'addon' => 'autocomplete="off"',
                                ])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <div class="mb-3">
                                @include('components.form', [
                                    'type' => 'text',
                                    'label' => 'Username Akun',
                                    'name' => 'username',
                                    'place' => 'Masukkan Nama',
                                    'value' => '',
                                    'addon' => 'autocomplete="off"',
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <div class="mb-3">
                                @include('components.form', [
                                    'type' => 'text',
                                    'label' => 'Password',
                                    'name' => 'password',
                                    'place' => 'Password',
                                    'value' => 'TokoZakiah440033',
                                    'addon' => 'autocomplete="off"',
                                ])

                                <div class="d-none">
                                    @include('components.form', [
                                        'type' => 'text',
                                        'label' => 'Nama Awal',
                                        'name' => 'role',
                                        'place' => '',
                                        'value' => 'user',
                                        'addon' => 'autocomplete="off"',
                                    ])
                                </div>
                            </div>
                        </div>
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

    </div>
@endsection
