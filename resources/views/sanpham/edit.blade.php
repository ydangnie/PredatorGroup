@extends('layouts.admin')
@section('content')
    <div class="bg-gray-900 min-h-screen text-white p-6">
        @livewire('admin.product-form', ['product' => $product])
    </div>
@endsection
