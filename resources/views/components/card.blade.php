@php
    $Model = 'App\\Models\\' . Str::studly(Str::singular($table));
    $label = $Model::Labelling();
    $field = (new $Model())->getFillable();
    $routes = \App\Providers\WebHelper::removeWord($table);
    $data = $Model::orderByDesc($sortBy)->get()->toArray();
    foreach ($data as &$item) {
        $item['tanggal'] = \App\Providers\WebHelper::dateIndonesia($item['tanggal']);
        // decode JSON product jadi array supaya bisa diakses di view
        $item['product_json'] = json_decode($item['product'], true);
    }
    unset($item);
@endphp
<div class="container mx-auto mb-40">
    <div class="overflow-x-auto bg-white rounded-lg shadow p-4">
        <div class="row row-cols-2 row-cols-lg-4 g-3">

            @foreach ($data as $d)
                <div class="col" data-tanggal="{{ $d['tanggal'] }}">
                    <div class="card border-0 mb-3 h-100 shadow">
                        <div class="row g-0 h-100 bg-slate-100">
                            <div
                                class="col-md-4 {{ $d['status'] == 'pending' ? 'bg-amber-500' : 'bg-green-500' }} d-flex align-items-center justify-center">
                                <h1 class="px-3 fw-bold text-gray-950">{{ $d['tanggal'] }}</h1>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body border-0">
                                    <span
                                        class="badge badge-{{ $d['status'] == 'pending' ? 'warning' : 'success' }} tracking-wider p-2 mb-1">{{ $d['status'] == 'pending' ? 'Menunggu' : 'Selesai' }}</span>
                                    <p class="card-text">{{ $d['description'] }}</p>
                                    <span class="badge badge-primary tracking-wider p-2 mt-2">{{ 'Rp '. number_format($d['total'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            @if ($d['status'] == 'pending')
                                <a class="btn bg-blue-900 text-white hover:bg-blue-400 hover:text-black w-50 card-button-edit"
                                    href="{{ route('transaction.edit', $d['id']) }}">
                                    <button type="button"><i class="bi bi-eye"></i></button>
                                </a>
                                <button class="btn bg-red-800 text-white hover:bg-red-400 hover:text-black w-50 card-button-delete" type="button" id="btnDelete"><i
                                        class="bi bi-trash"></i></button>
                            @else
                                <a class="btn bg-blue-900 text-white hover:bg-blue-400 hover:text-black w-100 card-button-edit-single"
                                    href="{{ route('transaction.edit', $d['id']) }}">
                                    <button type="button"><i class="bi bi-eye"></i></button>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
