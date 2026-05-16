@php
    $footer = $footerSettings ?? \App\Models\FooterSetting::first();
    $header = $headerSettings ?? \App\Models\HeaderSetting::first();
    $theme = \App\Models\ThemeSetting::first();
@endphp

<style>
/* ══════════════════════════════════════════
   11. FOOTER
   ══════════════════════════════════════════ */
.footer-t5 {
  background: {{ $theme->footer_bg_color ?? 'linear-gradient(160deg, #0a1628 0%, #0d2147 60%, #14336b 100%)' }};
  color: #8fa8cc;
  padding: 50px 0 0;
}
.footer-t5 .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1.2fr 1.2fr 1.5fr;
  gap: 30px;
  padding-bottom: 40px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
}
.footer-about .footer-logo-wrap {
  display: flex; align-items: center; gap: 14px;
  margin-bottom: 16px;
}
.footer-logo {
  width: 60px; height: 60px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.footer-logo img { width: 100%; height: auto; object-fit: contain; }
.footer-school-name { font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 700; color: #fff; }
.footer-school-bn { font-size: 13px; color: rgba(255,255,255,0.6); margin-top: 2px; }
.footer-about p { font-size: 13px; line-height: 1.7; margin-bottom: 15px; }
.footer-social { display: flex; gap: 10px; }
.footer-social a {
  width: 34px; height: 34px; border-radius: 50%;
  background: rgba(255,255,255,0.07);
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; color: #8fa8cc; text-decoration: none;
  border: 1px solid rgba(255,255,255,0.12);
  transition: all 0.2s;
}
.footer-social a:hover { background: #e8a020; color: #fff; border-color: #e8a020; }

.footer-col h4 {
  font-size: 14px; font-weight: 700; color: #fff;
  letter-spacing: 1px; text-transform: uppercase;
  margin-bottom: 18px; padding-bottom: 8px;
  border-bottom: 2px solid rgba(232,160,32,0.4);
}
.footer-links { list-style: none; padding: 0; margin: 0; }
.footer-links li { margin-bottom: 8px; }
.footer-links a {
  font-size: 13px; color: #7a93b5; text-decoration: none;
  display: flex; align-items: center; gap: 7px;
}
.footer-links a:hover { color: #fff; }
.footer-links a::before { content: '›'; font-size: 16px; color: #e8a020; }

.footer-contact-item { display: flex; gap: 10px; margin-bottom: 10px; font-size: 13px; }
.footer-contact-item .fci { font-size: 16px; flex-shrink: 0; }

.copyright-bar {
  background: {{ $theme->footer_bottom_bg_color ?? '#060f22' }};
  padding: 15px 0;
  border-top: 3px solid #e8a020;
}
.copyright-inner {
  max-width: 1200px; margin: 0 auto; padding: 0 10px;
  display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 5px;
}
.copy-left { font-size: 15px; color: #5a7a9e; }
.copy-left strong { color: #e8a020; }
.copy-right { font-size: 15px; color: #5a7a9e; font-weight: 500; }
.copy-right span, .copy-right a, .copy-right strong { color: #00e9eb; }
.copy-middle { display: flex; gap: 6px; }
.copy-pill { font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; }
.pill-red { background: #3d0a0a; color: #e74c3c; border: 1px solid #6b1111; }
.pill-gold { background: #2e1e00; color: #e8a020; border: 1px solid #5c3c00; }
.pill-blue { background: #0a1628; color: #5b9bd5; border: 1px solid #1a3a6e; }

@media (max-width: 900px) {
  .footer-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
  .footer-grid { grid-template-columns: 1fr; }
  .copyright-inner { flex-direction: column; text-align: center; }
}
</style>

<footer class="footer-t5">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-about">
                <div class="footer-logo-wrap">
                    <div class="footer-logo">
                        @php
                            $footerLogo = $footer->logo_url ?? $header->logo_url;
                        @endphp
                        @if($footerLogo)
                            <img src="{{ $footerLogo }}" alt="Logo">
                        @else
                            <span>🏫</span>
                        @endif
                    </div>
                    <div>
                        <div class="footer-school-name">{{ $header->site_name ?? 'School Name' }}</div>
                        <div class="footer-school-bn">{{ $header->site_name_bangla ?? 'প্রতিষ্ঠানের নাম' }}</div>
                    </div>
                </div>
                {{-- About text removed --}}
                <div class="footer-social">
                    @if($header && $header->facebook_url) <a href="{{ $header->facebook_url }}" target="_blank">f</a> @endif
                    @if($header && $header->youtube_url) <a href="{{ $header->youtube_url }}" target="_blank">▶</a> @endif
                    @if($header && $header->twitter_url) <a href="{{ $header->twitter_url }}" target="_blank">𝕏</a> @endif
                    @if($header && $header->instagram_url) <a href="{{ $header->instagram_url }}" target="_blank">◉</a> @endif
                </div>
            </div>

            {{-- 2. Contact Column --}}
            <div class="footer-col">
                <h4>{{ $footer->contact_title ?? 'CONTACT' }}</h4>
                @if($footer && $footer->contact_address)
                    <div class="footer-contact-item">
                        <span class="fci">📍</span>
                        <span>{{ $footer->contact_address }}</span>
                    </div>
                @else
                    <div class="footer-contact-item">
                        <span class="fci">📍</span>
                        <span>{{ $header->address ?? 'School Address' }}</span>
                    </div>
                @endif

                @if($footer && $footer->contact_phones)
                    @foreach($footer->contact_phones as $phone)
                        <div class="footer-contact-item">
                            <span class="fci">📞</span>
                            <span>{{ $phone['number'] ?? ($phone['label'] ?? '') }}</span>
                        </div>
                    @endforeach
                @elseif($header && $header->phones)
                    @foreach(array_slice($header->phones, 0, 1) as $phone)
                        <div class="footer-contact-item">
                            <span class="fci">📞</span>
                            <span>{{ $phone['number'] ?? $phone }}</span>
                        </div>
                    @endforeach
                @endif

                @php $fEmail = $footer->contact_email ?? $header->email; @endphp
                @if($fEmail)
                    <div class="footer-contact-item">
                        <span class="fci">✉️</span>
                        <span>{{ $fEmail }}</span>
                    </div>
                @endif
            </div>

            {{-- 3. Featured Links Column --}}
            <div class="footer-col">
                <h4>{{ $footer->featured_links_title ?? 'FEATURED LINKS' }}</h4>
                <ul class="footer-links">
                    @if($footer && $footer->featured_links)
                        @foreach($footer->featured_links as $link)
                            <li><a href="{{ $link['url'] ?? '#' }}">{{ $link['title'] ?? '' }}</a></li>
                        @endforeach
                    @else
                        @php
                            $quickLinks = \App\Models\Menu::where('is_active', true)->whereNull('parent_id')->take(6)->get();
                        @endphp
                        @foreach($quickLinks as $link)
                            <li><a href="{{ $link->url ?? '#' }}">{{ $link->title }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>

            {{-- 4. Facebook Page Column --}}
            <div class="footer-col">
                <h4>OUR FACEBOOK PAGE</h4>
                <div class="facebook-widget" style="background: rgba(255,255,255,0.05); border-radius: 8px; overflow: hidden; height: 180px;">
                    @if($header && $header->facebook_url)
                        <iframe src="https://www.facebook.com/plugins/page.php?href={{ urlencode($header->facebook_url) }}&tabs=timeline&width=280&height=180&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=false&appId" 
                            width="100%" height="180" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                    @else
                        <div style="padding: 20px; text-align: center; font-size: 12px; color: #7a93b5;">Facebook URL not set in admin.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="copyright-bar">
    <div class="copyright-inner">
        @php
            // Left Part Logic
            $copyText = $footer->copyright_text ?? '© {year} School Name — সর্বস্বত্ব সংরক্ষিত।';
            $currentYear = date('Y');
            $copyText = str_replace('{year}', $currentYear, $copyText);
            if (strpos($copyText, $currentYear) !== false) {
                $parts = explode($currentYear, $copyText, 2);
                $copyText = $parts[0] . $currentYear . ' <strong style="color: #00e9eb;">' . $parts[1] . '</strong>';
            }

            // Right Part Logic
            $creditText = \App\Models\Setting::get('developer_text', 'Designed & Developed by Trust Innovation Limited.');
            if (strpos($creditText, 'by ') !== false) {
                $parts = explode('by ', $creditText, 2);
                $creditText = $parts[0] . 'by <span style="color: #00e9eb;">' . $parts[1] . '</span>';
            }
        @endphp

        <div class="copy-left">
            {!! $copyText !!}
        </div>
        <div style="color: #5a7a9e; margin: 0 10px; font-size: 15px;">|</div>
        <div class="copy-right">
            {!! $creditText !!}
        </div>
    </div>
</div>
