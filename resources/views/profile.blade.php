@extends('layouts.app')

@section('header')
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Profile
    </h2>
@endsection

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold">Halaman Profile</h1>

    <p class="mt-2">Nama: {{ auth()->user()->name }}</p>
    <p>Email: {{ auth()->user()->email }}</p>
</div>
@endsection
