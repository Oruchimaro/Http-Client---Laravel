@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-2">
            <div class="row">
                <div class="col">
                    <h2>Categories</h2>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <ul class="list-group">
                        @foreach ($categories as $category)

                        <a class="list-group-item" href="{{ route('categories.products.show', ['title' =>$category->title, 'id' => $category->identifier]) }}">
                            {{ $category->title }} </a>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <div class="col">
                    <h3 class="display-3">Products</h3>
                </div>
            </div>
            <div class="row">
                @foreach ($products as $product)
                <div class="col-4">
                    <a href="{{ route('products.show', ['title' =>$product->title, 'id' => $product->identifier]) }}">
                        <div class="card">
                            <img src="{{ $product->picture }}" alt="product-image" class="card-img-top">
                            <div class="card-body">
                                <div class="card-title">{{ $product->title }}({{ $product->stock }})
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
