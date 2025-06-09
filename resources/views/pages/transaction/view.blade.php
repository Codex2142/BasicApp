@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')

    <div class="container mt-4">

        <div class="container mt-3">
            <div class="breadcrumbs-container text-white">
                {!! Breadcrumbs::render('Transaction') !!}
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
                @include('components.feedback',[
                    'type' =>'error',
                    'message' => $error,
                ])
            @endforeach
        @endif
    </div>

    <div class="bg-white rounded-lg shadow my-4 mx-2 p-4">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">

            <!-- Kiri: Tombol Tambah + Pencarian -->
            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">

                <!-- Tombol Tambah -->
                <a href="/transaksi/tambah" class="btn btn-success rounded-lg">
                    Tambah
                </a>

                <!-- Input Cari -->
                <div class="input-group">
                    <input id="searchInput" type="text" class="form-control" placeholder="Cari" aria-label="Pencarian">
                    <div class="input-group-append">
                        <span id="searchDelete" class="btn btn-danger from-group-view">X</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                <span><h1 class="fw-bold">Tampilan diurutkan berdasarkan <span class="text-stone-700">Tanggal</span></h1></span>
            </div>
        </div>
    </div>

    @include('components.card',[
        'head' => 'Semua Transaksi',   {{-- JUDUL TABEL --}}
        'table' => 'Transactions',      {{-- TABEL DATABASE --}}
        'sortBy' => 'tanggal',         {{-- PENGURUTAN --}}
    ])
    <div class="btn btn-success button-fixed-corner">
        <a href="/transaksi/tambah"><i class="bi bi-plus"></i></a>
    </div>
    @include('components.modal-delete')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const searchDelete = document.getElementById('searchDelete');

        searchInput.addEventListener('input', function () {
            const filter = searchInput.value.toLowerCase();
            const cards = document.querySelectorAll('.col[data-tanggal]');

            cards.forEach(card => {
                const tanggal = card.getAttribute('data-tanggal').toLowerCase();
                if (tanggal.includes(filter)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        searchDelete.addEventListener('click', function () {
            searchInput.value = '';
            const cards = document.querySelectorAll('.col[data-tanggal]');
            cards.forEach(card => {
                card.style.display = 'block';
            });
        });
    });

    let selectedDeleteUrl = '';

    document.querySelectorAll('.card-button-delete').forEach(button => {
        button.addEventListener('click', function () {
            const card = button.closest('.col');
            const id = card.querySelector('.card-button-edit')?.href.split('/').pop();
            selectedDeleteUrl = `/transaksi/${id}`;
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        });
    });

    document.getElementById('confirmSaveBtn').addEventListener('click', function () {
        if (selectedDeleteUrl) {
            window.location.href = selectedDeleteUrl;
        }
    });

</script>
@endpush

