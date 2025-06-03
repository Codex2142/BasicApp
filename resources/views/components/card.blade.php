@php
    $Model = 'App\\Models\\' . Str::studly(Str::singular($table));
    $label = $Model::Labelling();
    $field = (new $Model())->getFillable();
    $routes = \App\Providers\WebHelper::removeWord($table);
    $data = $Model::orderByDesc($sortBy)->get()->toArray();
    foreach ($data as &$item) {
        $item['tanggal'] = \App\Providers\WebHelper::dateIndonesia($item['tanggal']);
    }
    unset($item);
@endphp
<div class="container mx-auto mb-40">
    <div class="overflow-x-auto bg-white rounded-lg shadow p-4">
        <div class="row row-cols-2 row-cols-lg-4 g-3">

            @foreach ($data as $d)
                {{-- FOREACH --}}
                <div class="col" data-tanggal="{{ $d['tanggal'] }}">
                    <div class="card mb-3 h-100 shadow">
                        <div class="row g-0 h-100 bg-gray-50">
                            <div
                                class="col-md-4 {{ $d['status'] == 'pending' ? 'bg-amber-200' : 'bg-green-200' }} d-flex align-items-center justify-center">
                                <h1 class="px-3 fw-bold text-gray-950">{{ $d['tanggal'] }}</h1>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <span
                                        class="badge badge-{{ $d['status'] == 'pending' ? 'warning' : 'success' }} warning px-2">{{ $d['status'] == 'pending' ? 'Menunggu' : 'Selesai' }}</span>
                                    <p class="card-text">{{ $d['description'] }}</p>
                                    <span class="badge badge-primary">{{ 'Rp '. number_format($d['total'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            @if ($d['status'] == 'pending')
                                <a class="btn btn-primary w-50 card-button-edit"
                                    href="{{ route('transaction.edit', $d['id']) }}">
                                    <button type="button"><i class="bi bi-eye"></i></button>
                                </a>
                                <button class="btn btn-danger w-50 card-button-delete" type="button" id="btnDelete"><i
                                        class="bi bi-trash"></i></button>
                            @else
                                <a class="btn btn-primary w-100 card-button-edit-single"
                                    href="{{ route('transaction.edit', $d['id']) }}">
                                    <button type="button"><i class="bi bi-eye"></i></button>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- END FOREACH --}}
            @endforeach

        </div>
    </div>
</div>
