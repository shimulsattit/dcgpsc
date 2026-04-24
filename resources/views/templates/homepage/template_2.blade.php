@extends('layouts.app')

@section('title', 'Home - ' . (\App\Models\HeaderSetting::first()?->site_name ?? 'Barishal Cantonment Public School & College'))

@section('content')

    {{-- Template 2: Modern Grid Layout --}}



    {{-- Slider Section - Full Width (Template 2 Style) --}}

    {{-- Ticker ABOVE Slider (if configured) --}}
    @if(($headerSettings->ticker_position ?? 'below_slider') === 'above_slider')
        @include('partials.notice-ticker', ['wrapContainer' => true, 'tickerMargin' => 'mt-2 mb-2'])
    @endif

    <div id="heroCarousel" class="carousel slide carousel-fade shadow-sm" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            @forelse($sliders as $key => $slider)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    @if($slider->link)
                        <a href="{{ $slider->link }}" target="_blank">
                            <img src="{{ $slider->image_url }}" class="d-block w-100 slider-img"
                                alt="{{ $slider->title ?? 'Slider Image' }}" referrerpolicy="no-referrer">
                        </a>
                    @else
                        <img src="{{ $slider->image_url }}" class="d-block w-100 slider-img"
                            alt="{{ $slider->title ?? 'Slider Image' }}" referrerpolicy="no-referrer">
                    @endif
                    @if($slider->title)
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-2 rounded">
                            <h5>{{ $slider->title }}</h5>
                        </div>
                    @endif
                </div>
            @empty
                <div class="carousel-item active">
                    <div class="d-flex align-items-center justify-content-center bg-light slider-img">
                        <div class="text-center text-muted">
                            <i class="fas fa-image fa-4x mb-3"></i>
                            <p>No sliders available.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        @if($sliders->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>

    {{-- Ticker BELOW Slider (default) --}}
    @if(($headerSettings->ticker_position ?? 'below_slider') !== 'above_slider')
        @include('partials.notice-ticker', ['wrapContainer' => true, 'tickerMargin' => 'mt-3 mb-3'])
    @endif



    {{-- Main Content Grid --}}
    <div class="container my-3">
        <div class="row g-4">
            {{-- Left Content Column --}}
            <div class="col-lg-8">

                {{-- Welcome Section (Template 1 Style) --}}
                @if(isset($welcomeSection) && $welcomeSection && $welcomeSection->is_active)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header text-white" style="background-color: var(--primary-color);">
                            <h5 class="mb-0">{{ $welcomeSection->title }}</h5>
                        </div>
                        <div class="card-body p-4">
                            @if($welcomeSection->image)
                                <img src="{{ $welcomeSection->image }}" alt="{{ $welcomeSection->title }}"
                                    class="img-fluid rounded shadow-sm mb-3 welcome-image" referrerpolicy="no-referrer">
                                <style>
                                    .welcome-image {
                                        float: left;
                                        margin-right: 20px;
                                        margin-bottom: 15px;
                                        max-width: 350px;
                                    }

                                    @media (max-width: 768px) {
                                        .welcome-image {
                                            float: none;
                                            display: block;
                                            margin: 0 auto 15px auto;
                                            max-width: 100%;
                                            width: 100%;
                                        }
                                    }
                                </style>
                            @endif

                            @php
                                // Reference content: ~1450 characters
                                // Show Read More if content exceeds this length
                                $content = strip_tags($welcomeSection->content);
                                $contentLength = strlen($content);
                                $showReadMore = $contentLength > 1500;

                                if ($showReadMore) {
                                    // Show first 1200 characters
                                    $limitedContent = substr($content, 0, 1200);
                                    // Find last complete word to avoid cutting mid-word
                                    $lastSpace = strrpos($limitedContent, ' ');
                                    if ($lastSpace !== false) {
                                        $limitedContent = substr($limitedContent, 0, $lastSpace);
                                    }
                                } else {
                                    $limitedContent = $content;
                                }
                            @endphp

                            <div class="welcome-content" style="text-align: justify; line-height: 1.8;">
                                {!! nl2br(e($limitedContent)) !!}
                                @if($showReadMore)
                                    <span>...</span>
                                @endif
                            </div>
                            <div style="clear: both;"></div>

                            @if($showReadMore)
                                <div class="mt-3 text-end">
                                    <a href="{{ route('welcome.show') }}" class="btn btn-sm px-4"
                                        style="background-color: var(--primary-color); color: white; border: none;">
                                        Details
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Menu Cards Section (Template 1 Style) --}}
                @if(isset($menuCards) && $menuCards->count() > 0)
                    @php
                        $globalTemplate = $settings['menu_card_template'] ?? 'template_1';
                    @endphp
                    <div class="row g-3 mb-4">
                        @foreach($menuCards as $card)
                            @if($globalTemplate === 'template_2')
                                @include('partials.menu-cards.template-2', ['card' => $card])
                            @elseif($globalTemplate === 'template_3')
                                @include('partials.menu-cards.template-3', ['card' => $card])
                            @else
                                @include('partials.menu-cards.template-1', ['card' => $card])
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- News & Events Section --}}
                @if(isset($newsEvents) && $newsEvents->count() > 0)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header text-white d-flex justify-content-between align-items-center"
                            style="background-color: var(--primary-color);">
                            <h5 class="mb-0">{{ $theme->news_events_title ?? 'NEWS AND EVENTS' }}</h5>
                            <a href="{{ route('news.index') }}" class="text-white text-decoration-none small"
                                style="background-color: var(--secondary-color); padding: 6px 16px; border-radius: 4px;">
                                View All <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                @foreach($newsEvents as $news)
                                    <div class="col-12 col-lg-4">
                                        <div class="card h-100 border-0 shadow-sm">
                                            @if($news->image)
                                                <img src="{{ $news->image }}" class="card-img-top" alt="{{ $news->title }}"
                                                    style="height: 200px; object-fit: cover;" loading="lazy">
                                            @endif
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title fw-bold">{{ Str::words($news->title, 7, '...') }}</h6>
                                                <div class="text-muted small mb-2">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $news->published_at->format('M d, Y') }}
                                                </div>
                                                @if($news->excerpt)
                                                    <p class="card-text text-muted small">{{ Str::limit($news->excerpt, 80) }}
                                                    </p>
                                                @endif
                                                <a href="{{ route('news.show', $news->slug) }}" class="btn btn-sm mt-auto"
                                                    style="background-color: transparent; color: var(--secondary-color); border: 1px solid var(--secondary-color);">
                                                    READ MORE
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Achievements Section --}}
                @if(isset($achievements) && $achievements->count() > 0)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header text-white d-flex justify-content-between align-items-center"
                            style="background-color: var(--primary-color);">
                            <h5 class="mb-0">{{ $theme->achievements_title ?? 'ACHIEVEMENTS' }}</h5>
                            <a href="{{ route('achievements.index') }}" class="text-white text-decoration-none small"
                                style="background-color: var(--secondary-color); padding: 6px 16px; border-radius: 4px;">
                                View All <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                @foreach($achievements as $achievement)
                                    <div class="col-12 col-lg-4">
                                        <div class="card h-100 shadow-sm border-0">
                                            @if($achievement->image)
                                                <img src="{{ $achievement->image }}" class="card-img-top"
                                                    alt="{{ $achievement->title }}" style="height: 200px; object-fit: cover;"
                                                    loading="lazy" referrerpolicy="no-referrer">
                                            @endif
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title fw-bold">
                                                    {{ Str::words($achievement->title, 7, '...') }}
                                                </h6>
                                                <div class="text-muted small mb-2">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $achievement->published_at->format('M d, Y') }}
                                                </div>
                                                @if($achievement->excerpt)
                                                    <p class="card-text text-muted small">
                                                        {{ Str::limit($achievement->excerpt, 80) }}
                                                    </p>
                                                @endif
                                                <a href="{{ route('achievement.show', $achievement->slug) }}"
                                                    class="btn btn-sm mt-auto"
                                                    style="background-color: transparent; color: var(--secondary-color); border: 1px solid var(--secondary-color);">
                                                    READ MORE
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Location Map Section --}}
                @include('partials.location-map')
            </div>

            {{-- Sidebar (Template 1 Style) --}}
            <div class="col-lg-4">
                {{-- Founder --}}
                @if(isset($founder) && $founder)
                    @include('partials.sidebar-message', ['message' => $founder, 'title' => $founder->title ?? 'Message of the Founder'])
                @endif
                {{-- Chief Patron --}}
                @if(isset($chiefPatron) && $chiefPatron)
                    @include('partials.sidebar-message', ['message' => $chiefPatron, 'title' => $chiefPatron->title ?? 'Message of the Chief Patron'])
                @endif

                {{-- Chairman --}}
                @if(isset($chairman) && $chairman)
                    @include('partials.sidebar-message', ['message' => $chairman, 'title' => $chairman->title ?? 'Message of the Chairman'])
                @endif

                {{-- Principal --}}
                @if(isset($principal) && $principal)
                    @include('partials.sidebar-message', ['message' => $principal, 'title' => $principal->title ?? 'Message of the Principal'])
                @endif

                {{-- Vice Principal --}}
                @if(isset($vicePrincipal) && $vicePrincipal)
                    @include('partials.sidebar-message', ['message' => $vicePrincipal, 'title' => $vicePrincipal->title ?? 'Message of the Vice Principal'])
                @endif

                {{-- Headmaster --}}
                @if(isset($headmaster) && $headmaster)
                    @include('partials.sidebar-message', ['message' => $headmaster, 'title' => $headmaster->title ?? 'Message of the Headmaster'])
                @endif

                {{-- Notice Board (Template 1 Style) --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header py-2" style="background-color: var(--primary-color); color: white;">
                        <h5 class="mb-0 fs-6 fw-bold">{{ $theme->all_notices_title ?? 'ALL PUBLISHED NOTICE' }}</h5>
                    </div>
                    <div class="card-body p-0" style="background-color: var(--accent-color);">
                        <div class="list-group list-group-flush">
                            @if(isset($notices) && $notices->count() > 0)
                                @foreach($notices as $notice)
                                    @php
                                        // Use page URL if available, otherwise use link or file
                                        $noticeUrl = $notice->page_id && $notice->page
                                            ? '/' . $notice->page->slug
                                            : ($notice->link ?? ($notice->file_url ? $notice->file_url : '#'));
                                    @endphp
                                    <a href="{{ $noticeUrl }}" target="{{ $notice->page_id ? '_self' : '_blank' }}"
                                        class="list-group-item list-group-item-action py-1 border-bottom d-flex align-items-center"
                                        style="background-color: var(--accent-color); padding-left: 5px; padding-right: 5px;">

                                        {{-- Date Box - Two Part Design --}}
                                        <div class="me-3 d-flex flex-column rounded overflow-hidden shadow-sm"
                                            style="width: 50px; flex-shrink: 0;">
                                            {{-- Month (Primary Color Background) --}}
                                            <div class="text-white text-center py-1"
                                                style="background-color: var(--primary-color); font-size: 0.6rem; font-weight: 600; letter-spacing: 0.5px;">
                                                {{ strtoupper($notice->published_at->format('M')) }}
                                            </div>
                                            {{-- Date (White Background) --}}
                                            <div class="bg-white text-center py-1"
                                                style="color: #333; font-size: 1.1rem; font-weight: 700; line-height: 1.2;">
                                                {{ $notice->published_at->format('d') }}
                                            </div>
                                        </div>

                                        <div class="flex-grow-1">
                                            <div class="fw-bold mb-0" style="color: var(--primary-color); font-size: .9rem;">
                                                {{ $notice->title }}
                                                @if($notice->file_url)
                                                    <i class="fas fa-file-pdf ms-2 text-danger"></i>
                                                @endif
                                                @if($notice->page_id)
                                                    <i class="fas fa-file-alt ms-2 text-success" title="Full page available"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                    @if(!$loop->last)
                                        <div style="border-bottom: 1px solid rgba(0,0,0,0.05); margin: 0 10px;"></div>
                                    @endif
                                @endforeach
                            @else
                                <div class="p-4 text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    <p>No notices published yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-center border-top-0 pb-3" style="background-color: var(--accent-color);">
                        <a href="{{ route('notices.index') }}" class="btn btn-sm px-4"
                            style="background-color: var(--secondary-color); color: white; border: none;">All
                            Notice</a>
                    </div>
                </div>

                {{-- Sidebar Widgets --}}
                @if(isset($sidebarWidgets))
                    @foreach($sidebarWidgets as $widget)
                        @include('partials.sidebar-widget', ['widget' => $widget])
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@endsection