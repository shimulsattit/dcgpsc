@extends('layouts.app')

@section('title', 'Home - ' . (\App\Models\HeaderSetting::first()?->site_name ?? 'Barishal Cantonment Public School & College'))

@section('content')

    {{-- Template 4: Full Width Magazine Style --}}
    
    {{-- Section 1: Hero Slider (Full Width) --}}
    <section class="magazine-hero position-relative">
        <div id="magazineCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                @forelse($sliders as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <div class="hero-slide-wrapper">
                            <img src="{{ $slider->image_url }}" class="d-block w-100" alt="{{ $slider->title }}" referrerpolicy="no-referrer">
                            <div class="magazine-caption">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <span class="badge bg-danger mb-2 px-3 py-2 fade-in">FEATURED</span>
                                            <h2 class="display-4 fw-bold text-white mb-3 fade-in">{{ $slider->title }}</h2>
                                            @if($slider->link)
                                                <a href="{{ $slider->link }}" class="btn btn-primary btn-lg rounded-pill px-4 fade-in">Explore More <i class="fas fa-arrow-right ms-2"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="carousel-item active">
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 600px;">
                            <h2 class="text-white">Welcome to our Institution</h2>
                        </div>
                    </div>
                @endforelse
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#magazineCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#magazineCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </section>

    {{-- Section 2: News Ticker --}}
    @include('partials.notice-ticker', ['wrapContainer' => true, 'tickerMargin' => 'my-0'])

    {{-- Section 3: Magazine Grid (Main Content) --}}
    <div class="container my-5">
        <div class="row g-4">
            {{-- Left Column: Main News & Info --}}
            <div class="col-lg-8">
                <div class="section-title-wrap mb-4">
                    <h3 class="fw-bold border-start border-4 border-primary ps-3">LATEST HIGHLIGHTS</h3>
                </div>

                <div class="row g-4">
                    {{-- Featured Notice Card --}}
                    @if($notices->count() > 0)
                        @php $firstNotice = $notices->first(); @endphp
                        <div class="col-12">
                            <div class="card featured-news-card border-0 shadow-sm overflow-hidden h-100">
                                <div class="row g-0 h-100">
                                    <div class="col-md-5 bg-primary d-flex align-items-center justify-content-center p-5 text-white">
                                        <div class="text-center">
                                            <i class="fas fa-bullhorn fa-4x mb-3"></i>
                                            <h4 class="fw-bold">IMPORTANT NOTICE</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="card-body p-4 d-flex flex-column h-100">
                                            <span class="text-muted small mb-2"><i class="far fa-calendar-alt me-1"></i> {{ $firstNotice->published_at->format('M d, Y') }}</span>
                                            <h3 class="fw-bold mb-3">{{ $firstNotice->title }}</h3>
                                            <p class="text-muted mb-4">{{ Str::limit(strip_tags($firstNotice->content ?? ''), 150) }}</p>
                                            <a href="{{ $firstNotice->page_id && $firstNotice->page ? '/'.$firstNotice->page->slug : ($firstNotice->link ?? ($firstNotice->file_url ?? '#')) }}" class="btn btn-outline-primary mt-auto align-self-start">Read Full Notice</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Secondary News Grid --}}
                    @foreach($newsEvents as $news)
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm magazine-news-item">
                                @if($news->image_url)
                                    <div class="img-wrapper overflow-hidden">
                                        <img src="{{ $news->image_url }}" class="card-img-top" alt="{{ $news->title }}" style="height: 200px; object-fit: cover;">
                                    </div>
                                @endif
                                <div class="card-body">
                                    <span class="badge bg-info mb-2">Updates</span>
                                    <h5 class="fw-bold mb-2">{{ Str::limit($news->title, 60) }}</h5>
                                    <p class="text-muted small">{{ Str::limit($news->excerpt, 100) }}</p>
                                    <a href="{{ route('news.show', $news->slug) }}" class="text-primary text-decoration-none fw-bold small">CONTINUE READING <i class="fas fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Welcome Section --}}
                @php
                    $welcomeSection = \App\Models\WelcomeSection::where('is_active', true)->first();
                @endphp
                @if(isset($welcomeSection) && $welcomeSection && $welcomeSection->is_active)
                    <div class="mt-5 p-4 bg-light rounded shadow-sm">
                        <h4 class="fw-bold mb-3">{{ $welcomeSection->title }}</h4>
                        <div class="magazine-welcome-content">
                            {!! Str::limit(strip_tags($welcomeSection->content), 400) !!}
                        </div>
                        <a href="{{ route('welcome.show') }}" class="btn btn-sm btn-primary mt-3">Learn More About Us</a>
                    </div>
                @endif
            </div>

            {{-- Right Column: Sidebar Panels --}}
            <div class="col-lg-4">
                {{-- Admin Messages in Magazine Style --}}
                <div class="magazine-sidebar">
                    @php
                        $principal = $messages->where('designation', 'Principal')->first();
                        $chairman = $messages->where('designation', 'Chairman')->first();
                        $headerSettings = \App\Models\HeaderSetting::first();
                        $importantLinks = \App\Models\ImportantLink::all();
                    @endphp

                    @if($principal)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-dark text-white fw-bold">FROM THE PRINCIPAL</div>
                            <div class="card-body text-center">
                                <img src="{{ $principal->image_url }}" class="rounded-circle mb-3 border border-4 border-light shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                                <h5 class="fw-bold mb-0">{{ $principal->name }}</h5>
                                <p class="text-muted small mb-3">Principal</p>
                                <p class="small text-muted italic">"{{ Str::limit($principal->message, 120) }}"</p>
                                <a href="{{ route('message.show', $principal->slug) }}" class="btn btn-sm btn-outline-dark">Full Message</a>
                            </div>
                        </div>
                    @endif

                    @if($chairman)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-dark text-white fw-bold">CHAIRMAN'S WORDS</div>
                            <div class="card-body d-flex align-items-center">
                                <img src="{{ $chairman->image_url }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $chairman->name }}</h6>
                                    <p class="text-muted extra-small mb-1">Chairman</p>
                                    <a href="{{ route('message.show', $chairman->slug) }}" class="small text-primary text-decoration-none">Read Message</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Important Links Panel --}}
                    @if(isset($importantLinks) && $importantLinks->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white fw-bold">QUICK NAVIGATION</div>
                        <div class="list-group list-group-flush">
                            @foreach($importantLinks as $link)
                                <a href="{{ $link->url }}" class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-chevron-right me-2 text-primary"></i> {{ $link->title }}</span>
                                    <i class="fas fa-link small text-muted"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Archive/Notices List --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-secondary text-white fw-bold">NOTICE BOARD</div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($notices->skip(1) as $notice)
                                    <a href="{{ $notice->file_url ?? '#' }}" class="list-group-item list-group-item-action border-0 py-3">
                                        <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">{{ $notice->title }}</h6>
                                        <span class="text-muted extra-small">{{ $notice->published_at->format('d M, Y') }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-center pb-3">
                            <a href="{{ route('notices.index') }}" class="btn btn-sm btn-link text-decoration-none">VIEW ALL NOTICES <i class="fas fa-long-arrow-alt-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 4: Video/Photo Gallery Highlights (Full Width Background) --}}
    <section class="gallery-highlights py-5 bg-dark text-white">
        <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h3 class="fw-bold">CAMPUS GALLERY</h3>
                    <p class="text-muted">A glimpse into our campus life and events.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('gallery.index') }}" class="btn btn-outline-light rounded-pill px-4">View Full Gallery</a>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="gallery-item overflow-hidden rounded shadow">
                        <img src="https://placehold.co/600x400?text=Campus+Event+1" class="img-fluid" alt="Gallery">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-item overflow-hidden rounded shadow">
                        <img src="https://placehold.co/600x400?text=Campus+Event+2" class="img-fluid" alt="Gallery">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-item overflow-hidden rounded shadow">
                        <img src="https://placehold.co/600x400?text=Campus+Event+3" class="img-fluid" alt="Gallery">
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .magazine-hero .hero-slide-wrapper {
            position: relative;
            height: 600px;
            overflow: hidden;
        }
        .magazine-hero img {
            height: 100%;
            object-fit: cover;
            filter: brightness(0.6);
        }
        .magazine-caption {
            position: absolute;
            bottom: 100px;
            width: 100%;
            z-index: 10;
        }
        .magazine-news-item {
            transition: all 0.3s ease;
        }
        .magazine-news-item:hover {
            transform: translateY(-5px);
        }
        .magazine-news-item .img-wrapper img {
            transition: transform 0.5s ease;
        }
        .magazine-news-item:hover .img-wrapper img {
            transform: scale(1.1);
        }
        .extra-small {
            font-size: 0.75rem;
        }
        .italic {
            font-style: italic;
        }
        .featured-news-card {
            background: #fff;
            transition: all 0.3s ease;
        }
        .featured-news-card:hover {
            box-shadow: 0 15px 45px rgba(0,0,0,0.1) !important;
        }
        .gallery-item {
            cursor: pointer;
            position: relative;
        }
        .gallery-item::after {
            content: '\f00e';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .gallery-item:hover::after {
            opacity: 1;
        }
        @media (max-width: 768px) {
            .magazine-hero .hero-slide-wrapper {
                height: 400px;
            }
            .magazine-caption {
                bottom: 40px;
            }
            .magazine-caption h2 {
                font-size: 2rem;
            }
        }
    </style>
    @endpush

@endsection