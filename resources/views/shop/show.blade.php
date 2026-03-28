@extends('layouts.app')

@section('title', $product->title)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->title }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-lg-7">
            <div class="rounded-4 overflow-hidden shadow-sm mb-4">
                <img src="{{ $product->image_url ?? 'https://via.placeholder.com/800x500?text=Product+Cover' }}" 
                     class="w-100 object-fit-cover" style="max-height: 500px;" alt="{{ $product->title }}">
            </div>
            <div class="product-description">
                <h3 class="fw-bold mb-4">Description</h3>
                <div class="text-muted lh-lg">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 20px;">
                <span class="badge rounded-pill {{ $product->type === 'course' ? 'bg-primary' : 'bg-success' }} d-inline-block w-auto px-3 py-2 mb-3 align-self-start">
                    {{ ucfirst($product->type) }}
                </span>
                <h1 class="fw-bold mb-3">{{ $product->title }}</h1>
                <div class="mb-4">
                    @if($product->discount_price)
                        <span class="text-primary fw-bold fs-2">৳{{ number_format($product->discount_price, 2) }}</span>
                        <span class="text-muted text-decoration-line-through fs-5 ms-3">৳{{ number_format($product->price, 2) }}</span>
                        <span class="badge bg-danger ms-2">Save {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%</span>
                    @else
                        <span class="text-primary fw-bold fs-2">৳{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
                
                <hr class="my-4 opacity-10">
                
                <div class="mb-4">
                    <h5 class="fw-bold"><i class="bi bi-shield-check text-primary me-2"></i>Secure Access</h5>
                    <p class="text-muted small">Immediately accessible after payment verification.</p>
                </div>

                <a href="{{ route('shop.checkout', $product) }}" class="btn btn-primary btn-lg w-100 py-3 fw-bold">Buy Now</a>
                
                <div class="mt-4 text-center">
                    <p class="text-muted small">Need help? <a href="#">Contact Support</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
