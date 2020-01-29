@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col">
                    <h3 class="display-3">Purchases</h3>
                </div>
            </div>
            <div class="row">
                @foreach ($purchases as $product)
                <div class="col-4">
                    <a href="{{ route('products.show', ['title' =>$product->title, 'id' => $product->identifier]) }}">
                        <div class="card">
                            <img src="{{ $product->picture }}" alt="product-image" class="card-img-top">
                            <div class="card-body">
                                <div class="card-title">{{ $product->title }}
                                </div>
                                <div class="card-text">{{ $product->details }}</div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection
