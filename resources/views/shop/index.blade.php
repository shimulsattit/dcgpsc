@extends('layouts.app')

@section('title', 'Online Shop - Courses & Books')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold position-relative d-inline-block pb-2">
            Online Shop
            <div class="position-absolute start-0 bottom-0 w-100" style="height: 3px; background-color: #27ae60;"></div>
        </h2>
        <p class="text-muted mt-3">Enhance your knowledge with our premium courses and books.</p>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-5">
            <h4 class="text-muted">No products available at the moment.</h4>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm transition-hover overflow-hidden">
                        <div class="position-relative" style="height: 220px;">
                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/400x220?text=Product+Cover' }}" 
                                 class="w-100 h-100 object-fit-cover" alt="{{ $product->title }}">
                            <span class="position-absolute top-0 start-0 m-3 badge rounded-pill {{ $product->type === 'course' ? 'bg-primary' : 'bg-success' }}">
                                {{ ucfirst($product->type) }}
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold mb-2">{{ $product->title }}</h5>
                            <div class="mb-3">
                                @if($product->discount_price)
                                    <span class="text-primary fw-bold fs-5">৳{{ number_format($product->discount_price, 2) }}</span>
                                    <span class="text-muted text-decoration-line-through small ms-2">৳{{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="text-primary fw-bold fs-5">৳{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit(strip_tags($product->description), 120) }}
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('shop.show', $product) }}" class="btn btn-outline-primary w-100 mb-2">View Details</a>
                                <a href="{{ route('shop.checkout', $product) }}" class="btn btn-primary w-100">Buy Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endsection
