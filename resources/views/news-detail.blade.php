@extends('layouts.app')

@section('title', $newsEvent->title)

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <article class="card shadow-sm border-0">
                    @if($newsEvent->image)
                        <img src="{{ $newsEvent->image }}" class="card-img-top" alt="{{ $newsEvent->title }}"
                            style="width: 100%; max-height: 600px; object-fit: contain; background: #f8f9fa;" referrerpolicy="no-referrer">
                    @endif

                    <div class="card-body p-4">
                        <h1 class="card-title mb-3">{{ $newsEvent->title }}</h1>

                        <div class="text-muted mb-4 d-flex align-items-center flex-wrap">
                            <span class="me-3">
                                <i class="far fa-user me-1"></i> {{ \App\Models\Setting::get('default_post_author', 'Admin') }}
                            </span>
                            <span>
                                <i class="far fa-calendar-alt me-1"></i> {{ $newsEvent->published_at->format('F j, Y') }}
                            </span>
                        </div>

                        @if($newsEvent->excerpt)
                            <div class="alert alert-info">
                                <strong>{{ $newsEvent->excerpt }}</strong>
                            </div>
                        @endif

                        <div class="content mt-4" style="line-height: 1.8;">
                            {!! $newsEvent->content !!}
                        </div>

                        {{-- Image Gallery --}}
                        @if($newsEvent->additional_images && count($newsEvent->additional_images) > 0)
                            <div class="mt-5">
                                <h4 class="mb-3">Image Gallery</h4>
                                <div class="row g-3">
                                    @foreach($newsEvent->additional_images as $imageUrl)
                                        @php
                                            $directUrl = \App\Helpers\GoogleDriveHelper::isGoogleDriveUrl($imageUrl)
                                                ? \App\Helpers\GoogleDriveHelper::getDirectUrl($imageUrl)
                                                : $imageUrl;
                                        @endphp
                                        <div class="col-6 col-md-4 col-lg-3">
                                            <div class="gallery-item" onclick="window.open('{{ $directUrl }}', '_blank')">
                                                <img src="{{ $directUrl }}" class="gallery-img" alt="Gallery Image"
                                                    referrerpolicy="no-referrer">
                                                <div class="gallery-overlay">
                                                    <i class="fas fa-search-plus"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-5 pt-3 border-top">
                            <a href="{{ url('/') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
}
.gallery-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}
.gallery-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.gallery-overlay i {
    color: #fff;
    font-size: 2rem;
    transform: scale(0.7);
    transition: transform 0.3s ease;
}
.gallery-item:hover .gallery-img {
    transform: scale(1.08);
}
.gallery-item:hover .gallery-overlay {
    opacity: 1;
}
.gallery-item:hover .gallery-overlay i {
    transform: scale(1);
}
</style>
@endpush