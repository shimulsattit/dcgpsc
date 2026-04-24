@php
    $header = $headerSettings ?? null;
@endphp

<!-- Top Bar -->
@if($header && ($header->show_top_bar ?? true))
<div class="top-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-12 text-center text-md-start mb-2 mb-md-0">
                @if($header && $header->email)
                    <i class="fas fa-envelope me-2"></i> {{ $header->email }}
                @endif
                @if($header && $header->email && $header->phones && count($header->phones) > 0)
                    <span class="mx-2">|</span>
                @endif
                @if($header && $header->phones && count($header->phones) > 0)
                    <i class="fas fa-phone me-2"></i>
                    @foreach($header->phones as $index => $phone)
                        {{ $phone['number'] ?? $phone }}
                        @if($index < count($header->phones) - 1)
                            <span class="mx-2">|</span>
                        @endif
                    @endforeach
                @endif
            </div>
            <div
                class="col-md-6 col-12 text-center text-md-end d-flex flex-wrap align-items-center justify-content-center justify-content-md-end gap-2">
                {{-- Social Media Links --}}
                @if($header && $header->facebook_url)
                    <a href="{{ $header->facebook_url }}" class="text-dark me-1" target="_blank"><i
                            class="fab fa-facebook"></i></a>
                @endif
                @if($header && $header->youtube_url)
                    <a href="{{ $header->youtube_url }}" class="text-dark me-1" target="_blank"><i
                            class="fab fa-youtube"></i></a>
                @endif
                @if($header && $header->twitter_url)
                    <a href="{{ $header->twitter_url }}" class="text-dark me-1" target="_blank"><i
                            class="fab fa-twitter"></i></a>
                @endif
                @if($header && $header->instagram_url)
                    <a href="{{ $header->instagram_url }}" class="text-dark me-1" target="_blank"><i
                            class="fab fa-instagram"></i></a>
                @endif
                @if($header && $header->linkedin_url)
                    <a href="{{ $header->linkedin_url }}" class="text-dark me-1" target="_blank"><i
                            class="fab fa-linkedin"></i></a>
                @endif

                {{-- Login Link --}}
                @php
                    $hasSocialLinks = $header && ($header->facebook_url || $header->youtube_url || $header->twitter_url || $header->instagram_url || $header->linkedin_url);
                    $hasButtons = $header && $header->action_buttons && is_array($header->action_buttons) && count($header->action_buttons) > 0;
                @endphp
                @if(($hasSocialLinks || $hasButtons) && (!$header || $header->show_login_link))
                    <span class="mx-1">|</span>
                @endif
                @if(!$header || $header->show_login_link)
                    <a href="{{ url('/admin') }}" class="text-dark">Login</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Main Header (Logo) - Template 1 Style (No Ticker) -->
<div class="main-header">
    <div class="container">
        <div class="row align-items-center g-2">
            <div class="col-md-1 col-12 text-center d-flex justify-content-center justify-content-md-start">
                <a href="{{ url('/') }}" title="Go to Homepage">
                    @if($header && $header->logo)
                        <img src="{{ $header->logo }}" alt="Logo" class="img-fluid"
                            style="max-height: 100px; cursor: pointer;"
                            referrerpolicy="no-referrer">
                    @else
                        <img src="{{ $settings['logo'] ?? 'https://bacpsc.edu.bd/wp-content/uploads/2020/01/logo.png' }}"
                            alt="Logo" class="img-fluid"
                            style="max-height: 100px; cursor: pointer;">
                    @endif
                </a>
            </div>
            <div class="col-md-8 col-12 ps-2 text-center text-md-start">
                <h3 class="mb-1 fw-bold text-white"
                    style="font-size: {{ $header->site_name_font_size ?? '1.5rem' }}; letter-spacing: 0.5px;">
                    {{ $header->site_name ?? $settings['site_name'] ?? 'BARISHAL CANTONMENT PUBLIC SCHOOL & COLLEGE' }}
                </h3>
                <!-- Google Font for Bangla -->
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap');
                </style>
                <h5 class="mb-1 text-white"
                    style="font-family: 'Hind Siliguri', sans-serif; font-weight: 500; font-size: 1.3rem;">
                    {{ $header->site_name_bangla ?? $settings['site_name_bangla'] ?? 'বরিশাল ক্যান্টনমেন্ট পাবলিক স্কুল ও কলেজ' }}
                </h5>
                <p class="mb-0 text-white-50" style="font-size: 0.95rem;">
                    {{ $header->eiin ?? $settings['eiin'] ?? 'Barishal Cantonment, EIIN:136998' }}
                </p>
            </div>
            <div class="col-md-3 col-12 text-center text-md-end">
                {{-- Header Action Buttons --}}
                <div class="d-flex flex-column align-items-center align-items-md-end gap-2">
                    @if($header && $header->action_buttons && is_array($header->action_buttons))
                        @php
                            $visibleButtons = collect($header->action_buttons)
                                ->filter(function ($button) {
                                    return !str_starts_with($button['url'] ?? '', '#');
                                })
                                ->sortBy('order');
                        @endphp
                        @foreach($visibleButtons as $button)
                            <a href="{{ $button['url'] }}" class="btn btn-sm fw-bold"
                                style="background-color: {{ $button['bg_color'] ?? 'var(--primary-color)' }}; color: {{ $button['text_color'] ?? '#ffffff' }}; border: none; border-radius: 4px; padding: 6px 12px; font-size: 0.8rem; width: 130px; text-align: center; display: inline-block;"
                                target="{{ str_starts_with($button['url'] ?? '', 'http') ? '_blank' : '_self' }}">
                                {{ $button['label'] }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Navigation -->
<nav class="navbar navbar-expand-md navbar-dark navbar-custom sticky-top" style="background-color: var(--navbar-bg-color, #1e5540); border-bottom: 1px solid rgba(255,255,255,0.1);">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation" style="border-color: rgba(255,255,255,0.5);">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav" style="visibility: visible !important;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @php
                    $menus = \App\Models\Menu::where(function ($q) {
                        $q->whereNull('parent_id')->orWhere('parent_id', 0)->orWhere('parent_id', '');
                    })->where('is_active', true)->with('children')->orderBy('order')->get();
                @endphp
                @forelse($menus as $menu)
                    @if($menu->children->count() > 0)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="{{ $menu->url ?? '#' }}"
                                id="navbarDropdown{{ $menu->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                                @if($menu->is_highlighted && $menu->highlight_color)
                                    style="background-color: {{ $menu->highlight_color }} !important; color: #ffffff !important; font-weight: 600; padding: 8px 15px; border-radius: 5px;"
                                @endif>
                                {{ $menu->title }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $menu->id }}">
                                @foreach($menu->children as $child)
                                    <li>
                                        <a class="dropdown-item" href="{{ $child->url ?? '#' }}" @if($child->is_highlighted && $child->highlight_color)
                                            style="background-color: {{ $child->highlight_color }} !important; color: #ffffff !important; font-weight: 600; padding: 8px 15px; border-radius: 5px;"
                                        @endif>
                                            {{ $child->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ $menu->url ?? '#' }}" @if($menu->is_highlighted && $menu->highlight_color)
                                style="background-color: {{ $menu->highlight_color }} !important; color: #ffffff !important; font-weight: 600; padding: 8px 15px; border-radius: 5px;"
                            @endif>
                                {{ $menu->title }}
                            </a>
                        </li>
                    @endif
                @empty
                    <li class="nav-item"><a class="nav-link text-white" href="/">Home</a></li>
                @endforelse
            </ul>
        </div>
    </div>
</nav>