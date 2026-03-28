{{-- Notice Ticker Partial --}}
{{-- Parameters: $wrapContainer (bool, default false) --}}
@if($headerSettings->show_notice_ticker ?? true)
    @php
        $wrapContainer = $wrapContainer ?? false;
        $tickerMargin = $tickerMargin ?? 'mt-3 mb-3';
    @endphp

    @if($wrapContainer)
    <div class="container">@endif
        <div class="row {{ $tickerMargin }}">
            <div class="col-12">
                <div class="d-flex align-items-stretch"
                    style="box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 5px; overflow: hidden;">
                    {{-- LATEST NEWS Label --}}
                    <div class="d-none d-md-flex align-items-center justify-content-center px-4"
                        style="background: color-mix(in srgb, var(--ticker-bg-color) 70%, black); color: white; font-weight: 600; font-size: 0.95rem; white-space: nowrap; min-width: 150px;">
                        <i class="fas fa-newspaper me-2"></i>
                        LATEST NEWS
                    </div>

                    {{-- Scrolling Ticker --}}
                    <div class="flex-grow-1" style="background-color: var(--ticker-bg-color); padding: 8px 15px;">
                        <div class="ticker-container">
                            <div class="ticker-wrapper">
                                @php
                                    $tickerNotices = \App\Models\Notice::where('published_at', '<=', now())
                                        ->orderBy('published_at', 'desc')
                                        ->limit($headerSettings->notice_ticker_limit ?? 10)
                                        ->get();
                                @endphp
                                @if(isset($tickerNotices) && $tickerNotices->count() > 0)
                                    @foreach($tickerNotices as $notice)
                                        <div class="ticker-item">
                                            <a href="{{ $notice->link_url }}" class="text-decoration-none"
                                                style="transition: all 0.3s ease; font-size: 0.95rem; color: var(--ticker-text-color);">
                                                <i class="fas fa-bell"
                                                    style="margin-right: 8px; font-size: 0.85rem; background: linear-gradient(135deg, #FFD700, #FFA500); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                                                <strong>{{ $notice->title }}</strong>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="ticker-item">
                                        <i class="fas fa-circle" style="font-size: 6px; vertical-align: middle; opacity: 0.7;"></i>
                                        <span style="margin-left: 8px; color: var(--ticker-text-color);">Welcome to {{ $headerSettings->site_name ?? 'our school' }} website.</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($wrapContainer)
        </div>@endif
@endif