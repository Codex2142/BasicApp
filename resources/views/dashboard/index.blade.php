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
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold">MASIH KOSONGAN</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around bg-red-200">
                        <div class="icon-dashboard">
                            <i class="bi bi-cup-straw"></i>
                        </div>
                        <h1 class="text-dashboard">10</h1>
                    </div>
                </div>
            </div>

            {{-- TRANSAKSI --}}
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold">TRANSAKSI {{ strtoupper(Carbon::create()->month($month)->locale('id')->translatedFormat('F')) }}</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around bg-blue-200">
                        <div class="icon-dashboard">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <h1 class="text-dashboard">{{ count($transaction) }}</h1>
                    </div>
                </div>
            </div>

            {{-- PENDAPATAN --}}
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold">PENDAPATAN {{ strtoupper(Carbon::create()->month($month)->locale('id')->translatedFormat('F')) }}</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around bg-green-200">
                        <h1 class="text-dashboard">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</h1>
                    </div>
                </div>
            </div>

            {{-- CHART BESAR --}}
            <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                <div class="card text-center shadow">
                    <div class="d-flex justify-around align-items-center mt-2">
                        <h1 class="display-6">{{ strtoupper(Carbon::create()->month($month)->locale('id')->translatedFormat('F')) }} {{ $year }}</h1>
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
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card text-center shadow">
                    <div class="card-header fw-bold">MASIH KOSONGAN</div>
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-around bg-green-200">
                        <h1 class="text-dashboard">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</h1>
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
