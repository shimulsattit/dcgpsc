@php
    $header = $headerSettings ?? \App\Models\HeaderSetting::first();
    $settings = $settings ?? [];
@endphp

<style>
/* ══════════════════════════════════════════
   1. TOP HEADER
   ══════════════════════════════════════════ */
.top-header {
  background: linear-gradient(90deg, #0d2147 0%, #1a3a6e 60%, #2056a8 100%);
  color: #cdd8f0;
  font-size: 13px;
  padding: 7px 0;
  border-bottom: 2px solid #e8a020;
}
.top-header .container {
  max-width: 1200px; margin: 0 auto; padding: 0 20px;
  display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px;
}
.top-contact { display: flex; gap: 20px; }
.top-contact a {
  display: flex; align-items: center; gap: 6px;
  color: #cdd8f0; transition: color 0.2s; text-decoration: none;
}
.top-contact a:hover { color: #e8a020; }
.top-contact .icon { font-size: 14px; }
.top-social { display: flex; gap: 10px; }
.top-social a {
  width: 26px; height: 26px; border-radius: 50%;
  background: rgba(255,255,255,0.1);
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; color: #cdd8f0; text-decoration: none;
  transition: background 0.2s, color 0.2s;
}
.top-social a:hover { background: #e8a020; color: #fff; }

/* ══════════════════════════════════════════
   2. MAIN HEADER
   ══════════════════════════════════════════ */
.main-header-t5 {
  background: #fff;
  padding: 18px 0;
  box-shadow: 0 2px 12px rgba(26,58,110,0.08);
  position: relative; z-index: 999;
}
.main-header-t5 .container {
  max-width: 1200px; margin: 0 auto; padding: 0 20px;
  display: flex; align-items: center; gap: 20px;
}
.school-logo-wrap {
  width: 80px; height: 80px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.school-logo-wrap img { width: 100%; height: 100%; object-fit: cover; }
.school-logo-wrap span { font-size: 32px; }

.school-info { flex: 1; }
.school-name-en {
  font-family: 'Playfair Display', serif;
  font-size: 26px;
  font-weight: 800;
  color: #1a3a6e;
  text-transform: uppercase;
  letter-spacing: 1px;
  line-height: 1.1;
}
.school-name-bn {
  font-size: 21px;
  font-weight: 700;
  color: #c0392b;
  margin-top: 2px;
}
.school-address {
  font-size: 14px;
  font-weight: 600;
  color: #000;
  margin-top: 4px;
}
.header-right { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
.action-buttons-wrap { display: flex; flex-direction: column; gap: 10px; align-items: flex-end; }
.admit-btn {
  background: linear-gradient(90deg, #c0392b, #e74c3c);
  color: #fff;
  padding: 10px 24px;
  border-radius: 30px;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.5px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  text-decoration: none;
  box-shadow: 0 4px 15px rgba(192, 57, 43, 0.3);
  border: 1px solid rgba(255,255,255,0.1);
}
.admit-btn:hover { 
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(192, 57, 43, 0.4);
    color: #fff; 
}

/* ══════════════════════════════════════════
   3. NAVBAR
   ══════════════════════════════════════════ */
.navbar-t5 {
  background: linear-gradient(90deg, #0d2147, #1a3a6e, #2056a8);
  position: -webkit-sticky !important;
  position: sticky !important; 
  top: 0 !important; 
  z-index: 99999 !important;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  width: 100%;
}
/* Ensure no parent blocks sticky behavior */
header, main, .wrapper, body, html {
    overflow: visible !important;
}
.navbar-t5 .container {
  max-width: 1200px; margin: 0 auto; padding: 0 20px;
  display: flex; align-items: center;
}
.nav-list-t5 { display: flex; list-style: none; flex-wrap: wrap; margin: 0; padding: 0; }
.nav-list-t5 li { position: relative; }
.nav-list-t5 li a {
  display: block;
  color: #fff;
  font-size: 14px;
  font-weight: 500;
  padding: 14px 11px;
  letter-spacing: 0.2px;
  transition: all 0.3s ease;
  text-decoration: none;
  text-transform: uppercase;
  position: relative;
  white-space: nowrap;
}
.nav-list-t5 li a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 3px;
    background: #e8a020;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateX(-50%);
}

.nav-list-t5 li a:hover,
.nav-list-t5 li a.active {
  color: #e8a020;
}
.nav-list-t5 li a:hover::after,
.nav-list-t5 li a.active::after {
    width: 100%;
}

/* Dropdown */
.nav-list-t5 li:hover .dropdown-t5 { display: block; }
.dropdown-t5 {
  display: none;
  position: absolute; top: 100%; left: 0;
  background: #1a3a6e;
  min-width: 200px;
  border-top: 3px solid #e8a020;
  box-shadow: 0 8px 40px rgba(26,58,110,0.16);
  z-index: 999;
  list-style: none;
  padding: 0;
}
.dropdown-t5 li a {
  padding: 11px 18px;
  font-size: 13px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  text-transform: none;
}

@media (max-width: 900px) {
  .top-header .container { justify-content: center; }
  .main-header-t5 .container { flex-direction: column; text-align: center; gap: 15px; }
  .header-right { align-items: center; }
  .action-buttons-wrap { align-items: center; }
  .nav-list-t5 { justify-content: center; }
  .school-name-en { font-size: 20px; }
}
</style>

<!-- ══════════════════════════════════════════
     1. TOP HEADER
     ══════════════════════════════════════════ -->
<div class="top-header">
    <div class="container">
        <div class="top-contact">
            @if($header && $header->phones)
                @foreach(array_slice($header->phones, 0, 2) as $phone)
                    <a href="tel:{{ $phone['number'] ?? $phone }}"><span class="icon">📞</span> {{ $phone['number'] ?? $phone }}</a>
                @endforeach
            @endif
            @if($header && $header->email)
                <a href="mailto:{{ $header->email }}"><span class="icon">✉️</span> {{ $header->email }}</a>
            @endif
        </div>
        <div class="top-social">
            @if($header && $header->facebook_url) <a href="{{ $header->facebook_url }}" target="_blank">f</a> @endif
            @if($header && $header->youtube_url) <a href="{{ $header->youtube_url }}" target="_blank">▶</a> @endif
            @if($header && $header->twitter_url) <a href="{{ $header->twitter_url }}" target="_blank">𝕏</a> @endif
            @if($header && $header->instagram_url) <a href="{{ $header->instagram_url }}" target="_blank">◉</a> @endif
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════
     2. MAIN HEADER
     ══════════════════════════════════════════ -->
<header class="main-header-t5">
    <div class="container">
        <div class="school-logo-wrap">
            @if($header && $header->logo)
                <img src="{{ $header->logo }}" alt="Logo">
            @else
                <span>🏫</span>
            @endif
        </div>
        <div class="school-info">
            <div class="school-name-en">{{ $header->site_name ?? 'School Name' }}</div>
            <div class="school-name-bn">{{ $header->site_name_bangla ?? 'প্রতিষ্ঠানের নাম' }}</div>
            <div class="school-address">📍 {{ $header->address ?? ($header->eiin ?? 'School Address') }}</div>
        </div>
        <div class="header-right">
            <div class="action-buttons-wrap">
                @if($header && !empty($header->action_buttons))
                    @foreach($header->action_buttons as $btn)
                        @php
                            $btnBg = $btn['bg_color'] ?? 'linear-gradient(90deg, #c0392b, #e74c3c)';
                            $btnText = $btn['text_color'] ?? '#fff';
                        @endphp
                        <a href="{{ $btn['url'] ?? '#' }}" 
                           class="admit-btn" 
                           style="background: {{ $btnBg }}; color: {{ $btnText }}; border-color: {{ $btnBg }};">
                           {{ $btn['label'] ?? 'Action' }}
                        </a>
                    @endforeach
                @else
                    <a href="#" class="admit-btn">🎓 ভর্তি আবেদন</a>
                @endif
            </div>
        </div>
    </div>
</header>

<!-- ══════════════════════════════════════════
     3. NAVBAR
     ══════════════════════════════════════════ -->
<nav class="navbar-t5">
    <div class="container">
        <ul class="nav-list-t5">
            @php
                $menus = \App\Models\Menu::where(function ($q) {
                    $q->whereNull('parent_id')->orWhere('parent_id', 0)->orWhere('parent_id', '');
                })->where('is_active', true)->with('children')->orderBy('order')->get();
            @endphp
            @foreach($menus as $menu)
                <li>
                    <a href="{{ $menu->url ?? '#' }}" class="{{ request()->is(trim($menu->url, '/')) ? 'active' : '' }}">
                        {{ $menu->title }} @if($menu->children->count() > 0) ▾ @endif
                    </a>
                    @if($menu->children->count() > 0)
                        <ul class="dropdown-t5">
                            @foreach($menu->children as $child)
                                <li><a href="{{ $child->url ?? '#' }}">{{ $child->title }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</nav>
