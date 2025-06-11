@php
    // dd($data);
@endphp
@extends('layouts.app')

@section('title', 'Profil')

@section('content')

    <div class="container mt-3">
        <div class="breadcrumbs-container text-white">
            {!! Breadcrumbs::render('Profile') !!}
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

    <div class="container mt-4">
        <div class="col bg-white rounded-lg shadow my-4 mx-2 w-fit d-flex align-items-center gap-3">
            <div class="btn bg-green-900 text-white hover:bg-green-400 hover:text-black rounded-lg my-3 mx-3">
                <a href="/Beranda">Kembali</a>
            </div>
            <span class="md:mx-40 mr-10 fw-bold">Detail Profil</span>
        </div>

        <div class="container bg-white shadow p-4 rounded-lg">
            <h1 class="display-6 fw-bold mb-4">Data Diri</h1>
            <div class="row">
                <div class="col-md-6 mb-3">
                    @include('components.form', [
                        'type' => 'text',
                        'label' => 'Nama Depan',
                        'name' => 'firstname',
                        'place' => 'Nama Depan',
                        'value' => old('firstname', $data[0]->firstname ?? ''),
                        'addon' => 'readonly',
                    ])
                </div>
                <div class="col-md-6 mb-3">
                    @include('components.form', [
                        'type' => 'text',
                        'label' => 'Nama Belakang',
                        'name' => 'lastname',
                        'place' => 'Nama Depan',
                        'value' => old('lastname', $data[0]->lastname ?? ''),
                        'addon' => 'readonly',
                    ])
                </div>
                <div class="col-md-6 mb-3">
                    @include('components.form', [
                        'type' => 'text',
                        'label' => 'Username Login',
                        'name' => 'username',
                        'place' => 'Nama Depan',
                        'value' => old('username', $data[0]->username ?? ''),
                        'addon' => 'readonly',
                    ])
                </div>
                <div class="col-md-6 mb-3">
                    @include('components.form', [
                        'type' => 'text',
                        'label' => 'Tipe Pengguna',
                        'name' => 'role',
                        'place' => 'Nama Depan',
                        'value' => old('role', $data[0]->role ?? ''),
                        'addon' => 'readonly',
                    ])
                </div>
            </div>
            <div class="flex justify-center">
                <button class="btn bg-yellow-700 text-white hover:bg-yellow-400 hover:text-white rounded-md" data-bs-toggle="modal" data-bs-target="#editModal">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
            </div>

        </div>
    </div>

    <!-- Modal Form Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('user.update', [$data[0]->id, 'profil']) }}">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include('components.form', [
                            'type' => 'text',
                            'label' => 'Nama Depan',
                            'name' => 'firstname',
                            'place' => 'Nama Depan',
                            'value' => old('firstname', $data[0]->firstname ?? ''),
                            'addon' => '',
                        ])

                        @include('components.form', [
                            'type' => 'text',
                            'label' => 'Nama Belakang',
                            'name' => 'lastname',
                            'place' => 'Nama Belakang',
                            'value' => old('lastname', $data[0]->lastname ?? ''),
                            'addon' => '',
                        ])

                        @include('components.form', [
                            'type' => 'text',
                            'label' => 'Username',
                            'name' => 'username',
                            'place' => 'Username',
                            'value' => old('username', $data[0]->username ?? ''),
                            'addon' => '',
                        ])

                        @include('components.form', [
                            'type' => 'password',
                            'label' => 'Password',
                            'name' => 'password',
                            'place' => 'Kosongkan jika tidak ingin ubah',
                            'value' => '',
                            'addon' => '',
                        ])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-red-800 text-white hover:bg-red-400 hover:text-black	" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn bg-blue-900 text-white hover:bg-blue-400 hover:text-black">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('changePassword').addEventListener('click', function() {
            form.classList.toggle('d-none');
        });
    </script>
@endpush
