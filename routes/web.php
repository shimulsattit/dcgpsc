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

// System Fix & Cache Clear Route
Route::get('/system-fix', function () {
    try {
        // 1. Ensure directories exist with wide permissions
        $paths = [
            storage_path('app/private/livewire-tmp'),
            storage_path('app/public/livewire-tmp'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
        ];

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0775, true);
            }
            @chmod($path, 0775);
        }

        // 2. Clear ALL caches
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');

        // 3. Run Seeder
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'MenuSeeder', '--force' => true]);
        
        $livewireDisk = 'public';
        $filesystemDisk = config('filesystems.default');
        
        $phpInfo = [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
        ];

        return "<h1>System Fix Complete</h1>
                <p>Status: <strong>All Caches Cleared & Directories Created</strong></p>
                <div style='background:#f4f4f4; padding:15px; border-radius:5px; display: flex; gap: 20px;'>
                    <div style='flex: 1;'>
                        <p><strong>Laravel Config:</strong></p>
                        <ul>
                            <li>Livewire Temp Disk: <code>$livewireDisk</code></li>
                            <li>Default Filesystem Disk: <code>$filesystemDisk</code></li>
                        </ul>
                    </div>
                    <div style='flex: 1;'>
                        <p><strong>PHP Server Limits:</strong></p>
                        <ul>
                            <li>Upload Max Filesize: <code>{$phpInfo['upload_max_filesize']}</code></li>
                            <li>Post Max Size: <code>{$phpInfo['post_max_size']}</code></li>
                            <li>Max Execution Time: <code>{$phpInfo['max_execution_time']}s</code></li>
                            <li>Memory Limit: <code>{$phpInfo['memory_limit']}</code></li>
                        </ul>
                    </div>
                </div>
                <p>আপনি এখন আপনার সাইট ব্যবহার করে দেখতে পারেন। যদি সমস্যা না মেটে, তবে আপনার ব্রাউজারের ক্যাশ ক্লিয়ার করে আবার চেষ্টা করুন।</p>
                <a href='/admin/photo-galleries/create' style='padding:10px; background:#4f46e5; color:white; text-decoration:none; border-radius:5px; display:inline-block; margin-top:10px;'>ফটো গ্যালারি ক্রিয়েট পেজে যান</a>";
    } catch (\Exception $e) {
        return "<h1>Error</h1><pre>" . $e->getMessage() . "</pre>";
    }
});

// Alias for old route name just in case
Route::get('/fix-menu-data', function() { return redirect('/system-fix'); });

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
