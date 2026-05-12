<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Menu;
use App\Models\HeaderSetting;
use App\Models\FooterSetting;
use App\Models\ThemeSetting;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Notice;
use App\Models\Message;
use App\Models\WelcomeSection;

class SchoolInit extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'app:school-init {--name= : The name of the school} {--email= : The default contact email}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Cleanup all demo data and initialize professional defaults with optional school name and email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->alert('SCHOOL PROJECT INITIALIZATION');
        
        if (!$this->confirm('This action will PERMANENTLY DELETE all current content. Proceed?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->cleanDatabase();
        $this->seedSettings();
        $this->seedMenus();
        $this->seedDemoContent();
        $this->clearSystemCaches();

        $this->newLine();
        $this->success('SUCCESS: Project has been initialized for a fresh school setup!');
    }

    /**
     * Truncate all content-related tables.
     */
    protected function cleanDatabase()
    {
        $this->info('Step 1: Cleaning database tables...');
        
        Schema::disableForeignKeyConstraints();

        $tables = [
            'notices', 'sliders', 'offers', 'achievements', 'news_events', 
            'photo_galleries', 'video_galleries', 'messages', 'popups', 
            'important_links', 'welcome_sections', 'governing_body_members', 
            'sidebar_widgets', 'menu_cards', 'menu_card_items', 'products', 'orders',
            'pages', 'menus', 'header_settings', 'footer_settings', 'theme_settings', 'settings'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->comment(" - Table [{$table}] truncated.");
            }
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Seed default system and site settings.
     */
    protected function seedSettings()
    {
        $this->info('Step 2: Initializing site settings...');

        $schoolName = $this->option('name') ?: config('app.name', 'New School Name');
        $email = $this->option('email') ?: 'info@school.edu.bd';

        // Header Configuration
        HeaderSetting::create([
            'site_name' => $schoolName,
            'site_name_bangla' => $this->option('name') ? 'নতুন স্কুলের নাম' : 'নতুন স্কুলের নাম', // User can change bangla name in admin
            'email' => $email,
            'show_top_bar' => true,
            'show_notice_ticker' => true,
            'notice_ticker_label' => 'LATEST NEWS',
            'notice_ticker_limit' => 5,
            'ticker_position' => 'below_slider',
            'is_shop_enabled' => false,
        ]);

        // Footer Configuration
        FooterSetting::create([
            'school_name' => $schoolName,
            'copyright_text' => "Copyright © {year} {$schoolName}",
        ]);

        // Visual Theme Configuration
        ThemeSetting::create([
            'primary_color' => '#006a4e',
            'secondary_color' => '#f42a41',
            'homepage_template' => 'template_1',
        ]);
    }

    /**
     * Seed default navigation menus (Inactive by default).
     */
    protected function seedMenus()
    {
        $this->info('Step 3: Seeding default navigation menus...');

        $menus = [
            ['title' => 'Home', 'url' => '/', 'order' => 1],
            ['title' => 'About', 'url' => '#', 'order' => 2],
            ['title' => 'Academic', 'url' => '#', 'order' => 3],
            ['title' => 'Notice', 'url' => '/notices', 'order' => 4],
            ['title' => 'Admission', 'url' => '#', 'order' => 5],
            ['title' => 'Gallery', 'url' => '#', 'order' => 6],
            ['title' => 'Contact', 'url' => '#', 'order' => 7],
            ['title' => 'Login', 'url' => '/admin', 'order' => 8],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu + ['is_active' => false]);
        }
    }

    /**
     * Seed basic demo content for initial visual structure.
     */
    protected function seedDemoContent()
    {
        $this->info('Step 4: Seeding placeholder demo content...');

        $schoolName = $this->option('name') ?: config('app.name', 'Our School');

        Slider::create([
            'title' => "Welcome to {$schoolName}",
            'image_url' => 'https://placehold.co/1920x800?text=Default+Slider',
            'order' => 1,
        ]);

        Notice::create([
            'title' => 'Sample Notice: Academic Session Starting Soon',
            'published_at' => now(),
            'is_active' => true,
        ]);

        Message::create([
            'name' => 'Principal Name',
            'designation' => 'Principal',
            'message' => "Welcome to {$schoolName}. We are dedicated to providing quality education for every student.",
            'image_url' => 'https://placehold.co/400x500?text=Principal',
            'order' => 1,
        ]);

        WelcomeSection::create([
            'title' => "Welcome to {$schoolName}",
            'content' => 'Explore our world-class facilities and academic programs designed for the future leaders.',
            'image_url' => 'https://placehold.co/600x400?text=Welcome+Image',
        ]);
    }

    /**
     * Clear all application caches.
     */
    protected function clearSystemCaches()
    {
        $this->info('Step 5: Clearing system caches...');
        
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        
        $this->comment(' - Application cache cleared.');
        $this->comment(' - Blade views cleared.');
        $this->comment(' - Configuration cache cleared.');
    }

    /**
     * Output success message in a formatted way.
     */
    protected function success($message)
    {
        $this->output->writeln("<bg=green;fg=black;options=bold> {$message} </>");
    }
}
