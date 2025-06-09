@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow-md">
            <div class="text-center">
                <span class="inline-block rounded-full overflow-hidden w-64 h-64 shadow-lg ring-2 ring-gray-300">
                    <img src="{{ asset('images/Skirknamecard.jpg') }}" class="w-full h-full object-cover" />
                </span>
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Toko Zakiah</h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    @foreach ($errors->all() as $error)
                        <span class="block">{{ $error }}</span>
                    @endforeach
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('auth.loginProcess') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        @include('components.form', [
                            'type' => 'text',
                            'label' => 'Username',
                            'name' => 'username',
                            'place' => 'Masukkan Username',
                            'addon' => 'autocomplete="off"',
                        ])
                    </div>
                    <div>
                        @include('components.form', [
                            'type' => 'password',
                            'label' => 'Password',
                            'name' => 'password',
                            'place' => 'Masukkan Password',
                        ])
                    </div>
                </div>
                <div class="flex justify-center items-center">
                    <button class="bg-blue-500 py-2 px-4 rounded-lg hover:bg-blue-900 hover:text-white">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
