<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Slider;
use App\Models\Notice;
use App\Models\Message;
use App\Models\NewsEvent;
use App\Models\Achievement;
use App\Models\Offer;

class HomeController extends Controller
{
    public function index()
    {
        // Cache sliders for 1 hour
        $sliders = Cache::remember('homepage.sliders', 3600, function () {
            return Slider::where('is_active', true)
                ->orderBy('order')
                ->select('id', 'title', 'image_url', 'link', 'order')
                ->get();
        });

        // Cache notices for 30 minutes
        $notices = Cache::remember('homepage.notices', 1800, function () {
            return Notice::with('page:id,slug')
                ->orderBy('published_at', 'desc')
                ->select('id', 'title', 'file', 'link', 'page_id', 'published_at')
                ->take(5)
                ->get();
        });

        // Cache messages for 1 hour
        $messages = Cache::remember('homepage.messages', 3600, function () {
            return Message::orderBy('order')
                ->select('id', 'name', 'slug', 'designation', 'message', 'image_url', 'order')
                ->get();
        });

        // Cache news events for 30 minutes
        $newsEvents = Cache::remember('homepage.news_events', 1800, function () {
            return NewsEvent::active()
                ->orderBy('published_at', 'desc')
                ->select('id', 'title', 'slug', 'excerpt', 'image_url', 'published_at')
                ->take(3)
                ->get();
        });

        // Cache achievements for 30 minutes
        $achievements = Cache::remember('homepage.achievements', 1800, function () {
            return Achievement::active()
                ->orderBy('published_at', 'desc')
                ->select('id', 'title', 'slug', 'excerpt', 'image_url', 'published_at')
                ->take(3)
                ->get();
        });

        // Cache offers for 1 hour
        $offers = Cache::remember('homepage.offers', 3600, function () {
            return Offer::active()
                ->ordered()
                ->select('id', 'title', 'image_url', 'link')
                ->get();
        });

        return view('welcome', compact('sliders', 'notices', 'messages', 'newsEvents', 'achievements', 'offers'));
    }

    public function showWelcome()
    {
        $welcomeSection = \App\Models\WelcomeSection::where('is_active', true)->first();

        if (!$welcomeSection) {
            abort(404);
        }

        return view('welcome-show', compact('welcomeSection'));
    }
}
