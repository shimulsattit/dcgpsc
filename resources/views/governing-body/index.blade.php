@extends('layouts.app')

@section('title', 'Governing Body - ' . ($headerSettings->site_name ?? 'School Name'))

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-uppercase d-inline-block position-relative pb-2" style="color: #2c3e50;">
                Governing Body
                <div class="position-absolute start-0 bottom-0 w-100" style="height: 3px; background-color: #f1c40f;"></div>
            </h2>
        </div>

        @if($chiefPatron)
            <div class="row justify-content-center mb-5">
                <div class="col-md-5 col-lg-4">
                    <div class="card h-100 border shadow-premium transition-hover overflow-hidden"
                        style="border: 1px solid var(--primary-color) !important;">
                        <div class="card-body text-center p-4">
                            <div class="mb-4 d-inline-block position-relative">
                                <div class="rounded-3 overflow-hidden shadow-lg"
                                    style="width: 180px; height: 190px; border: 5px solid #fff;">
                                    <img src="{{ $chiefPatron->photo_url ?? 'https://via.placeholder.com/180x190?text=No+Photo' }}"
                                        alt="{{ $chiefPatron->name }}" class="w-100 h-100 object-fit-cover object-position-top"
                                        referrerpolicy="no-referrer">
                                </div>
                            </div>
                            <h4 class="fw-bold mb-1"
                                style="color: {{ $chiefPatron->role?->name_color ?? '#2c3e50' }}; font-size: {{ $chiefPatron->role?->name_font_size ?? '24px' }};">
                                {{ $chiefPatron->name }}</h4>
                            <p class="fw-semibold mb-2"
                                style="color: {{ $chiefPatron->role?->designation_color ?? '#3498db' }}; font-size: {{ $chiefPatron->role?->designation_font_size ?? '16px' }};">
                                {{ $chiefPatron->designation }}</p>
                            <p class="text-muted small mb-3 px-2">{{ $chiefPatron->description }}</p>
                            <div class="d-inline-block px-4 py-2 rounded-pill small fw-bold text-uppercase letter-spacing-1 shadow-sm"
                                style="background-color: {{ $chiefPatron->role?->badge_bg_color ?? '#27ae60' }}; color: {{ $chiefPatron->role?->badge_text_color ?? '#ffffff' }}; font-size: 0.75rem;">
                                {{ $chiefPatron->role?->name ?? 'Member' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4 justify-content-center">
            @foreach($otherMembers as $member)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border shadow-premium transition-hover overflow-hidden"
                        style="border: 1px solid var(--primary-color) !important;">
                        <div class="card-body text-center p-4">
                            <div class="mb-4 d-inline-block">
                                <div class="rounded-3 overflow-hidden shadow"
                                    style="width: 150px; height: 160px; border: 4px solid #fff;">
                                    <img src="{{ $member->photo_url ?? 'https://via.placeholder.com/150x160?text=No+Photo' }}"
                                        alt="{{ $member->name }}" class="w-100 h-100 object-fit-cover object-position-top"
                                        referrerpolicy="no-referrer">
                                </div>
                            </div>
                            <h6 class="fw-bold mb-1"
                                style="color: {{ $member->role?->name_color ?? '#2c3e50' }}; font-size: {{ $member->role?->name_font_size ?? '18px' }};">
                                {{ $member->name }}</h6>
                            <p class="mb-1"
                                style="color: {{ $member->role?->designation_color ?? '#3498db' }}; font-size: {{ $member->role?->designation_font_size ?? '14px' }};">
                                {{ $member->designation }}</p>
                            <p class="text-muted extra-small mb-3 px-2">{{ $member->description }}</p>
                            <div class="d-inline-block px-3 py-1 rounded-pill small fw-bold text-uppercase shadow-xs"
                                style="font-size: 0.65rem; background-color: {{ $member->role?->badge_bg_color ?? '#27ae60' }}; color: {{ $member->role?->badge_text_color ?? '#ffffff' }}; letter-spacing: 0.5px;">
                                {{ $member->role?->name ?? 'Member' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .shadow-premium {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        }

        .transition-hover {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .transition-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .object-fit-contain {
            object-fit: contain;
        }

        .object-position-top {
            object-position: top;
        }

        .extra-small {
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .letter-spacing-1 {
            letter-spacing: 1px;
        }

        .shadow-xs {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

    </style>
@endsection
