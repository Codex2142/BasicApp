@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="row g-3">
            {{-- TOTAL PRODUK --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold">TOTAL PRODUK</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around bg-amber-200">
                        <div class="icon-dashboard">
                            <i class="bi bi-cup-straw"></i>
                        </div>
                        <h1 class="text-dashboard">{{ count($product) }}</h1>
                    </div>
                </div>
            </div>

            {{-- BELUM DIISI --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold">BELUM DIISI</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around bg-red-200">
                        <div class="icon-dashboard">
                            <i class="bi bi-cup-straw"></i>
                        </div>
                        <h1 class="text-dashboard">10</h1>
                    </div>
                </div>
            </div>

            {{-- TRANSAKSI --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold">TRANSAKSI {{ strtoupper($month) }}</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around bg-blue-200">
                        <div class="icon-dashboard">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <h1 class="text-dashboard">{{ count($transaction) }}</h1>
                    </div>
                </div>
            </div>

            {{-- PENDAPATAN --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold">PENDAPATAN {{ strtoupper($month) }}</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around bg-green-200">
                        <h1 class="text-dashboard">{{ 'Rp '. number_format($total, 0, ',', '.') }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
