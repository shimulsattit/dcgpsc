<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NewsEventController;
use App\Http\Controllers\AchievementController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// News and Events routes
Route::get('/news', [NewsEventController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsEventController::class, 'show'])->name('news.show');

Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
Route::get('/achievement/{slug}', [AchievementController::class, 'show'])->name('achievement.show');

// Message route
Route::get('/message/{slug}', [App\Http\Controllers\MessageController::class, 'show'])->name('message.show');

// Welcome section full view
Route::get('/welcome', [HomeController::class, 'showWelcome'])->name('welcome.show');

// Notices route
Route::get('/notices', [App\Http\Controllers\NoticeController::class, 'index'])->name('notices.index');
Route::get('/gallery', [App\Http\Controllers\PhotoGalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [App\Http\Controllers\PhotoGalleryController::class, 'show'])->name('gallery.show');

// Video Gallery routes
Route::get('/video-gallery', [App\Http\Controllers\VideoGalleryController::class, 'index'])->name('video-gallery.index');
Route::get('/video-gallery/{slug}', [App\Http\Controllers\VideoGalleryController::class, 'show'])->name('video-gallery.show');

// Preview route for admin (must be before dynamic page route)
Route::get('/page/preview/{page}', [PageController::class, 'preview'])->name('page.preview');

// Filament Admin Panel
// Access at: http://localhost/barishal/public/admin
// All authentication handled by Filament

// All authentication handled by Filament

// Fix Menu Route (Temporary) - Added to help user sync menu
Route::get('/fix-menu-data', function () {
    try {
        // 1. Clear ALL caches
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');

        // 2. Run Seeder
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'MenuSeeder', '--force' => true]);
        $seederOutput = \Illuminate\Support\Facades\Artisan::output();

        // 3. Check file modification times
        $headerFile = resource_path('views/partials/header-template1.blade.php');
        $fileExists = file_exists($headerFile);
        $fileTime = $fileExists ? date('Y-m-d H:i:s', filemtime($headerFile)) : 'NOT FOUND';
        $fileSize = $fileExists ? filesize($headerFile) : 0;

        // 4. Check if file has the fix
        $fileContent = $fileExists ? file_get_contents($headerFile) : '';
        $hasShowClass = str_contains($fileContent, 'collapse navbar-collapse show');
        $hasPurpleBanner = str_contains($fileContent, 'FILE CHECK');

        // 5. Debug Data
        $menuCount = \App\Models\Menu::count();
        $rootMenus = \App\Models\Menu::where(function ($q) {
            $q->whereNull('parent_id')->orWhere('parent_id', 0)->orWhere('parent_id', '');
        })->where('is_active', true)->get();

        $debugInfo = "=== DATABASE ===" . "\n";
        $debugInfo .= "Total Menus: " . $menuCount . "\n";
        $debugInfo .= "Root Menus: " . $rootMenus->count() . "\n";
        $debugInfo .= "Sample: " . $rootMenus->pluck('title')->take(3)->implode(', ') . "\n\n";

        $debugInfo .= "=== FILE STATUS ===" . "\n";
        $debugInfo .= "File Exists: " . ($fileExists ? 'YES' : 'NO') . "\n";
        $debugInfo .= "Last Modified: " . $fileTime . "\n";
        $debugInfo .= "File Size: " . $fileSize . " bytes\n";
        $debugInfo .= "Has 'show' class fix: " . ($hasShowClass ? 'YES ✓' : 'NO ✗') . "\n";
        $debugInfo .= "Has Purple Banner: " . ($hasPurpleBanner ? 'YES ✓' : 'NO ✗') . "\n";

        return "<h1>Complete System Check</h1>
                <p>Status: <strong>All Caches Cleared & Seeder Ran</strong></p>
                <h3>Diagnostic Info:</h3>
                <pre style='background:#eee;padding:15px; border-left: 4px solid #333;'>$debugInfo</pre>
                <h3>Next Steps:</h3>
                <ol style='line-height: 2;'>
                    <li>If 'Has show class fix' = NO, run: <code>git pull origin main</code></li>
                    <li>After git pull, refresh this page again</li>
                    <li>Then go to <a href='/'>Home Page</a> to check menu</li>
                </ol>
                <h3>Console Output:</h3>
                <pre style='background:#ddd;padding:10px; font-size: 12px;'>$seederOutput</pre>";
    } catch (\Exception $e) {
        return "<h1>Error</h1><pre>" . $e->getMessage() . "</pre>";
    }
});

// Governing Body route
Route::get('/governing-body', [App\Http\Controllers\GoverningBodyController::class, 'index'])->name('governing-body.index');

// Online Course & Book Shop
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [App\Http\Controllers\ShopController::class, 'index'])->name('index');
    Route::get('/product/{product}', [App\Http\Controllers\ShopController::class, 'show'])->name('show');
    Route::get('/checkout/{product}', [App\Http\Controllers\ShopController::class, 'checkout'])->name('checkout');
    Route::post('/order/{product}', [App\Http\Controllers\ShopController::class, 'storeOrder'])->name('order.store');
    Route::get('/success/{order}', [App\Http\Controllers\ShopController::class, 'success'])->name('success');
    Route::get('/payment/automated/{order}', function ($order) {
        return "Automated payment gateway integration coming soon (SSLCommerz/Shurjopay). Order ID: " . $order;
    })->name('payment.automated');
});

// Dynamic page route (must be last to avoid conflicts)
Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9-]+')->name('page.show');
