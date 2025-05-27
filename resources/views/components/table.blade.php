@php
    $Model = 'App\\Models\\' . Str::studly(Str::singular($table));
    $label = $Model::Labelling();

    $data = $Model::orderBy($sortBy)->paginate(10);
    $field  = (new $Model)->getFillable();
@endphp
<div class="container mx-auto mb-40">
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <h1 class="text-2xl font-semibold mb-4 my-3 mx-3">{{ $head }}</h1>
      <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    {{-- NAMA NAMA KOLOM DINAMIS --}}
                    @foreach ($label as $l)
                        <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ $l }}</th>
                    @endforeach
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{-- LOOPING BARIS --}}
                @if ($data)
                    @foreach ($data as $d )
                        <tr>
                            <td class="px-4 py-3 fw-bold">{{ $loop->iteration }}</td>

                            {{-- LOOPING KOLOM --}}
                            @foreach ($field as $f)
                                @if ($f == 'photo')
                                    <td class="px-4 py-3">
                                        <span class="inline-block rounded-full overflow-hidden w-16 h-16">
                                            <img
                                                src="{{ $d->$f ? asset('storage/' . $d->$f) : 'https://placehold.co/64x64/png' }}"
                                                class="w-full h-full object-cover"
                                            />
                                        </span>
                                    </td>
                                @else
                                    <td class="px-4 py-3">
                                        {{ $d->$f }}
                                    </td>
                                @endif
                            @endforeach
                            <td class="px-4 py-3">
                                <div class="flex justify-center space-x-2">
                                    @php
                                        $routes = \App\Providers\WebHelper::removeWord($table);
                                    @endphp
                                    <a href="{{ route($routes . '.edit', ($d['id'])) }}">
                                        <button class="text-blue-600 hover:underline">Edit</button>
                                    </a>
                                    <a href="{{ route($routes . '.destroy', ($d['id'])) }}">
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ count($label) + 2}}">
                            <p class="text-center py-2 text-gray-600">Tidak ada Data</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="px-4 py-3 bg-white border-t text-center">
            {{ $data->links() }}
        </div>
    </div>
</div>
