@extends('layouts.app')

@section('title', 'Photo Gallery')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header text-white py-3" style="background-color: var(--primary-color);">
                    <h3 class="mb-0 fw-bold" style="font-size: 1.2rem;">
                        <i class="fas fa-images me-2"></i>
                        Photo Gallery
                    </h3>
                </div>
            </div>
        </div>
    </div>

    @if($galleries->count() > 0)
        <div class="row g-4">
            @foreach($galleries as $gallery)
                <div class="col-md-3">
                    <a href="{{ route('gallery.show', $gallery->slug) }}" class="text-decoration-none folder-card-link">
                        <div class="folder-card position-relative bg-white shadow-sm rounded-3 overflow-hidden" 
                             style="transition: transform 0.3s ease, box-shadow 0.3s ease; height: 100%;">
                            
                            <!-- Folder Stack Effect -->
                            <div class="position-absolute start-0 w-100 bg-white border rounded-top"
                                 style="top: -6px; height: 10px; z-index: 1; transform: scaleX(0.95); box-shadow: 0 -2px 5px rgba(0,0,0,0.05);"></div>
                            <div class="position-absolute start-0 w-100 bg-white border rounded-top"
                                 style="top: -12px; height: 10px; z-index: 0; transform: scaleX(0.90); box-shadow: 0 -2px 5px rgba(0,0,0,0.03);"></div>

                            <!-- Main Card Content -->
                            <div class="position-relative" style="z-index: 2;">
                                <!-- Image Container -->
                                <div class="ratio ratio-16x9 overflow-hidden custom-border border-bottom-0">
                                    @if($gallery->thumbnail_url)
                                        <img src="{{ $gallery->thumbnail_url }}" 
                                             class="w-100 h-100 object-fit-cover" 
                                             alt="{{ $gallery->title }}"
                                             referrerpolicy="no-referrer"
                                             onerror="this.onerror=null; this.src='https://via.placeholder.com/400x225?text=Preview+Unavailable';">
                                    @else
                                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                            <i class="fas fa-folder fa-3x"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Card Body -->
                                <div class="p-3 bg-white border-top custom-border border-top-0">
                                    <h3 class="h6 fw-bold mb-1 text-dark text-truncate" style="color: var(--primary-color) !important;">
                                        {{ $gallery->title }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $galleries->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-3"><i class="far fa-images"></i></div>
            <h3 class="text-muted">No photo albums found.</h3>
        </div>
    @endif
</div>

<style>
    .folder-card-link:hover .folder-card {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .folder-card {
        border: 1px solid rgba(0,0,0,0.08); 
        margin-top: 12px; /* Space for the stack effect */
    }

    .custom-border {
         border: 5px solid #fff;
    }
</style>
@endsection
