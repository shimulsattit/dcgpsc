@extends('layouts.app')

@section('title', 'Home - ' . (\App\Models\HeaderSetting::first()?->site_name ?? 'Cantonment Public School and College'))

@section('content')

{{-- Custom CSS for CPSCM Style --}}
@push('styles')
<style>
    .cpscm-card {
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 20px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .cpscm-card-header {
        background-color: var(--primary-color, #006a4e);
        color: white;
        padding: 8px 15px;
        font-weight: bold;
        font-size: 0.95rem;
        border-radius: 4px 4px 0 0;
    }
    .sidebar-msg-card {
        display: flex;
        padding: 10px;
        align-items: center;
        border-bottom: 1px solid #eee;
    }
    .sidebar-msg-img {
        width: 60px;
        height: 70px;
        object-fit: cover;
        margin-right: 12px;
        border: 1px solid #ddd;
    }
    .sidebar-msg-info h6 {
        margin: 0;
        font-size: 0.85rem;
        font-weight: bold;
        color: var(--primary-color);
    }
    .sidebar-msg-info p {
        margin: 0;
        font-size: 0.75rem;
        color: #666;
    }
    .news-card-img {
        height: 160px;
        object-fit: cover;
    }
    .notice-item {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        transition: all 0.3s;
    }
    .notice-item:hover {
        background-color: #f9f9f9;
    }
    .notice-date {
        font-size: 0.75rem;
        color: var(--secondary-color, #f42a41);
        font-weight: bold;
    }
    .notice-title {
        font-size: 0.85rem;
        font-weight: 600;
        display: block;
        color: #333;
        text-decoration: none;
    }
    .achievement-card img {
        height: 180px;
        object-fit: cover;
    }
    .welcome-section {
        background: #fff;
        padding: 25px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 30px;
        text-align: justify;
    }
    .gallery-grid img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        margin-bottom: 15px;
        border: 4px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>
@endpush

<div class="container my-4">
    {{-- Top Section: Slider & Sidebar Messages --}}
    <div class="row g-3">
        {{-- Slider --}}
        <div class="col-lg-9">
            <div id="heroCarousel" class="carousel slide shadow-sm" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @forelse($sliders as $key => $slider)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img src="{{ $slider->image_url }}" class="d-block w-100" style="height: 450px; object-fit: cover;" alt="Slider">
                        </div>
                    @empty
                        <div class="carousel-item active">
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 450px;">No Slider Available</div>
                        </div>
                    @endforelse
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>

            {{-- News Ticker under slider (Optional, based on screenshot) --}}
            @include('partials.notice-ticker', ['wrapContainer' => false, 'tickerMargin' => 'mt-2'])
        </div>

        {{-- Sidebar Messages --}}
        <div class="col-lg-3">
            {{-- Chairman's Message --}}
            @php $chairman = $messages->where('designation', 'Chairman')->first(); @endphp
            <div class="cpscm-card">
                <div class="cpscm-card-header">Chairman's Message</div>
                <div class="card-body p-0">
                    @if($chairman)
                    <div class="sidebar-msg-card">
                        <img src="{{ $chairman->image_url }}" class="sidebar-msg-img" alt="Chairman">
                        <div class="sidebar-msg-info">
                            <h6>{{ $chairman->name }}</h6>
                            <p>Chairman</p>
                            <a href="{{ route('message.show', $chairman->slug) }}" class="btn btn-link p-0 extra-small text-decoration-none">Read More</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Principal's Message --}}
            @php $principal = $messages->where('designation', 'Principal')->first(); @endphp
            <div class="cpscm-card">
                <div class="cpscm-card-header">Principal's Message</div>
                <div class="card-body p-0">
                    @if($principal)
                    <div class="sidebar-msg-card">
                        <img src="{{ $principal->image_url }}" class="sidebar-msg-img" alt="Principal">
                        <div class="sidebar-msg-info">
                            <h6>{{ $principal->name }}</h6>
                            <p>Principal</p>
                            <a href="{{ route('message.show', $principal->slug) }}" class="btn btn-link p-0 extra-small text-decoration-none">Read More</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Dynamic Sidebar Widgets (Why Study, etc.) --}}
            @foreach($sidebarWidgets ?? [] as $widget)
                <div class="cpscm-card">
                    <div class="cpscm-card-header">{{ $widget->title }}</div>
                    <div class="card-body p-2 text-center">
                        {!! $widget->content !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="row mt-4">
        {{-- Main Left Column --}}
        <div class="col-lg-9">
            {{-- Welcome Message --}}
            @php $welcomeSection = \App\Models\WelcomeSection::where('is_active', true)->first(); @endphp
            @if($welcomeSection)
            <div class="welcome-section shadow-sm">
                <h4 class="fw-bold mb-3 text-center border-bottom pb-2">{{ $welcomeSection->title }}</h4>
                <div style="font-size: 0.95rem; line-height: 1.7; color: #444;">
                    {!! nl2br(e($welcomeSection->content)) !!}
                </div>
            </div>
            @endif

            {{-- News & Events Section --}}
            <div class="cpscm-card">
                <div class="cpscm-card-header text-center">News & Events</div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        @foreach($newsEvents as $news)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm achievement-card">
                                @if($news->image_url)
                                <img src="{{ $news->image_url }}" class="news-card-img card-img-top" alt="News">
                                @endif
                                <div class="card-body p-2">
                                    <h6 class="fw-bold mb-1" style="font-size: 0.85rem;">{{ Str::limit($news->title, 50) }}</h6>
                                    <p class="text-muted extra-small mb-2"><i class="far fa-calendar-alt"></i> {{ $news->published_at->format('M d, Y') }}</p>
                                    <a href="{{ route('news.show', $news->slug) }}" class="btn btn-outline-dark btn-sm py-0 px-2 extra-small">Read More</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Our Achievements Section --}}
            <div class="cpscm-card">
                <div class="cpscm-card-header text-center">Our Achievements</div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        @foreach($achievements as $achievement)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm achievement-card">
                                @if($achievement->image_url)
                                <img src="{{ $achievement->image_url }}" class="card-img-top" alt="Achievement">
                                @endif
                                <div class="card-body p-2">
                                    <h6 class="fw-bold mb-1" style="font-size: 0.85rem;">{{ Str::limit($achievement->title, 50) }}</h6>
                                    <p class="text-muted extra-small mb-2"><i class="far fa-calendar-alt"></i> {{ $achievement->published_at->format('M d, Y') }}</p>
                                    <a href="{{ route('achievement.show', $achievement->slug) }}" class="btn btn-outline-dark btn-sm py-0 px-2 extra-small">Read More</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Location Map (Google Map) --}}
            <div class="cpscm-card">
                <div class="cpscm-card-header">Location (Google Map)</div>
                <div class="card-body p-0">
                    @include('partials.location-map', ['wrapContainer' => false])
                </div>
            </div>

            {{-- Photo Gallery Section --}}
            <div class="cpscm-card">
                <div class="cpscm-card-header text-center">Photo Gallery</div>
                <div class="card-body p-3">
                    <div class="row g-2 gallery-grid">
                        @php $galleries = \App\Models\PhotoGallery::latest()->take(6)->get(); @endphp
                        @foreach($galleries as $gallery)
                        <div class="col-md-4 col-6">
                            <a href="{{ route('gallery.show', $gallery->slug) }}">
                                <img src="{{ $gallery->image_url }}" alt="Gallery Image">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Right Column --}}
        <div class="col-lg-3">
            {{-- Notices Widget --}}
            <div class="cpscm-card">
                <div class="cpscm-card-header text-center">Notices</div>
                <div class="card-body p-0">
                    <div class="notices-list" style="max-height: 400px; overflow-y: auto;">
                        @foreach($notices as $notice)
                        <div class="notice-item">
                            <span class="notice-date">{{ $notice->published_at->format('F d, Y') }}</span>
                            <a href="{{ $notice->file_url ?? '#' }}" target="_blank" class="notice-title">{{ $notice->title }}</a>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center py-2 border-top">
                        <a href="{{ route('notices.index') }}" class="btn btn-link btn-sm extra-small text-decoration-none">View All Notices</a>
                    </div>
                </div>
            </div>

            {{-- Important Links Panel --}}
            <div class="cpscm-card">
                <div class="cpscm-card-header">Important Links</div>
                <div class="list-group list-group-flush">
                    @php $importantLinks = \App\Models\ImportantLink::all(); @endphp
                    @foreach($importantLinks as $link)
                    <a href="{{ $link->url }}" class="list-group-item list-group-item-action py-2 extra-small border-0 border-bottom">
                        <i class="fas fa-chevron-right me-2 text-primary"></i> {{ $link->title }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Social Media / Facebook Page --}}
            <div class="cpscm-card">
                <div class="cpscm-card-header">Facebook Page</div>
                <div class="card-body p-2 text-center">
                    {{-- Placeholder for Facebook Plugin --}}
                    <div class="bg-light p-4 text-muted small">Facebook Feed Area</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .extra-small { font-size: 0.75rem; }
</style>

@endsection