@extends ('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col">
                    <h4 class="display-3">{{ $product->title }} ({{ $product->stock }})</h4>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <a href="#" class="btn btn-success btn-lh">Purchase</a>
                </div>
            </div>

            <div class="row">
                <div class="col-10">
                    <div class="card">
                        <img src="{{ $product->picture }}" alt="product-image" class="card-img-top">
                        <div class="card-body">
                            <div class="card-title">{{ $product->title }}</div>
                            <div class="card-text">{{ $product->details }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
