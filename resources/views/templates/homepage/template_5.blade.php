@extends('layouts.app')

@section('title', 'Home - ' . (\App\Models\HeaderSetting::first()?->site_name ?? 'School Name'))

@section('content')
    <style>
        :root {
            --t5-primary: #1a3a6e;
            --t5-primary-light: #2056a8;
            --t5-accent: #e8a020;
            --t5-accent2: #c0392b;
            --t5-green: #1e7e34;
            --t5-light-bg: #f4f7fb;
            --t5-white: #ffffff;
            --t5-text: #222831;
            --t5-text-muted: #6c7a8d;
            --t5-border: #dde3ed;
            --t5-shadow: 0 4px 24px rgba(26,58,110,0.10);
            --t5-shadow-lg: 0 8px 40px rgba(26,58,110,0.16);
        }

        /* ==========================================================================
           4. SLIDER
           ========================================================================== */
        .slider-section { position: relative; overflow: hidden; background: #0d2147; }
        .slides { display: flex; transition: transform 0.7s cubic-bezier(.77,0,.18,1); }
        .slide {
            min-width: 100%;
            height: 480px;
            position: relative;
            overflow: hidden;
        }
        .slide-bg {
            width: 100%; height: 100%;
            object-fit: cover; display: block;
        }
        .slide-overlay {
            position: absolute; inset: 0;
            background: transparent;
            display: flex; align-items: center;
            padding: 0 80px;
        }
        .slide-content { max-width: 600px; }
        .slide-content .tag {
            display: inline-block;
            background: var(--t5-accent);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 20px;
            margin-bottom: 14px;
        }
        .slide-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 14px;
            text-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }
        .slide-content p {
            font-size: 16px;
            color: rgba(255,255,255,0.85);
            margin-bottom: 24px;
        }
        .slide-btn {
            background: var(--t5-accent);
            color: #fff;
            padding: 12px 32px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
            display: inline-block;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(232,160,32,0.4);
            text-decoration: none;
        }
        .slide-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(232,160,32,0.5); }

        .slider-prev, .slider-next {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: var(--t5-primary);
            border: 2px solid rgba(255,255,255,0.2);
            color: #fff;
            width: 46px; height: 46px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 10;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .slider-prev:hover, .slider-next:hover { 
            background: var(--t5-primary-light); 
            border-color: #fff;
            transform: translateY(-50%) scale(1.1);
        }
        .slider-prev { left: 20px; }
        .slider-next { right: 20px; }

        .slider-dots {
            position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%);
            display: flex; gap: 8px;
        }
        .dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            border: none;
        }
        .dot.active { background: var(--t5-accent); transform: scale(1.3); }

        /* ==========================================================================
           GENERAL
           ========================================================================== */
        .section-header { text-align: center; margin-bottom: 40px; }
        .section-tag {
            display: inline-block;
            background: #e8f0fc;
            color: var(--t5-primary-light);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 5px 16px;
            border-radius: 20px;
            margin-bottom: 10px;
            border: 1px solid #c5d5f5;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 30px;
            font-weight: 800;
            color: var(--t5-primary);
            margin-bottom: 10px;
        }
        .section-divider {
            width: 60px; height: 4px;
            background: linear-gradient(90deg, var(--t5-accent), var(--t5-accent2));
            border-radius: 4px;
            margin: 10px auto 0;
        }

        /* ==========================================================================
           6. LEADERSHIP CARDS
           ========================================================================== */
        .leadership-section { padding: 60px 0; background: var(--t5-light-bg); }
        .cards-grid { display: flex; justify-content: center; flex-wrap: wrap; gap: 60px; }
        .leader-card {
            width: 350px;
            background: var(--t5-white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--t5-shadow);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .leader-card:hover { transform: translateY(-6px); box-shadow: var(--t5-shadow-lg); }
        .card-top { height: 8px; }
        .chairman-card .card-top { background: linear-gradient(90deg, #0d2147, #2056a8); }
        .principal-card .card-top { background: linear-gradient(90deg, #1e7e34, #27ae60); }
        .vice-card .card-top { background: linear-gradient(90deg, #7d1a1a, #c0392b); }
        .card-photo-wrap { padding: 28px 28px 16px; }
        .card-photo {
            width: 180px; height: 220px; border-radius: 12px; object-fit: cover;
            margin: 0 auto; background: #dee8f5; border: 3px solid transparent;
            box-shadow: 0 4px 16px rgba(26,58,110,0.15);
            display: block;
        }
        .chairman-card .card-photo { border-color: #2056a8; }
        .principal-card .card-photo { border-color: #1e7e34; }
        .vice-card .card-photo { border-color: #c0392b; }
        .card-body { padding: 0 24px 28px; }
        .card-role { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 6px; }
        .chairman-card .card-role { color: #2056a8; }
        .principal-card .card-role { color: #1e7e34; }
        .vice-card .card-role { color: #c0392b; }
        .card-name { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; color: var(--t5-primary); margin-bottom: 8px; }
        .card-desc { font-size: 13px; color: var(--t5-text-muted); line-height: 1.6; margin-bottom: 16px; }
        .card-msg-btn {
            display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600;
            padding: 8px 20px; border-radius: 30px; border: 2px solid; transition: all 0.2s;
            cursor: pointer; background: transparent; text-decoration: none;
        }
        .chairman-card .card-msg-btn { border-color: #2056a8; color: #2056a8; }
        .chairman-card .card-msg-btn:hover { background: #2056a8; color: #fff; }
        .principal-card .card-msg-btn { border-color: #1e7e34; color: #1e7e34; }
        .principal-card .card-msg-btn:hover { background: #1e7e34; color: #fff; }
        .vice-card .card-msg-btn { border-color: #c0392b; color: #c0392b; }
        .vice-card .card-msg-btn:hover { background: #c0392b; color: #fff; }

        /* ==========================================================================
           7. HISTORY + NOTICE BOARD
           ========================================================================== */
        .history-notice-section { padding: 60px 0; background: var(--t5-white); }
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 36px; align-items: start; }
        .history-box h3 {
            font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 800; color: var(--t5-primary);
            margin-bottom: 6px; display: flex; align-items: center; gap: 10px;
        }
        .history-box h3::after { content: ''; flex: 1; height: 3px; background: linear-gradient(90deg, var(--t5-accent), transparent); border-radius: 2px; }
        .history-img {
            width: 100%; height: 280px; object-fit: cover; border-radius: 12px;
            background: linear-gradient(135deg, #1a3a6e, #2e86c1);
            display: flex; align-items: center; justify-content: center; font-size: 60px; margin: 16px 0;
        }
        .history-box p { font-size: 16px; color: #4a5568; line-height: 1.8; text-align: justify; }
        .history-box p + p { margin-top: 12px; }
        .read-more-link {
            display: inline-flex; align-items: center; gap: 6px; margin-top: 16px;
            font-size: 13px; font-weight: 700; color: var(--t5-primary-light);
            border-bottom: 2px solid var(--t5-accent); padding-bottom: 2px; text-decoration: none;
        }

        /* Notice Board */
        .notice-board { background: var(--t5-white); border-radius: 16px; overflow: hidden; box-shadow: var(--t5-shadow); border: 1px solid var(--t5-border); }
        .nb-header { background: linear-gradient(135deg, var(--t5-primary), var(--t5-primary-light)); padding: 16px 22px; display: flex; align-items: center; gap: 12px; }
        .nb-header h3 { color: #fff; font-size: 15px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin: 0; }
        .nb-header .nb-badge { margin-left: auto; background: var(--t5-accent2); color: #fff; font-size: 11px; font-weight: 700; border-radius: 20px; padding: 3px 11px; }
        .nb-list { padding: 8px 0; max-height: 380px; overflow-y: auto; }
        .nb-item { display: flex; align-items: center; gap: 14px; padding: 13px 20px; border-bottom: 1px solid #f0f4fb; transition: background 0.2s; cursor: pointer; text-decoration: none; }
        .nb-item:hover { background: #f4f7fb; }
        .nb-date { min-width: 48px; text-align: center; background: linear-gradient(135deg, var(--t5-primary), var(--t5-primary-light)); border-radius: 8px; padding: 6px 4px; color: #fff; }
        .nb-date .m { font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; opacity: 0.85; }
        .nb-date .d { font-size: 20px; font-weight: 800; line-height: 1.1; }
        .nb-content { flex: 1; }
        .nb-title { font-size: 14px; font-weight: 600; color: var(--t5-primary); line-height: 1.4; }
        .nb-sub { font-size: 11px; color: var(--t5-text-muted); margin-top: 2px; }
        .nb-new { background: #e8f6fd; color: var(--t5-primary-light); font-size: 9px; font-weight: 700; border-radius: 4px; padding: 2px 7px; border: 1px solid #bee3f8; }
        .nb-footer { background: #f7fafd; border-top: 1px solid var(--t5-border); padding: 14px; text-align: center; }
        .nb-all-btn { background: linear-gradient(135deg, var(--t5-primary), var(--t5-primary-light)); color: #fff; border: none; border-radius: 30px; padding: 9px 32px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-block; }

        /* ==========================================================================
           8. ACHIEVEMENTS (STATS)
           ========================================================================== */
        .achievement-section { padding: 60px 0; background: linear-gradient(135deg, #0d2147 0%, #1a3a6e 50%, #2056a8 100%); position: relative; overflow: hidden; }
        .achievement-section .section-title { color: #fff; }
        .achievement-section .section-tag { background: rgba(255,255,255,0.1); color: #c8d6f0; border-color: rgba(255,255,255,0.2); }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 40px; }
        .stat-card { text-align: center; padding: 28px 16px; background: rgba(255,255,255,0.08); border-radius: 14px; border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(6px); transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-4px); background: rgba(255,255,255,0.14); }
        .stat-icon { font-size: 36px; margin-bottom: 12px; }
        .stat-number { font-family: 'Playfair Display', serif; font-size: 40px; font-weight: 800; color: var(--t5-accent); line-height: 1; }
        .stat-label { font-size: 13px; color: rgba(255,255,255,0.75); margin-top: 6px; font-weight: 500; }
        .achieve-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .achieve-card { background: rgba(255,255,255,0.08); border-radius: 12px; padding: 22px; border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: flex-start; gap: 14px; }
        .achieve-icon { width: 44px; height: 44px; border-radius: 10px; background: var(--t5-accent); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
        .achieve-text h4 { font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 4px; }
        .achieve-text p { font-size: 12px; color: rgba(255,255,255,0.65); line-height: 1.5; }

        /* ==========================================================================
           9. EVENTS & NEWS
           ========================================================================== */
        .events-section { padding: 60px 0; background: var(--t5-light-bg); }
        .events-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .event-card { background: var(--t5-white); border-radius: 14px; overflow: hidden; box-shadow: var(--t5-shadow); transition: transform 0.3s; }
        .event-card:hover { transform: translateY(-5px); box-shadow: var(--t5-shadow-lg); }
        .event-img { height: 220px; display: flex; align-items: center; justify-content: center; font-size: 52px; position: relative; background: #ddd; }
        .event-cat { position: absolute; top: 12px; left: 12px; background: var(--t5-accent); color: #fff; font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; }
        .event-body { padding: 18px; }
        .event-date { font-size: 11px; color: var(--t5-text-muted); margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
        .event-title { font-size: 18px; font-weight: 700; color: var(--t5-primary); line-height: 1.4; margin-bottom: 8px; }
        .event-desc { font-size: 14px; color: var(--t5-text-muted); line-height: 1.6; }
        .event-link { display: inline-flex; align-items: center; gap: 5px; margin-top: 12px; font-size: 12px; font-weight: 700; color: var(--t5-primary-light); text-decoration: none; }

        /* ==========================================================================
           10. MAP
           ========================================================================== */
        .map-section { padding: 30px 0 60px; background: var(--t5-white); }
        .map-wrapper { border-radius: 16px; overflow: hidden; box-shadow: var(--t5-shadow-lg); border: 3px solid var(--t5-border); display: grid; grid-template-columns: 1fr 300px; }
        .map-iframe-wrap { height: 380px; }
        .map-iframe-wrap iframe { width: 100%; height: 100%; border: none; }
        .map-info { background: linear-gradient(160deg, var(--t5-primary), var(--t5-primary-light)); padding: 32px 24px; color: #fff; }
        .map-info h3 { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid rgba(232,160,32,0.5); }
        .map-detail { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; font-size: 13px; line-height: 1.6; color: rgba(255,255,255,0.85); }
        .map-detail .mi { font-size: 18px; flex-shrink: 0; margin-top: 2px; }
        .map-get-btn { display: block; background: var(--t5-accent); color: #fff; text-align: center; padding: 11px 20px; border-radius: 30px; font-weight: 700; font-size: 13px; margin-top: 24px; text-decoration: none; }

        @media (max-width: 900px) {
            .stats-grid, .achieve-cards, .events-grid { grid-template-columns: 1fr 1fr; }
            .two-col, .map-wrapper { grid-template-columns: 1fr; }
            .slide-content h2 { font-size: 28px; }
            .slide-overlay { padding: 0 40px; }
            .leader-card { width: calc(50% - 28px); min-width: 300px; }
        }
        @media (max-width: 600px) {
            .stats-grid, .achieve-cards, .events-grid { grid-template-columns: 1fr; }
            .leader-card { width: 100%; min-width: unset; }
        }
    </style>

    @php
        $headerSetting = \App\Models\HeaderSetting::first();
        $tickerPos = $headerSetting->ticker_position ?? 'below_slider';
    @endphp

    {{-- News Ticker (Above Slider) --}}
    @if($tickerPos == 'above_slider')
        @include('partials.notice-ticker', ['wrapContainer' => true])
    @endif

    {{-- Slider Section --}}
    <section class="slider-section">
        <div class="slides" id="t5SliderTrack">
            @forelse($sliders as $key => $slider)
                <div class="slide">
                    <img src="{{ $slider->image_url }}" class="slide-bg" alt="{{ $slider->title }}">
                    <div class="slide-overlay">
                        <div class="slide-content">
                            <h2>{{ $slider->title }}</h2>
                            <p>{{ $slider->description }}</p>
                            @if($slider->link)
                                <a href="{{ $slider->link }}" class="slide-btn">বিস্তারিত দেখুন →</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="slide" style="background: var(--t5-primary); display: flex; align-items: center; justify-content: center; color: #fff;">
                    <h3>No sliders available.</h3>
                </div>
            @endforelse
        </div>
        <button class="slider-prev" onclick="changeT5Slide(-1)">‹</button>
        <button class="slider-next" onclick="changeT5Slide(1)">›</button>
        <div class="slider-dots" id="t5SliderDots">
            @foreach($sliders as $key => $slider)
                <button class="dot {{ $key == 0 ? 'active' : '' }}" onclick="goT5Slide({{ $key }})"></button>
            @endforeach
        </div>
    </section>

    {{-- News Ticker (Below Slider or Footer) --}}
    @if($tickerPos == 'below_slider')
        @include('partials.notice-ticker', ['wrapContainer' => true])
    @endif

    @if($tickerPos == 'footer')
        @include('partials.notice-ticker', ['isSticky' => true])
    @endif

    {{-- Leadership Section --}}
    <section class="leadership-section">
        <div class="container">
            <div class="section-header">
                <div class="section-tag">Administration</div>
                <h2 class="section-title">Leadership</h2>
                <div class="section-divider"></div>
            </div>
            <div class="cards-grid">
                @php
                    $leaders = \App\Models\Message::active()->get()->sort(function($a, $b) {
                        $order = ['chairman' => 1, 'vice chairman' => 2, 'principal' => 3, 'vice principal' => 4];
                        $aOrder = $order[strtolower($a->designation)] ?? 99;
                        $bOrder = $order[strtolower($b->designation)] ?? 99;
                        return $aOrder <=> $bOrder;
                    });
                    $roles = ['chairman-card', 'principal-card', 'vice-card'];
                @endphp
                @foreach($leaders as $leader)
                    @php
                        $designation = strtolower($leader->designation);
                        $roleClass = 'vice-card'; // Default
                        if (str_contains($designation, 'chairman')) $roleClass = 'chairman-card';
                        elseif (str_contains($designation, 'principal') && !str_contains($designation, 'vice')) $roleClass = 'principal-card';
                    @endphp
                    <div class="leader-card {{ $roleClass }}">
                        <div class="card-top"></div>
                        <div class="card-photo-wrap">
                            <img src="{{ $leader->image_url }}" alt="{{ $leader->name }}" class="card-photo">
                        </div>
                        <div class="card-body">
                            <div class="card-role">{{ $leader->designation }}</div>
                            <div class="card-name">{{ $leader->name }}</div>
                            <a href="{{ route('message.show', $leader->slug) }}" class="card-msg-btn">💬 Speech</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- History & Notice Board --}}
    <section class="history-notice-section">
        <div class="container">
            <div class="two-col">
                <div class="history-box">
                    @php 
                        $welcome = \App\Models\WelcomeSection::active()->first();
                    @endphp
                    <h3>{{ $welcome->title ?? 'প্রতিষ্ঠানের পরিচিতি' }}</h3>
                    <div class="history-img">
                        @if($welcome && $welcome->image_url)
                            <img src="{{ $welcome->image_url }}" alt="{{ $welcome->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                        @else
                            🏛️
                        @endif
                    </div>
                    <p>{!! Str::limit(strip_tags($welcome->content ?? 'প্রতিষ্ঠানের বিস্তারিত তথ্য এখানে দেখা যাবে।'), 800) !!}</p>
                    <a href="{{ route('welcome.show') }}" class="read-more-link">{{ $welcome->button_text ?? 'সম্পূর্ণ ইতিহাস পড়ুন' }} →</a>
                </div>

                <div class="notice-board">
                    <div class="nb-header">
                        <span>📋</span>
                        <h3>নোটিশ বোর্ড</h3>
                        <span class="nb-badge">NEW</span>
                    </div>
                    <div class="nb-list">
                        @foreach(\App\Models\Notice::latest()->take(6)->get() as $notice)
                            @php
                                $noticeUrl = $notice->page_id && $notice->page
                                    ? route('page.show', $notice->page->slug)
                                    : ($notice->link ?? ($notice->file_url ? $notice->file_url : '#'));
                            @endphp
                            <a href="{{ $noticeUrl }}" class="nb-item">
                                <div class="nb-date">
                                    <div class="m">{{ $notice->created_at->format('M') }}</div>
                                    <div class="d">{{ $notice->created_at->format('d') }}</div>
                                </div>
                                <div class="nb-content">
                                    <div class="nb-title">{{ Str::limit($notice->title, 60) }}</div>
                                    <div class="nb-sub">প্রকাশিত হয়েছে: {{ $notice->created_at->format('M d, Y') }}</div>
                                </div>
                                <span class="nb-new">NEW</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="nb-footer">
                        <a href="{{ route('notices.index') }}" class="nb-all-btn">📄 সকল নোটিশ দেখুন</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Achievement (Stats) Section --}}
    <section class="achievement-section">
        <div class="container">
            <div class="section-header">
                <div class="section-tag">আমাদের গর্ব</div>
                <h2 class="section-title">অর্জন ও সাফল্য</h2>
                <div class="section-divider"></div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👨‍🎓</div>
                    <div class="stat-number">{{ \App\Models\Setting::get('total_students', '৩,০০০+') }}</div>
                    <div class="stat-label">মোট শিক্ষার্থী</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">👨‍🏫</div>
                    <div class="stat-number">{{ \App\Models\Setting::get('total_teachers', '১২০+') }}</div>
                    <div class="stat-label">অভিজ্ঞ শিক্ষক</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🏆</div>
                    <div class="stat-number">{{ \App\Models\Setting::get('total_gpa5', '১২৫') }}</div>
                    <div class="stat-label">A+ প্রাপ্ত (SSC)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-number">{{ \App\Models\Setting::get('total_experience', '৪০+') }}</div>
                    <div class="stat-label">বছরের অভিজ্ঞতা</div>
                </div>
            </div>

            <div class="achieve-cards">
                <div class="achieve-card">
                    <div class="achieve-icon">🥇</div>
                    <div class="achieve-text">
                        <h4>শ্রেষ্ঠ বিদ্যালয় পুরস্কার</h4>
                        <p>জেলার সেরা মাধ্যমিক বিদ্যালয় হিসেবে স্বীকৃতি লাভ।</p>
                    </div>
                </div>
                <div class="achieve-card">
                    <div class="achieve-icon">📖</div>
                    <div class="achieve-text">
                        <h4>শতভাগ পাসের ধারা</h4>
                        <p>টানা ১০ বছর ধরে SSC ও HSC পরীক্ষায় শতভাগ পাস।</p>
                    </div>
                </div>
                <div class="achieve-card">
                    <div class="achieve-icon">🎖️</div>
                    <div class="achieve-text">
                        <h4>জাতীয় অলিম্পিয়াড</h4>
                        <p>জাতীয় বিজ্ঞান অলিম্পিয়াডে প্রথম স্থান অধিকার।</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- News & Events Section --}}
    <section class="events-section">
        <div class="container">
            <div class="section-header" style="position: relative; text-align: center;">
                <div class="section-tag">Latest</div>
                <h2 class="section-title">News & Events</h2>
                <div class="section-divider"></div>
                @if(\App\Models\NewsEvent::active()->count() > 3)
                    <a href="{{ route('news.index') }}" class="nb-all-btn" style="position: absolute; right: 0; bottom: 0;">View All →</a>
                @endif
            </div>
            <div class="events-grid">
                @foreach(\App\Models\NewsEvent::active()->latest()->take(3)->get() as $index => $news)
                    <div class="event-card">
                        <div class="event-img" style="background: linear-gradient(135deg, #{{ substr(md5($index), 0, 6) }}, #{{ substr(md5($index+1), 0, 6) }});">
                            @if($news->image)
                                <img src="{{ $news->image }}" alt="{{ $news->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                📰
                            @endif
                            <span class="event-cat">সংবাদ</span>
                        </div>
                        <div class="event-body">
                            <div class="event-date">📅 {{ $news->published_at->format('d M, Y') }}</div>
                            <div class="event-title">{{ Str::limit($news->title, 60) }}</div>
                            <div class="event-desc">{{ Str::limit(strip_tags($news->content), 100) }}</div>
                            <a href="{{ route('news.show', $news->slug) }}" class="event-link">বিস্তারিত পড়ুন →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Achievements Section --}}
    <section class="events-section" style="background: var(--t5-white); border-top: 1px solid var(--t5-border);">
        <div class="container">
            <div class="section-header" style="position: relative; text-align: center;">
                <div class="section-tag">Success</div>
                <h2 class="section-title">Achievements</h2>
                <div class="section-divider"></div>
                @if(\App\Models\Achievement::active()->count() > 3)
                    <a href="{{ route('achievements.index') }}" class="nb-all-btn" style="position: absolute; right: 0; bottom: 0;">View All →</a>
                @endif
            </div>
            <div class="events-grid">
                @foreach(\App\Models\Achievement::active()->latest()->take(3)->get() as $index => $achieve)
                    <div class="event-card">
                        <div class="event-img" style="background: linear-gradient(135deg, #{{ substr(md5($index.'ach'), 0, 6) }}, #{{ substr(md5($index+1 .'ach'), 0, 6) }});">
                            @if($achieve->image)
                                <img src="{{ $achieve->image }}" alt="{{ $achieve->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                🏆
                            @endif
                            <span class="event-cat">Achievement</span>
                        </div>
                        <div class="event-body">
                            <div class="event-date">📅 {{ $achieve->published_at->format('d M, Y') }}</div>
                            <div class="event-title">{{ Str::limit($achieve->title, 60) }}</div>
                            <div class="event-desc">{{ Str::limit(strip_tags($achieve->content), 100) }}</div>
                            <a href="{{ route('achievement.show', $achieve->slug) }}" class="event-link">বিস্তারিত পড়ুন →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Map Section --}}
    @php
        $locMap = \App\Models\LocationMap::active()->first();
        $header = \App\Models\HeaderSetting::first();
        
        // Final fallback logic for each field
        $siteName = $header?->site_name ?? 'SAVAR CANTONMENT PUBLIC SCHOOL & COLLEGE';
        $displayAddress = ($header?->address && $header->address != 'menu') ? $header->address : 'Savar Cantonment, Savar, Dhaka';
        
        $displayPhone = '';
        if($header && $header->phones) {
            $phoneArr = is_array($header->phones) ? $header->phones : json_decode($header->phones, true);
            $displayPhone = $phoneArr[0]['number'] ?? ($phoneArr[0] ?? '');
        }
        if(!$displayPhone) $displayPhone = $locMap->phone ?? '01309131476';

        $displayEmail = $header?->email ?? ($locMap->email ?? 'ideal_school2000@yahoo.com');
        
        // For the map iframespecifically
        $mapAddress = urlencode($locMap->address ?? ($header?->address ?? 'Savar, Dhaka'));
    @endphp
    @if($locMap || $header)
    <section class="map-section">
        <div class="container">
            <div class="section-header">
                <div class="section-tag">Location</div>
                <h2 class="section-title">{{ $locMap->title ?? 'Our Location' }}</h2>
                <div class="section-divider"></div>
            </div>
            
            <div class="map-wrapper">
                <div class="map-iframe-wrap">
                    @if($locMap && $locMap->embed_code)
                        {!! $locMap->embed_code !!}
                    @else
                        <iframe src="https://www.google.com/maps?q={{ $mapAddress }}&output=embed" allowfullscreen="" loading="lazy"></iframe>
                    @endif
                </div>
                <div class="map-info">
                    <h3>📍 {{ $locMap->title ?? 'Our Location' }}</h3>
                    <div class="map-detail">
                        <span class="mi">🏫</span>
                        <span><strong>{{ $siteName }}</strong><br>{{ $displayAddress }}</span>
                    </div>
                    
                    @if($displayPhone)
                        <div class="map-detail">
                            <span class="mi">📞</span>
                            <span>{{ $displayPhone }}</span>
                        </div>
                    @endif

                    @if($displayEmail)
                        <div class="map-detail">
                            <span class="mi">✉️</span>
                            <span>{{ $displayEmail }}</span>
                        </div>
                    @endif

                    <div class="map-detail">
                        <span class="mi">🕘</span>
                        <span>রবি – বৃহস্পতিবার: সকাল ৮টা – বিকাল ৫টা<br>শুক্র – শনি: বন্ধ</span>
                    </div>
                    <a href="https://www.google.com/maps?q={{ $mapAddress }}" target="_blank" class="map-get-btn">🗺️ গুগল ম্যাপে দেখুন</a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <script>
        let currentT5 = 0;
        const totalT5 = {{ $sliders->count() }};
        const trackT5 = document.getElementById('t5SliderTrack');
        const dotsT5 = document.querySelectorAll('#t5SliderDots .dot');

        function updateT5Slider() {
            if (!trackT5) return;
            trackT5.style.transform = `translateX(-${currentT5 * 100}%)`;
            dotsT5.forEach((d, i) => d.classList.toggle('active', i === currentT5));
        }

        function changeT5Slide(dir) {
            if (totalT5 <= 1) return;
            currentT5 = (currentT5 + dir + totalT5) % totalT5;
            updateT5Slider();
        }

        function goT5Slide(n) {
            currentT5 = n;
            updateT5Slider();
        }

        if (totalT5 > 1) {
            setInterval(() => changeT5Slide(1), 5000);
        }
    </script>
@endsection
