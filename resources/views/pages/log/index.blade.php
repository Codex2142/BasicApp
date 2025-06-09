@extends('layouts.app')

@section('title', 'Riwayat')

@section('content')

    <div class="container mt-4 p-4">
        <div class="container mt-3">
            <div class="breadcrumbs-container text-white">
                {!! Breadcrumbs::render('Log') !!}
            </div>
        </div>

        <div x-data="{ tab: 'login' }" class="w-full">
            <div class="flex flex-wrap justify-start gap-2 border-b border-gray-200 mb-6">
                <button @click="tab = 'login'"
                    :class="tab === 'login' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                    class="px-4 py-2 rounded-t-md font-semibold transition">
                    Login/Logout
                </button>
                <button @click="tab = 'insert'"
                    :class="tab === 'insert' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                    class="px-4 py-2 rounded-t-md font-semibold transition">
                    Insert
                </button>
                <button @click="tab = 'update'"
                    :class="tab === 'update' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                    class="px-4 py-2 rounded-t-md font-semibold transition">
                    Update
                </button>
                <button @click="tab = 'delete'"
                    :class="tab === 'delete' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                    class="px-4 py-2 rounded-t-md font-semibold transition">
                    Delete
                </button>
            </div>
            {{-- Content Tabs --}}
            <div>
                <div x-show="tab === 'delete'" class="space-y-4">
                    @php
                        $deleteLogs = $data['delete'] ?? collect();
                    @endphp

                    @if ($deleteLogs->isEmpty())
                        <p>Tidak ada log delete.</p>
                    @else
                        @foreach ($deleteLogs as $log)
                            <div class="card mb-3 p-3">
                                <strong>{{ strtoupper($log['deskripsi']) }}</strong>
                                <div class="mt-2 mb-3">
                                    <div><strong>ID Pengguna:</strong> {{ $log['user_id'] }}</div>
                                    <div><strong>Username:</strong> {{ $log['username'] ?? 'Unknown' }}</div>
                                    <div><strong>Waktu:</strong> {{ $log['waktu'] }}</div>
                                </div>

                                @php
                                    $detail = $log['detail'] ?? [];
                                @endphp

                                @if (is_array($detail) && count($detail) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Kolom</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($detail as $key => $value)
                                                    <tr>
                                                        <td>{{ ucfirst($key) }}</td>
                                                        <td>
                                                            @if (is_array($value))
                                                                <ul class="list-unstyled mb-0">
                                                                    @foreach ($value as $k => $v)
                                                                        <li><strong>{{ ucfirst($k) }}:</strong>
                                                                            {{ $v ?? '-' }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                {{ $value === null ? '-' : $value }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                <div x-show="tab === 'login'" class="space-y-4">

                    @php
                        $loginLogs = $data->get('login', collect())->merge($data->get('logout', collect()));
                    @endphp

                    @forelse ($loginLogs as $log)
                        <div class="card mb-4 shadow p-4 rounded-lg bg-white">
                            <h1 class="text-lg font-bold text-stone-700 display-6">{{ strtoupper($log['deskripsi']) }}</h1>
                            <div class="mt-2 grid md:grid-cols-3 gap-4 text-sm text-gray-700">
                                <div>
                                    <span class="font-semibold">ID Pengguna:</span><br>
                                    {{ $log['user_id'] }}
                                </div>
                                <div>
                                    <span class="font-semibold">Username:</span><br>
                                    {{ $log['username'] ?? 'Unknown' }}
                                </div>
                                <div>
                                    <span class="font-semibold">Waktu:</span><br>
                                    {{ $log['waktu'] }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Tidak ada log login/logout.</p>
                    @endforelse
                </div>

                <div x-show="tab === 'insert'" class="space-y-4">
                    @forelse ($data->get('insert', collect()) as $log)
                        <div class="card mb-4 shadow p-4 rounded-lg bg-white">
                            <h5 class="text-lg font-bold text-green-700">{{ strtoupper($log['deskripsi']) }}</h5>

                            <div class="mt-2 mb-3 text-sm text-gray-700">
                                <div><strong>ID Pengguna:</strong> {{ $log['user_id'] }}</div>
                                <div><strong>Username:</strong> {{ $log['username'] ?? 'Unknown' }}</div>
                                <div><strong>Waktu:</strong> {{ $log['waktu'] }}</div>
                            </div>

                            @php
                                $dataDetail = $log['detail']['data'] ?? [];
                            @endphp

                            @if (!empty($dataDetail))
                                <div class="table-responsive overflow-x-auto">
                                    <table class="table table-sm table-bordered table-striped">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-4 py-2 text-left">Kolom</th>
                                                <th class="border px-4 py-2 text-left">Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataDetail as $key => $value)
                                                <tr>
                                                    <td class="border px-4 py-2 align-top font-semibold">
                                                        {{ ucfirst($key) }}</td>
                                                    <td class="border px-4 py-2 align-top whitespace-pre-wrap break-words">
                                                        @if ($key === 'product' && is_string($value))
                                                            @php
                                                                $productData = json_decode($value, true);
                                                            @endphp
                                                            @if ($productData && isset($productData['items']) && is_array($productData['items']))
                                                                <table
                                                                    class="table table-bordered table-sm mb-2 w-full text-xs">
                                                                    <thead class="bg-gray-50">
                                                                        <tr>
                                                                            <th class="border px-2 py-1">Nama Produk</th>
                                                                            <th class="border px-2 py-1">Jumlah</th>
                                                                            <th class="border px-2 py-1">Harga</th>
                                                                            <th class="border px-2 py-1">Subtotal</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($productData['items'] as $item)
                                                                            <tr>
                                                                                <td class="border px-2 py-1">
                                                                                    {{ $item['name'] ?? '-' }}</td>
                                                                                <td class="border px-2 py-1">
                                                                                    {{ $item['qty'] ?? '-' }}</td>
                                                                                <td class="border px-2 py-1">
                                                                                    {{ number_format($item['price'] ?? 0, 0, ',', '.') }}
                                                                                </td>
                                                                                <td class="border px-2 py-1">
                                                                                    {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                <div><strong>Total:</strong>
                                                                    {{ number_format($productData['total'] ?? 0, 0, ',', '.') }}
                                                                </div>
                                                            @else
                                                                {{ $value }}
                                                            @endif
                                                        @else
                                                            {{ $value === null ? '-' : $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Tidak ada log insert.</p>
                    @endforelse
                </div>


                <div x-show="tab === 'update'" class="space-y-4">
                    @php
                        $updateLogs = $data['update'] ?? [];
                    @endphp

                    @if (empty($updateLogs))
                        <p>Tidak ada log update.</p>
                    @else
                        @foreach ($updateLogs as $log)
                            <div class="card mb-3 p-3">
                                <strong>{{ strtoupper($log['deskripsi']) }}</strong>
                                <div class="mt-2 mb-3">
                                    <div><strong>ID Pengguna:</strong> {{ $log['user_id'] }}</div>
                                    <div><strong>Username:</strong> {{ $log['username'] ?? 'Unknown' }}</div>
                                    <div><strong>Waktu:</strong> {{ $log['waktu'] }}</div>
                                </div>

                                @php
                                    $data = $log['detail']['data'] ?? [];
                                @endphp

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kolom</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $key => $value)
                                                <tr>
                                                    <td>{{ ucfirst($key) }}</td>
                                                    <td>
                                                        @if ($key === 'product' && is_string($value))
                                                            @php
                                                                $productData = json_decode($value, true);
                                                            @endphp
                                                            @if ($productData && isset($productData['items']) && is_array($productData['items']))
                                                                <table class="table table-bordered table-sm mb-2">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Nama Produk</th>
                                                                            <th>Jumlah</th>
                                                                            <th>Harga</th>
                                                                            <th>Subtotal</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($productData['items'] as $item)
                                                                            <tr>
                                                                                <td>{{ $item['name'] ?? '-' }}</td>
                                                                                <td>{{ $item['qty'] ?? '-' }}</td>
                                                                                <td>{{ number_format($item['price'] ?? 0, 0, ',', '.') }}
                                                                                </td>
                                                                                <td>{{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                <div><strong>Total:</strong>
                                                                    {{ number_format($productData['total'] ?? 0, 0, ',', '.') }}
                                                                </div>
                                                            @else
                                                                {{ $value }}
                                                            @endif
                                                        @else
                                                            {{ $value === null ? '-' : $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
