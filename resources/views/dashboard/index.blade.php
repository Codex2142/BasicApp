@php
    use Carbon\Carbon;
    $prev = Carbon::create((int) $year, (int) $month)->subMonth();
    $next = Carbon::create((int) $year, (int) $month)->addMonth();
@endphp


@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="container mt-4">
        <div class="row g-3">
            {{-- TOTAL PRODUK --}}
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold bg-amber-300">TOTAL PRODUK</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around">
                        <div class="icon-dashboard">
                            <span class="p-3 bg-gray-300 rounded-full shadow">
                                <i class="bi bi-cup-straw"></i>
                            </span>
                        </div>
                        <h1 class="text-dashboard">{{ count($product) }}</h1>
                    </div>
                </div>
            </div>

            {{-- BELUM DIISI --}}
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold bg-red-200">MASIH KOSONGAN</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around">
                        <div class="icon-dashboard">
                            <span class="p-3 bg-gray-300 rounded-full shadow">
                                <i class="bi bi-cup-straw"></i>
                            </span>
                        </div>
                        <h1 class="text-dashboard">10</h1>
                    </div>
                </div>
            </div>

            {{-- TRANSAKSI --}}
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold  bg-blue-200">TRANSAKSI
                        {{ strtoupper(Carbon::create()->month($month)->locale('id')->translatedFormat('F')) }}</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around">
                        <div class="icon-dashboard">
                            <span class="p-3 bg-gray-300 rounded-full shadow">
                                <i class="bi bi-newspaper"></i>
                            </span>
                        </div>
                        <h1 class="text-dashboard">{{ count($transaction) }}</h1>
                    </div>
                </div>
            </div>

            {{-- PENDAPATAN --}}
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold bg-green-200">PENDAPATAN
                        {{ strtoupper(Carbon::create()->month($month)->locale('id')->translatedFormat('F')) }}</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around ">
                        <h1 class="text-dashboard">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</h1>
                    </div>
                </div>
            </div>

            {{-- CHART BESAR --}}
            <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                <div class="card text-center shadow">
                    <div class="d-flex justify-between mx-4 align-items-center mt-2">
                        <h1 class="display-6 fw-bold">
                            {{ strtoupper(Carbon::create()->month($month)->locale('id')->translatedFormat('F')) }}
                            {{ $year }}</h1>
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('dashboard.index', ['month' => $prev->month, 'year' => $prev->year]) }}"
                                class="btn bg-gray-500 border border-gray-700 rounded-md p-2 px-3 text-white hover:bg-gray-700 hover:border-gray-800">
                                <span><i class="bi bi-arrow-left"></i></span>
                            </a>
                            <a href="{{ route('dashboard.index', ['month' => $next->month, 'year' => $next->year]) }}"
                                class="btn bg-gray-500 border border-gray-700 rounded-md p-2 px-3 text-white hover:bg-gray-700 hover:border-gray-800">
                                <span><i class="bi bi-arrow-right"></i></span>
                            </a>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        {!! $chart->container() !!}
                    </div>
                </div>
            </div>

            {{-- CHART KECIL --}}
            <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold bg-green-200">SEDANG BERLANGSUNG</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around ">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {!! $chart->script() !!}
@endpush
