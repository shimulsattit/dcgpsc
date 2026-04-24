@if(isset($footerSettings) && $footerSettings)
    <footer class="footer text-white pt-4 pb-4">
        <div class="container">
            <div class="row g-4">
                {{-- Logo & School Info --}}
                <div class="col-lg-3">
                    @if($footerSettings->logo)
                        <img src="{{ $footerSettings->logo }}" alt="{{ $footerSettings->school_name }}" class="mb-3"
                            style="max-height: 80px;"
                            referrerpolicy="no-referrer">
                    @endif
                    <h6 class="fw-bold">{{ $footerSettings->school_name }}</h6>

                    {{-- Social Media Icons --}}
                    <div class="mt-3">
                        @if($footerSettings->facebook_url)
                            <a href="{{ $footerSettings->facebook_url }}" target="_blank"
                                class="btn btn-outline-light btn-sm rounded-circle me-2"
                                style="width: 40px; height: 40px; padding: 8px;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        @if($footerSettings->twitter_url)
                            <a href="{{ $footerSettings->twitter_url }}" target="_blank"
                                class="btn btn-outline-light btn-sm rounded-circle"
                                style="width: 40px; height: 40px; padding: 8px;">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="col-lg-3">
                    <h5 class="fw-bold mb-3">{{ $footerSettings->contact_title }}</h5>
                    @if($footerSettings->contact_address)
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $footerSettings->contact_address }}
                        </p>
                    @endif
                    @if($footerSettings->contact_phones && is_array($footerSettings->contact_phones))
                        @foreach($footerSettings->contact_phones as $phone)
                            <p class="mb-2">
                                <i class="fas fa-phone me-2"></i>
                                <strong>{{ $phone['label'] ?? '' }}:</strong> {{ $phone['number'] ?? '' }}
                            </p>
                        @endforeach
                    @endif
                    @if($footerSettings->contact_email)
                        <p class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            {{ $footerSettings->contact_email }}
                        </p>
                    @endif
                </div>

                {{-- Featured Links --}}
                <div class="col-lg-3">
                    <h5 class="fw-bold mb-3">{{ $footerSettings->featured_links_title }}</h5>
                    @if($footerSettings->featured_links && is_array($footerSettings->featured_links))
                        <ul class="list-unstyled">
                            @foreach($footerSettings->featured_links as $link)
                                <li class="mb-2">
                                    <a href="{{ $link['url'] ?? '#' }}"
                                        class="text-white text-decoration-none d-flex align-items-center" target="_blank">
                                        <i class="fas fa-link me-2"></i>
                                        <span>{{ $link['title'] ?? '' }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Facebook Page --}}
                <div class="col-lg-3">
                    <h5 class="fw-bold mb-3">{{ $footerSettings->facebook_title }}</h5>
                    @if($footerSettings->facebook_embed_url)
                        @php
                            // Extract Facebook page username/ID from URL
                            // Example: https://www.facebook.com/YourPageName or https://facebook.com/profile.php?id=123456
                            $facebookUrl = $footerSettings->facebook_embed_url;

                            // Clean up the URL - remove embed parameters if any
                            $facebookUrl = preg_replace('/\/plugins\/page\.php.*/', '', $facebookUrl);
                            $facebookUrl = rtrim($facebookUrl, '/');
                        @endphp

                        {{-- Facebook Page Plugin --}}
                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous"
                            src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0" nonce="random123"></script>

                        <div class="fb-page" data-href="{{ $facebookUrl }}" data-tabs="" data-width="340" data-height="250"
                            data-small-header="false" data-adapt-container-width="true" data-hide-cover="false"
                            data-show-facepile="true">
                            <blockquote cite="{{ $facebookUrl }}" class="fb-xfbml-parse-ignore">
                                <a href="{{ $facebookUrl }}" target="_blank" class="text-white">Visit our Facebook Page</a>
                            </blockquote>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    {{-- Copyright Bar --}}
    <div class="footer-bottom text-white py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="mb-0 small">{{ str_replace('{year}', date('Y'), $footerSettings->copyright_text) }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    @if($footerSettings->privacy_policy_url)
                        <a href="{{ $footerSettings->privacy_policy_url }}" class="text-white text-decoration-none small">
                            Privacy & Policy
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif