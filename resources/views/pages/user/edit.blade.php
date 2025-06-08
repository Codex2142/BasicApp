@extends('layouts.app')

@section('title', 'User - Edit')

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
                <a href="/user">Kembali</a>
            </div>
            <span class="md:mx-40 mr-10 fw-bold"> Tambah Produk</span>
        </div>

        <div class="container shadow rounded-md bg-white py-4">
            <form action="{{ route('user.update', $data[0]->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Firstname -->
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <div class="mb-3">
                                @include('components.form', [
                                    'type' => 'text',
                                    'label' => 'Nama Awal   ',
                                    'name' => 'firstname',
                                    'place' => 'Masukkan Nama Awal',
                                    'value' => old('firstname', $data[0]->firstname ?? ''),
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
                                    'place' => 'Masukkan Nama Akhir',
                                    'value' => old('lastname', $data[0]->lastname ?? ''),
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
                                    'label' => 'Username',
                                    'name' => 'username',
                                    'place' => 'Masukkan Nama Akhir',
                                    'value' => old('username', $data[0]->username ?? ''),
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="password" class="form-label block mb-1 font-medium">Password</label>
                                <div class="mb-2">
                                    <button type="button" class="btn btn-warning btn-sm" id="changePassword">Ganti
                                        Password?</button>
                                </div>

                                <div id="passwordForm" class="d-none transition">
                                    @include('components.form', [
                                        'type' => 'password',
                                        'label' => '',
                                        'name' => 'password',
                                        'place' => 'Password Baru',
                                        'value' => '',
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

@push('scripts')
    <script>
        document.getElementById('changePassword').addEventListener('click', function() {
            const form = document.getElementById('passwordForm');
            const passwordInput = form.querySelector('input[name="password"]');

            form.classList.toggle('show');
            form.classList.toggle('d-none');

            if (form.classList.contains('show')) {
                this.textContent = 'Sembunyikan Password';
            } else {
                this.textContent = 'Ganti Password?';
                // Hapus isi input password
                if (passwordInput) {
                    passwordInput.value = '';
                }
            }
        });
    </script>
@endpush
