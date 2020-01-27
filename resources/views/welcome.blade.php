@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h3 class="display-3">Products</h3>
        </div>
    </div>
    <div class="row">
        @foreach ($products as $product)
        <div class="col-4">
            <div class="card">
                <img src="{{ $product->picture }}" alt="product-image" class="card-img-top">
                <div class="card-body">
                    <div class="card-title">{{ $product->title }}</div>
                    <div class="card-text">{{ $product->details }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
