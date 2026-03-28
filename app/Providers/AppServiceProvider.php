<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDriveService;
use Masbug\Flysystem\GoogleDriveAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Register cache observer for automatic cache clearing
        $observer = new \App\Observers\CacheObserver();
        \App\Models\Slider::observe($observer);
        \App\Models\Notice::observe($observer);
        \App\Models\Message::observe($observer);
        \App\Models\NewsEvent::observe($observer);
        \App\Models\Achievement::observe($observer);
        // Additional observers for global cache
        \App\Models\WelcomeSection::observe($observer);
        \App\Models\MenuCard::observe($observer);
        \App\Models\Menu::observe($observer);
        \App\Models\SidebarWidget::observe($observer);
        \App\Models\ImportantLink::observe($observer);
        \App\Models\LocationMap::observe($observer);
        \App\Models\Setting::observe($observer);
        \App\Models\ThemeSetting::observe($observer);
        \App\Models\HeaderSetting::observe($observer);
        \App\Models\FooterSetting::observe($observer);

        try {
            // Check if table exists to avoid errors during migration
            if (Schema::hasTable('settings')) {
                $settings = \Illuminate\Support\Facades\Cache::remember('global.settings', 3600, function () {
                    return \App\Models\Setting::all()->pluck('value', 'key');
                });
                View::share('settings', $settings);
            }

            // Fetch and share notices
            if (Schema::hasTable('notices')) {
                $notices = \Illuminate\Support\Facades\Cache::remember('global.notices', 1800, function () {
                    return \App\Models\Notice::whereNotNull('published_at')
                        ->where('published_at', '<=', now())
                        ->orderBy('published_at', 'desc')
                        ->take(10)
                        ->get();
                });
                View::share('notices', $notices);
            }

            // Fetch and share welcome section
            if (Schema::hasTable('welcome_sections')) {
                $welcomeSection = \Illuminate\Support\Facades\Cache::remember('global.welcome_section', 3600, function () {
                    return \App\Models\WelcomeSection::active()->first();
                });
                View::share('welcomeSection', $welcomeSection);
            }

            // Fetch and share menu cards
            if (Schema::hasTable('menu_cards')) {
                $menuCards = \Illuminate\Support\Facades\Cache::remember('global.menu_cards', 3600, function () {
                    return \App\Models\MenuCard::active()->ordered()->with('items')->get();
                });
                View::share('menuCards', $menuCards);
            }

            if (Schema::hasTable('menus')) {
                $menus = \Illuminate\Support\Facades\Cache::remember('global.menus', 3600, function () {
                    return \App\Models\Menu::whereNull('parent_id')
                        ->where('is_active', true)
                        ->with('children')
                        ->orderBy('order')
                        ->get();
                });
                View::share('menus', $menus);
            }

            if (Schema::hasTable('sliders')) {
                $sliders = \Illuminate\Support\Facades\Cache::remember('global.sliders', 3600, function () {
                    return \App\Models\Slider::active()
                        ->ordered()
                        ->get();
                });
                View::share('sliders', $sliders);
            }

            if (Schema::hasTable('messages')) {
                $messages = \Illuminate\Support\Facades\Cache::remember('global.messages', 3600, function () {
                    return \App\Models\Message::active()->ordered()->get();
                });
                View::share('messages', $messages);
                // Share specific roles for easier access (derived from cached collection)
                View::share('founder', $messages->where('type', 'Founder')->first());
                View::share('chiefPatron', $messages->where('type', 'Chief Patron')->first());
                View::share('chairman', $messages->where('type', 'Chairman')->first());
                View::share('principal', $messages->where('type', 'Principal')->first());
                View::share('vicePrincipal', $messages->where('type', 'Vice Principal')->first());
                View::share('headmaster', $messages->where('type', 'Headmaster')->first());


            }

            if (Schema::hasTable('sidebar_widgets')) {
                $sidebarWidgets = \Illuminate\Support\Facades\Cache::remember('global.sidebar_widgets', 3600, function () {
                    return \App\Models\SidebarWidget::active()->ordered()->get();
                });
                View::share('sidebarWidgets', $sidebarWidgets);
            }

            // Fetch and share important links
            if (Schema::hasTable('important_links')) {
                $importantLinks = \Illuminate\Support\Facades\Cache::remember('global.important_links', 3600, function () {
                    return \App\Models\ImportantLink::active()->ordered()->get();
                });
                View::share('importantLinks', $importantLinks);
            }

            // Fetch and share location map
            if (Schema::hasTable('location_maps')) {
                $locationMap = \Illuminate\Support\Facades\Cache::remember('global.location_map', 3600, function () {
                    return \App\Models\LocationMap::active()->first();
                });
                View::share('locationMap', $locationMap);
            }

            // Fetch and share footer settings
            if (Schema::hasTable('footer_settings')) {
                $footerSettings = \Illuminate\Support\Facades\Cache::remember('global.footer_settings', 3600, function () {
                    return \App\Models\FooterSetting::first();
                });
                View::share('footerSettings', $footerSettings);
            }

            // Fetch and share header settings
            if (Schema::hasTable('header_settings')) {
                $headerSettings = \Illuminate\Support\Facades\Cache::remember('global.header_settings', 3600, function () {
                    return \App\Models\HeaderSetting::first();
                });
                View::share('headerSettings', $headerSettings);
            }

            // Fetch and share theme settings (including homepage template)
            if (Schema::hasTable('theme_settings')) {
                $themeSettings = \Illuminate\Support\Facades\Cache::remember('global.theme_settings', 3600, function () {
                    return \App\Models\ThemeSetting::first();
                });

                if ($themeSettings) {
                    // LIVE PREVIEW LOGIC: Override homepage_template if query param exists
                    if (request()->has('theme_preview')) {
                        $previewKey = request('theme_preview');
                        // Optional: Verify key exists in DB to prevent arbitrary strings
                        // $isValid = \App\Models\ThemeShowcase::where('theme_key', $previewKey)->exists();
                        // if ($isValid) ...
                        $themeSettings->homepage_template = $previewKey;
                    }

                    View::share('themeSettings', $themeSettings);
                    View::share('theme', $themeSettings); // Alias for easier access
                    // Merge theme settings into existing settings
                    $existingSettings = View::shared('settings') ?? collect();
                    if ($existingSettings instanceof \Illuminate\Support\Collection) {
                        $existingSettings = $existingSettings->toArray();
                    }
                    View::share('settings', array_merge(
                        $existingSettings,
                        [
                            'homepage_template' => $themeSettings->homepage_template ?? 'template_1',
                            'message_card_design' => $themeSettings->message_card_design ?? 'solid',
                            'message_card_bg_image' => $themeSettings->message_card_bg_image ?? null,
                            'message_card_bg_image_gdrive' => $themeSettings->message_card_bg_image_gdrive ?? null,
                            'card_header_color' => $themeSettings->card_header_color ?? '#134B50',
                            'card_name_text_color' => $themeSettings->card_name_text_color ?? '#e8f5e9',
                            'card_designation_text_color' => $themeSettings->card_designation_text_color ?? '#ffffff',
                            'card_image_border_color' => $themeSettings->card_image_border_color ?? '#ffffff',
                            'card_button_bg_color' => $themeSettings->card_button_bg_color ?? '#134B50',
                            'card_button_text_color' => $themeSettings->card_button_text_color ?? '#ffffff',
                        ]
                    ));
                }
            }

        } catch (\Exception $e) {
            // Silently fail if tables don't exist yet
        }

        // Register custom Google Drive filesystem disk (only if dependencies are installed)
        if (class_exists(GoogleClient::class) && class_exists(GoogleDriveAdapter::class)) {
            Storage::extend('gdrive', function ($app, $config) {
                $client = new GoogleClient();

                if (! empty($config['jsonKeyFile'])) {
                    // JSON Path method (Service Account key file)
                    $client->setAuthConfig($config['jsonKeyFile']);
                    $client->setScopes([\Google\Service\Drive::DRIVE]);
                } else {
                    // Fallback: OAuth client + refresh token method
                    $client->setClientId($config['clientId'] ?? null);
                    $client->setClientSecret($config['clientSecret'] ?? null);
                    $client->refreshToken($config['refreshToken'] ?? null);
                }

                $client->setApplicationName(config('app.name', 'Laravel Google Drive'));

                $service = new GoogleDriveService($client);

                $folderId = $config['folderId'] ?? null;

                $adapter = new GoogleDriveAdapter($service, $folderId);

                return new \League\Flysystem\Filesystem($adapter);
            });
        }
    }
}
