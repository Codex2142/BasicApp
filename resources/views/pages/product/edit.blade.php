@extends('layouts.app')

@section('title', 'Produk - Edit')

@section('content')
    <div class="container mt-4 ">
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
            <span class="md:mx-40 mr-10 fw-bold"> Edit Produk</span>
        </div>

        <form action="{{ route('product.update', $data[0]->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-col md:flex-row gap-6 items-center justify-center">
                <div class="flex flex-col items-center justify-center">
                    <!-- Old Photo Preview -->
                    @if (!empty($data[0]->photo))
                        <div id="oldPhoto" class="transition-all duration-300 ease-in-out">
                            <span
                                class="inline-block rounded-full overflow-hidden w-64 h-64 shadow-lg ring-2 ring-gray-300">
                                <img src="{{ asset('storage/' . $data[0]->photo) }}" class="w-full h-full object-cover" />
                            </span>
                        </div>
                    @endif

                    <!-- Input New -->
                    <div id="newPhoto" class="transition-all duration-300 ease-in-out mt-4 w-full hidden">
                        @include('components.form', [
                            'type' => 'file',
                            'label' => 'Masukkan Gambar Baru',
                            'name' => 'photo',
                        ])
                    </div>
                </div>

                <!-- Button Toggle -->
                <div class="flex flex-col items-center justify-center mt-4">
                    <button type="button" id="toggleBtn"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-300">
                        <h3 id="toggleBtnText" class="text-lg font-semibold">Ganti Foto?</h3>
                    </button>
                </div>
            </div>

            <!-- Nama Produk -->
            <div class="mb-3">
                <div class="mb-3">
                    @include('components.form', [
                        'type' => 'text',
                        'label' => 'Nama',
                        'name' => 'name',
                        'place' => 'Masukkan Nama',
                        'value' => old('name', $data[0]->name ?? ''),
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
                        'value' => old('price1', $data[0]->price1 ?? ''),
                        'addon' => 'autocomplete="off"',
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
                        'value' => old('price2', $data[0]->price2 ?? ''),
                        'addon' => 'autocomplete="off"',
                    ])
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-3 mt-4">
                <div class="mb-3">
                    @include('components.form', [
                        'type' => 'textarea',
                        'label' => 'Deskripsi',
                        'name' => 'description',
                        'place' => '',
                        'value' => old('description', $data[0]->description ?? ''),
                        'addon' => 'autocomplete="off"',
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

{{-- SCRIPT TO TOGGLE --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleBtn');
        const toggleBtnText = document.getElementById('toggleBtnText');
        const oldPhoto = document.getElementById('oldPhoto');
        const newPhoto = document.getElementById('newPhoto');
        const fileInput = newPhoto.querySelector('input[type="file"]');

        let isEditing = false;

        toggleBtn.addEventListener('click', function() {
            isEditing = !isEditing;

            if (isEditing) {
                oldPhoto?.classList.add('hidden');
                newPhoto.classList.remove('hidden');
                toggleBtnText.textContent = 'Batalkan';
            } else {
                oldPhoto?.classList.remove('hidden');
                newPhoto.classList.add('hidden');
                toggleBtnText.textContent = 'Ganti Foto?';

                if (fileInput) fileInput.value = '';
            }
        });
    });
</script>
