<?php

namespace App\Filament\Admin\Pages;

use App\Models\HeaderSetting;
use App\Models\FooterSetting;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Tabs;
use App\Services\R2Uploader;

class ManageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Site Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Settings';

    protected static string $view = 'filament.admin.pages.manage-settings';

    protected static ?string $title = 'Site Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $headerSetting = HeaderSetting::first();
        $footerSetting = FooterSetting::first();
        
        $headerData = $headerSetting ? $headerSetting->toArray() : [];
        $footerData = $footerSetting ? $footerSetting->toArray() : [];

        // Formatting footer arrays
        if (empty($footerData['contact_phones']) || !is_array($footerData['contact_phones'])) {
            $footerData['contact_phones'] = [];
        }
        if (empty($footerData['featured_links']) || !is_array($footerData['featured_links'])) {
            $footerData['featured_links'] = [];
        }

        // Map data to uniquely named keys to avoid collisions
        $data = [
            'header' => $headerData,
            'footer' => $footerData,
            'admin_tutorial_link' => Setting::get('admin_tutorial_link'),
        ];

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Head Section (হেডার সেকশন)')
                    ->icon('heroicon-o-bars-arrow-up')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Section::make('Top Bar')
                            ->schema([
                                Forms\Components\Toggle::make('header.show_top_bar')
                                    ->label('Show Top Bar')
                                    ->default(true)
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('header.email')
                                    ->label('Email Address')
                                    ->email()
                                    ->placeholder('info@bacpsc.edu.bd'),
                                Forms\Components\Repeater::make('header.phones')
                                    ->label('Phone Numbers')
                                    ->schema([
                                        Forms\Components\TextInput::make('number')
                                            ->label('Phone Number')
                                            ->placeholder('+8801769026044')
                                            ->required(),
                                    ])
                                    ->defaultItems(1)
                                    ->addActionLabel('Add Phone Number')
                                    ->collapsible()
                                    ->itemLabel(fn(array $state): ?string => $state['number'] ?? null),
                                Forms\Components\TextInput::make('header.facebook_url')
                                    ->label('Facebook URL')
                                    ->url()
                                    ->placeholder('https://facebook.com/...'),
                                Forms\Components\TextInput::make('header.youtube_url')
                                    ->label('YouTube URL')
                                    ->url()
                                    ->placeholder('https://youtube.com/...'),
                                Forms\Components\TextInput::make('header.twitter_url')
                                    ->label('Twitter URL')
                                    ->url(),
                                Forms\Components\TextInput::make('header.instagram_url')
                                    ->label('Instagram URL')
                                    ->url(),
                                Forms\Components\TextInput::make('header.linkedin_url')
                                    ->label('LinkedIn URL')
                                    ->url(),
                                Forms\Components\Toggle::make('header.show_login_link')
                                    ->label('Show Login Link')
                                    ->default(true),
                            ])->columns(2),

                        Forms\Components\Section::make('Main Header (Logo & School Info)')
                            ->schema([
                                Forms\Components\TextInput::make('header.logo_url')
                                    ->label('Logo URL')
                                    ->placeholder('https://...')
                                    ->helperText('সরাসরি URL দিন অথবা নিচ থেকে Cloudflare R2-তে আপলোড করুন।'),
                                Forms\Components\FileUpload::make('header.logo_upload_handler')
                                    ->label('Upload Logo to Cloudflare R2')
                                    ->image()
                                    ->dehydrated(false)
                                    ->storeFiles(false)
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if (!$state) return;
                                        $file = is_array($state) ? ($state[0] ?? null) : $state;
                                        if (!($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                                        $url = R2Uploader::uploadAndGetUrl($file, 'settings/logos');
                                        if ($url) {
                                            $set('header.logo_url', $url);
                                        }
                                    }),
                                
                                Forms\Components\TextInput::make('header.favicon_url')
                                    ->label('Favicon URL')
                                    ->placeholder('https://...')
                                    ->helperText('সরাসরি URL দিন অথবা নিচ থেকে Cloudflare R2-তে আপলোড করুন।'),
                                Forms\Components\FileUpload::make('header.favicon_upload_handler')
                                    ->label('Upload Favicon to Cloudflare R2')
                                    ->image()
                                    ->dehydrated(false)
                                    ->storeFiles(false)
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if (!$state) return;
                                        $file = is_array($state) ? ($state[0] ?? null) : $state;
                                        if (!($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                                        $url = R2Uploader::uploadAndGetUrl($file, 'settings/favicons');
                                        if ($url) {
                                            $set('header.favicon_url', $url);
                                        }
                                    }),
                                Forms\Components\TextInput::make('header.site_name')
                                    ->label('School Name (English)')
                                    ->required(),
                                Forms\Components\TextInput::make('header.site_name_font_size')
                                    ->label('School Name Font Size')
                                    ->default('1.5rem')
                                    ->placeholder('e.g., 1.5rem or 24px')
                                    ->helperText('Adjust the font size of the English School Name'),
                                Forms\Components\TextInput::make('header.site_name_bangla')
                                    ->label('School Name (Bangla)')
                                    ->required(),
                                Forms\Components\TextInput::make('header.eiin')
                                    ->label('EIIN & Location'),
                            ])->columns(2),

                        Forms\Components\Section::make('Action Buttons')
                            ->description('Add dynamic action buttons with custom colors.')
                            ->schema([
                                Forms\Components\Repeater::make('header.action_buttons')
                                    ->label('Buttons')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')
                                            ->label('Button Label')
                                            ->required(),
                                        Forms\Components\TextInput::make('url')
                                            ->label('Button URL')
                                            ->required(),
                                        Forms\Components\ColorPicker::make('bg_color')
                                            ->label('Background Color')
                                            ->default('#006a4e'),
                                        Forms\Components\ColorPicker::make('text_color')
                                            ->label('Text Color')
                                            ->default('#ffffff'),
                                        Forms\Components\TextInput::make('order')
                                            ->label('Order')
                                            ->numeric()
                                            ->default(0),
                                    ])
                                    ->columns(5)
                                    ->collapsible()
                                    ->reorderable()
                                    ->orderColumn('order')
                                    ->itemLabel(fn(array $state): ?string => $state['label'] ?? null),
                            ]),

                        Forms\Components\Section::make('Notice Ticker & Other')
                            ->schema([
                                Forms\Components\Toggle::make('header.show_notice_ticker')
                                    ->label('Show Notice Ticker')
                                    ->default(true),
                                Forms\Components\TextInput::make('header.notice_ticker_label')
                                    ->label('Ticker Label (টিকার লেবেল)')
                                    ->default('LATEST NEWS')
                                    ->placeholder('e.g., LATEST NEWS or ব্রেকিং নিউজ'),
                                Forms\Components\TextInput::make('header.notice_ticker_limit')
                                    ->label('Notice Limit')
                                    ->numeric()
                                    ->default(10),
                                Forms\Components\Select::make('header.ticker_position')
                                    ->label('Ticker Position (টিকার পজিশন)')
                                    ->options([
                                        'above_slider' => '⬆ Slider এর উপরে (Above Slider)',
                                        'below_slider' => '⬇ Slider এর নিচে (Below Slider) — Default',
                                    ])->default('below_slider'),
                                Forms\Components\Toggle::make('header.is_shop_enabled')
                                    ->label('Active Online Shop')
                                    ->default(true),
                                Forms\Components\TextInput::make('admin_tutorial_link')
                                    ->label('Admin Video Tutorial Link (YouTube)')
                                    ->url()
                                    ->placeholder('https://youtube.com/watch?v=...')
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ]),

                Forms\Components\Section::make('Footer Section (ফুটার সেকশন)')
                    ->icon('heroicon-o-bars-arrow-down')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Section::make('Logo & School Information')
                            ->schema([
                                Forms\Components\TextInput::make('footer.logo_url')
                                    ->label('Footer Logo URL')
                                    ->placeholder('https://...')
                                    ->helperText('সরাসরি URL দিন অথবা নিচ থেকে Cloudflare R2-তে আপলোড করুন।'),
                                Forms\Components\FileUpload::make('footer.logo_upload_handler')
                                    ->label('Upload Footer Logo to Cloudflare R2')
                                    ->image()
                                    ->dehydrated(false)
                                    ->storeFiles(false)
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if (!$state) return;
                                        $file = is_array($state) ? ($state[0] ?? null) : $state;
                                        if (!($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                                        $url = R2Uploader::uploadAndGetUrl($file, 'settings/footer');
                                        if ($url) {
                                            $set('footer.logo_url', $url);
                                        }
                                    }),
                                Forms\Components\TextInput::make('footer.school_name')
                                    ->label('School Name (Footer Display)')
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Contact Information')
                            ->schema([
                                Forms\Components\TextInput::make('footer.contact_title')
                                    ->label('Section Title')
                                    ->default('CONTACT'),
                                Forms\Components\Repeater::make('footer.contact_phones')
                                    ->label('Phone Numbers')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')->required(),
                                        Forms\Components\TextInput::make('number')->required(),
                                    ])->columns(2),
                                Forms\Components\TextInput::make('footer.contact_email')
                                    ->label('Email Address')
                                    ->email(),
                                Forms\Components\Textarea::make('footer.contact_address')
                                    ->label('Address (ঠিকানা)')
                                    ->rows(3),
                            ])->columns(2),

                        Forms\Components\Section::make('Social & Copyright')
                            ->schema([
                                Forms\Components\TextInput::make('footer.facebook_title')
                                    ->label('Facebook Section Title')
                                    ->default('OUR FACEBOOK PAGE'),
                                Forms\Components\TextInput::make('footer.facebook_url')
                                    ->label('Facebook Page URL')
                                    ->url(),
                                Forms\Components\TextInput::make('footer.twitter_url')
                                    ->label('Twitter URL')
                                    ->url(),
                                Forms\Components\Textarea::make('footer.facebook_embed_url')
                                    ->label('Facebook Page Plugin URL')
                                    ->rows(3)
                                    ->helperText('Get embed code from Facebook Page Plugin and paste the iframe src URL'),
                                Forms\Components\TextInput::make('footer.copyright_text')
                                    ->label('Copyright Text')
                                    ->required()
                                    ->helperText('Use {year} to automatically display the current year (e.g., Copyright © {year} BCPSC)'),
                                Forms\Components\TextInput::make('footer.privacy_policy_url')
                                    ->label('Privacy Policy URL')
                                    ->url(),
                            ])->columns(2),

                        Forms\Components\Section::make('Featured Links')
                            ->schema([
                                Forms\Components\TextInput::make('footer.featured_links_title')
                                    ->label('Featured Links Title')
                                    ->default('FEATURED LINKS'),
                                Forms\Components\Repeater::make('footer.featured_links')
                                    ->label('Links')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')->required(),
                                        Forms\Components\TextInput::make('url')->url()->required(),
                                    ])->columns(2),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save All Settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // 1. Handle Admin Tutorial Link (Setting model)
        if (isset($data['admin_tutorial_link'])) {
            Setting::updateOrCreate(
                ['key' => 'admin_tutorial_link'],
                ['value' => $data['admin_tutorial_link']]
            );
        }

        // 2. Handle Header settings
        if (isset($data['header'])) {
            $headerSetting = HeaderSetting::first() ?? new HeaderSetting();
            $headerSetting->fill($data['header']);
            $headerSetting->save();
        }

        // 3. Handle Footer settings
        if (isset($data['footer'])) {
            $footerSetting = FooterSetting::first() ?? new FooterSetting();
            $footerSetting->fill($data['footer']);
            $footerSetting->save();
        }

        Notification::make()
            ->success()
            ->title('All settings saved successfully')
            ->send();
    }
}
