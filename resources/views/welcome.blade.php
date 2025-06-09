<div class="mb-3">
    @include('components.form', [
        'type' => 'text',
        'label' => 'Nama',
        'name' => 'name',
        'place' => 'Masukkan Nama',
        'value' => 'John Doe',
        {{-- 'value' => old('name', $product->name ?? ''), CONTOH MODEL DATABASE --}}

    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'password',
        'label' => 'Password',
        'name' => 'password',
        'place' => 'Masukkan Password',
        'value' => '12345678',  // Note: biasanya password tidak diisi default.
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'email',
        'label' => 'Email',
        'name' => 'email',
        'place' => 'Masukkan Email',
        'value' => 'johndoe@example.com',
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'number',
        'label' => 'Angka',
        'name' => 'number',
        'place' => '',
        'value' => '100',
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'date',
        'label' => 'Tanggal',
        'name' => 'date',
        'place' => '',
        'value' => '2025-05-26',
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'time',
        'label' => 'Jam',
        'name' => 'start_time',
        'place' => '',
        'value' => '14:30',
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'month',
        'label' => 'Bulan',
        'name' => 'month',
        'place' => '',
        'value' => '2025-05',
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'week',
        'label' => 'Minggu',
        'name' => 'week',
        'place' => '',
        'value' => '2025-W22',
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'radio',
        'label' => 'Jenis Kelamin',
        'name' => 'gender',
        'place' => '',
        'data' => [
            'male' => 'Laki',
            'female' => 'Perempuan',
            'mix' => 'Campuran',
        ],
        'value' => 'female',  // default terpilih
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'checkbox',
        'label' => 'Makan Atau Minum',
        'name' => 'activity',
        'place' => '',
        'data' => [
            'eating' => 'Makan',
            'drinking' => 'Minum',
            'playing' => 'Main'
        ],
        'value' => ['eating', 'playing'], // default yang diceklis
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'file',
        'label' => 'Masukkan Gambar',
        'name' => 'file',
        'place' => '',
        'value' => '', // untuk file input, biasanya tidak bisa diset value karena alasan keamanan
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'textarea',
        'label' => 'Deskripsi',
        'name' => 'description',
        'place' => 'Masukkan Deskripsi',
        'value' => 'Ini adalah deskripsi produk contoh.',
    ])
</div>

<div class="mb-3">
    @include('components.form', [
        'type' => 'select',
        'label' => 'Jenis Kelamin',
        'name' => 'gender',
        'place' => '',
        'data' => [
            'male' => 'Laki',
            'female' => 'Perempuan',
            'mix' => 'Campuran',
        ],
        'value' => 'male',
    ])
</div>


@auth
    @if (Auth::user()->role === 'admin')
        <p>Selamat datang, Admin!</p>
        <a href="{{ route('user.index') }}">Kelola Pengguna</a>
    @elseif (Auth::user()->role === 'user')
        <p>Selamat datang, Pengguna!</p>
        <a href="{{ route('product.index') }}">Lihat Produk</a>
    @else
        <p>Role tidak dikenali.</p>
    @endif
@endauth
