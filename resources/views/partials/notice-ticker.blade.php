@if($headerSettings->show_notice_ticker ?? true)
    @php
        $wrapContainer = $wrapContainer ?? false;
        $tickerMargin = $tickerMargin ?? 'mt-3 mb-3';
        $isSticky = $isSticky ?? false;
        $theme = \App\Models\ThemeSetting::first();
        $tickerBg = $theme->ticker_bg_color ?? '#0d2147';
        $tickerText = $theme->ticker_text_color ?? '#ffffff';
    @endphp

    <style>
        @if($isSticky)
        .fixed-ticker {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 10000;
            margin: 0 !important;
            border-radius: 0 !important;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.15) !important;
        }
        .fixed-ticker .d-flex { border-radius: 0 !important; }
        body { padding-bottom: 45px !important; } 
        @endif
        
        .ticker-container { overflow: hidden; width: 100%; position: relative; }
        .ticker-wrapper { 
            display: inline-block; 
            white-space: nowrap; 
            animation: ticker-scroll 25s linear infinite; 
            padding-left: 100%;
        }
        .ticker-wrapper:hover { animation-play-state: paused; }
        .ticker-item { display: inline-block; padding: 0 40px; }
        
        @keyframes ticker-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
    </style>

    @if($wrapContainer && !$isSticky)
    <div class="container-fluid p-0">@endif
        <div class="row g-0 {{ $isSticky ? 'fixed-ticker' : $tickerMargin }}">
            <div class="col-12 p-0">
                <div class="d-flex align-items-stretch"
                    style="box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 0; overflow: hidden; background: {{ $tickerBg }};">
                    {{-- LATEST NEWS Label --}}
                    <div class="d-md-flex align-items-center justify-content-center px-4"
                        style="background: rgba(0,0,0,0.15); color: {{ $tickerText }}; font-weight: 600; font-size: 1.05rem; white-space: nowrap; min-width: 150px; display: flex;">
                        <span class="d-none d-md-inline">📢</span> {{ $headerSettings->notice_ticker_label ?? 'LATEST NEWS' }}
                    </div>

                    {{-- Scrolling Ticker --}}
                    <div class="flex-grow-1" style="padding: 10px 0;">
                        <div class="ticker-container">
                            <div class="ticker-wrapper">
                                @php
                                    $loopNotices = \App\Models\Notice::latest()
                                        ->limit($headerSettings->notice_ticker_limit ?? 10)
                                        ->get();
                                @endphp
                                @if(isset($loopNotices) && $loopNotices->count() > 0)
                                    @foreach($loopNotices as $notice)
                                        <div class="ticker-item">
                                            <a href="{{ $notice->file_url ?? '#' }}" class="text-decoration-none"
                                                style="color: {{ $tickerText }}; font-weight: 500; font-size: 1.05rem;">
                                                🚀 {{ $notice->title }}
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="ticker-item">
                                        <span style="color: {{ $tickerText }};">Welcome to our school website.</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($wrapContainer && !$isSticky)
        </div>@endif
@endif