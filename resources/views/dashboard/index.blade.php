@php
    use Carbon\Carbon;
    $prev = Carbon::create((int) $year, (int) $month)->subMonth();
    $next = Carbon::create((int) $year, (int) $month)->addMonth();
@endphp


@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="container mt-3">
        <div class="breadcrumbs-container text-white">
            {!! Breadcrumbs::render('Beranda') !!}
        </div>
    </div>

    <div class="container mt-4">
        <div class="row g-3">
            {{-- TOTAL PRODUK --}}
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card border-0 text-center shadow">
                    <div class="card-header border-0 fw-bold bg-slate-800 text-white">TOTAL PRODUK</div>
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
                <div class="card border-0 text-center shadow">
                    <div class="card-header border-0 fw-bold bg-slate-700 text-white">TOTAL PENGGUNA</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around">
                        <div class="icon-dashboard">
                            <span class="p-3 bg-gray-300 rounded-full shadow">
                                <i class="bi bi-people"></i>
                            </span>
                        </div>
                        <h1 class="text-dashboard">{{ $users }}</h1>
                    </div>
                </div>
            </div>

            {{-- Kiriman --}}
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card border-0 text-center shadow">
                    <div class="card-header border-0 fw-bold  bg-slate-500 text-white">KIRIMAN
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
                <div class="card border-0 text-center shadow">
                    <div class="card-header border-0 fw-bold bg-slate-200">PENDAPATAN
                        {{ strtoupper(Carbon::create()->month($month)->locale('id')->translatedFormat('F')) }}</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around overflow-x-auto">
                        <h1 class="text-dashboard">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</h1>
                    </div>
                </div>
            </div>

            {{-- CHART BESAR --}}
            <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                <div class="card border-0 text-center shadow">
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
                <div class="card border-0 text-center shadow h-100"> <!-- Tambah h-100 -->
                    <div class="card-header border-0 fw-bold bg-slate-100">
                        10 PRODUK TERLARIS
                        {{ strtoupper(Carbon::create()->month($month)->locale('id')->translatedFormat('F')) }}
                    </div>
                    <div class="card-body p-0 d-flex flex-column"> <!-- Modifikasi disini -->
                        <div class="flex-grow-1" style="min-height: 200px;">
                            {!! $chart2->container() !!}
                        </div>
                        <div class="chart-legend mt-auto px-3 pb-3">
                            <!-- Legend akan otomatis muncul di sini -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {!! $chart->script() !!}
    {!! $chart2->script() !!}

@endpush
