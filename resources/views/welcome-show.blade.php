@extends('layouts.app')

@section('title', $welcomeSection->title ?? 'Welcome')

@section('content')
    <style>
        .welcome-page { padding: 60px 0; background: #f4f7fb; min-height: 80vh; }
        .welcome-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #dde3ed; }
        .welcome-header { background: linear-gradient(135deg, #1a3a6e, #2056a8); padding: 40px; text-align: center; color: #fff; }
        .welcome-header h1 { font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 800; margin: 0; }
        .welcome-body { padding: 50px; }
        .welcome-img-wrap { margin-bottom: 30px; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .welcome-img { width: 100%; max-height: 500px; object-fit: cover; }
        .welcome-text { font-size: 18px; color: #4a5568; line-height: 1.9; text-align: justify; }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; background: #e8a020; color: #fff; padding: 12px 35px; border-radius: 30px; font-weight: 700; text-decoration: none; transition: all 0.3s; margin-top: 40px; }
        .back-btn:hover { background: #d48f1b; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(232,160,32,0.3); }
        
        @media (max-width: 768px) {
            .welcome-header { padding: 30px 20px; }
            .welcome-body { padding: 30px 20px; }
            .welcome-header h1 { font-size: 24px; }
        }
    </style>

    <div class="welcome-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="welcome-card">
                        <div class="welcome-header">
                            <h1>{{ $welcomeSection->title }}</h1>
                        </div>
                        <div class="welcome-body text-center">
                            @if($welcomeSection->image)
                                <div class="welcome-img-wrap">
                                    <img src="{{ $welcomeSection->image }}" alt="{{ $welcomeSection->title }}" class="welcome-img">
                                </div>
                            @endif
                            
                            <div class="welcome-text">
                                {!! $welcomeSection->content !!}
                            </div>

                            <a href="{{ url('/') }}" class="back-btn">
                                🏠 Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection