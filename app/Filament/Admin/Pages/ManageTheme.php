<?php

namespace App\Filament\Admin\Pages;

use App\Models\ThemeSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageTheme extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'Site Management';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationLabel = 'Theme Settings';

    protected static string $view = 'filament.admin.pages.manage-theme';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }


    public ?array $data = [];

    public function mount(): void
    {
        $themeSetting = ThemeSetting::first();

        if ($themeSetting) {
            $this->form->fill($themeSetting->toArray());
        } else {
            $this->form->fill([
                'homepage_template' => 'template_1',
                'primary_color' => '#006a4e',
                'secondary_color' => '#f42a41',
                'accent_color' => '#f8f9fa',
                'text_color' => '#333333',
                'link_color' => '#006a4e',
                'topbar_bg_color' => '#5a9a7a',
                'header_bg_color' => '#2d6a4f',
                'navbar_bg_color' => '#1e5540',
                'navbar_hover_color' => 'rgba(255,255,255,0.15)',
                'footer_bg_color' => '#2c3e50',
                'footer_bottom_bg_color' => '#1a252f',
                'body_bg_color' => '#f4f6f9',
                'ticker_bg_color' => '#006a4e',
                'ticker_text_color' => '#ffffff',
            ]);
        }
    }

    protected function getColorPresets(): array
    {
        return [
            'default' => [
                'primary_color' => '#006a4e',
                'secondary_color' => '#f42a41',
                'accent_color' => '#f8f9fa',
                'text_color' => '#333333',
                'link_color' => '#006a4e',
                'topbar_bg_color' => '#5a9a7a',
                'header_bg_color' => '#2d6a4f',
                'navbar_bg_color' => '#1e5540',
                'navbar_hover_color' => 'rgba(255,255,255,0.15)',
                'footer_bg_color' => '#2c3e50',
                'footer_bottom_bg_color' => '#1a252f',
                'body_bg_color' => '#f4f6f9',
            ],
            'ocean_blue' => [
                'primary_color' => '#0077be',
                'secondary_color' => '#00a8e8',
                'accent_color' => '#e3f2fd',
                'text_color' => '#1a1a1a',
                'link_color' => '#0077be',
                'topbar_bg_color' => '#005f8f',
                'header_bg_color' => '#004d73',
                'navbar_bg_color' => '#003d5c',
                'navbar_hover_color' => 'rgba(255,255,255,0.2)',
                'footer_bg_color' => '#01579b',
                'footer_bottom_bg_color' => '#003d5c',
                'body_bg_color' => '#e3f2fd',
            ],
            'forest_green' => [
                'primary_color' => '#2e7d32',
                'secondary_color' => '#66bb6a',
                'accent_color' => '#e8f5e9',
                'text_color' => '#212121',
                'link_color' => '#2e7d32',
                'topbar_bg_color' => '#388e3c',
                'header_bg_color' => '#2e7d32',
                'navbar_bg_color' => '#1b5e20',
                'navbar_hover_color' => 'rgba(255,255,255,0.15)',
                'footer_bg_color' => '#1b5e20',
                'footer_bottom_bg_color' => '#0d3d13',
                'body_bg_color' => '#e8f5e9',
            ],
            'sunset_orange' => [
                'primary_color' => '#e65100',
                'secondary_color' => '#ff6f00',
                'accent_color' => '#fff3e0',
                'text_color' => '#212121',
                'link_color' => '#e65100',
                'topbar_bg_color' => '#ef6c00',
                'header_bg_color' => '#e65100',
                'navbar_bg_color' => '#bf360c',
                'navbar_hover_color' => 'rgba(255,255,255,0.2)',
                'footer_bg_color' => '#bf360c',
                'footer_bottom_bg_color' => '#8d2a0c',
                'body_bg_color' => '#fff3e0',
            ],
            'royal_purple' => [
                'primary_color' => '#6a1b9a',
                'secondary_color' => '#ab47bc',
                'accent_color' => '#f3e5f5',
                'text_color' => '#212121',
                'link_color' => '#6a1b9a',
                'topbar_bg_color' => '#7b1fa2',
                'header_bg_color' => '#6a1b9a',
                'navbar_bg_color' => '#4a148c',
                'navbar_hover_color' => 'rgba(255,255,255,0.15)',
                'footer_bg_color' => '#4a148c',
                'footer_bottom_bg_color' => '#311b5e',
                'body_bg_color' => '#f3e5f5',
            ],
            'modern_gray' => [
                'primary_color' => '#455a64',
                'secondary_color' => '#78909c',
                'accent_color' => '#eceff1',
                'text_color' => '#212121',
                'link_color' => '#455a64',
                'topbar_bg_color' => '#546e7a',
                'header_bg_color' => '#455a64',
                'navbar_bg_color' => '#37474f',
                'navbar_hover_color' => 'rgba(255,255,255,0.15)',
                'footer_bg_color' => '#37474f',
                'footer_bottom_bg_color' => '#263238',
                'body_bg_color' => '#eceff1',
            ],
            'crimson_red' => [
                'primary_color' => '#c62828',
                'secondary_color' => '#e53935',
                'accent_color' => '#ffebee',
                'text_color' => '#212121',
                'link_color' => '#c62828',
                'topbar_bg_color' => '#d32f2f',
                'header_bg_color' => '#c62828',
                'navbar_bg_color' => '#b71c1c',
                'navbar_hover_color' => 'rgba(255,255,255,0.15)',
                'footer_bg_color' => '#b71c1c',
                'footer_bottom_bg_color' => '#8b1414',
                'body_bg_color' => '#ffebee',
            ],
            'teal_aqua' => [
                'primary_color' => '#00897b',
                'secondary_color' => '#26a69a',
                'accent_color' => '#e0f2f1',
                'text_color' => '#212121',
                'link_color' => '#00897b',
                'topbar_bg_color' => '#00796b',
                'header_bg_color' => '#00695c',
                'navbar_bg_color' => '#004d40',
                'navbar_hover_color' => 'rgba(255,255,255,0.15)',
                'footer_bg_color' => '#004d40',
                'footer_bottom_bg_color' => '#00332a',
                'body_bg_color' => '#e0f2f1',
            ],
            'golden_yellow' => [
                'primary_color' => '#f9a825',
                'secondary_color' => '#fbc02d',
                'accent_color' => '#fffde7',
                'text_color' => '#212121',
                'link_color' => '#f9a825',
                'topbar_bg_color' => '#f57f17',
                'header_bg_color' => '#f9a825',
                'navbar_bg_color' => '#f57f17',
                'navbar_hover_color' => 'rgba(255,255,255,0.2)',
                'footer_bg_color' => '#f57f17',
                'footer_bottom_bg_color' => '#c66900',
                'body_bg_color' => '#fffde7',
            ],
            'navy_blue' => [
                'primary_color' => '#1565c0',
                'secondary_color' => '#1976d2',
                'accent_color' => '#e3f2fd',
                'text_color' => '#212121',
                'link_color' => '#1565c0',
                'topbar_bg_color' => '#0d47a1',
                'header_bg_color' => '#1565c0',
                'navbar_bg_color' => '#0d47a1',
                'navbar_hover_color' => 'rgba(255,255,255,0.15)',
                'footer_bg_color' => '#0d47a1',
                'footer_bottom_bg_color' => '#083375',
                'body_bg_color' => '#e3f2fd',
            ],
            'mint_green' => [
                'primary_color' => '#66bb6a',
                'secondary_color' => '#81c784',
                'accent_color' => '#e8f5e9',
                'text_color' => '#212121',
                'link_color' => '#66bb6a',
                'topbar_bg_color' => '#4caf50',
                'header_bg_color' => '#43a047',
                'navbar_bg_color' => '#388e3c',
                'navbar_hover_color' => 'rgba(255,255,255,0.15)',
                'footer_bg_color' => '#2e7d32',
                'footer_bottom_bg_color' => '#1b5e20',
                'body_bg_color' => '#e8f5e9',
            ],
            'chocolate_brown' => [
                'primary_color' => '#6d4c41',
                'secondary_color' => '#8d6e63',
                'accent_color' => '#efebe9',
                'text_color' => '#212121',
                'link_color' => '#6d4c41',
                'topbar_bg_color' => '#5d4037',
                'header_bg_color' => '#4e342e',
                'navbar_bg_color' => '#3e2723',
                'navbar_hover_color' => 'rgba(255,255,255,0.15)',
                'footer_bg_color' => '#3e2723',
                'footer_bottom_bg_color' => '#2c1b18',
                'body_bg_color' => '#efebe9',
            ],
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('🏠 Homepage Template')
                    ->description('Choose the layout design for your homepage. Each template has a unique style and arrangement.')
                    ->schema([
                        Forms\Components\Select::make('homepage_template')
                            ->label('Template Design')
                            ->options(\App\Models\ThemeShowcase::where('is_active', true)->pluck('name', 'theme_key'))
                            ->default('template_1')
                            ->required()
                            ->live()
                            ->helperText('💡 Select a template to change your homepage design. Changes will be visible immediately after saving.')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('sidebar_title')
                            ->label('Sidebar Title')
                            ->default('BCPSC INFORMATION')
                            ->maxLength(100)
                            ->helperText('Title shown in the sidebar section (e.g., BCPSC INFORMATION)')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('news_events_title')
                            ->label('News & Events Section Title')
                            ->default('NEWS AND EVENTS')
                            ->maxLength(100)
                            ->helperText('Title for the News & Events section')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('achievements_title')
                            ->label('Achievements Section Title')
                            ->default('ACHIEVEMENTS')
                            ->maxLength(100)
                            ->helperText('Title for the Achievements section')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('all_notices_title')
                            ->label('All Notices Section Title')
                            ->default('ALL PUBLISHED NOTICE')
                            ->maxLength(100)
                            ->helperText('Title for the All Published Notices section')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Forms\Components\Section::make('💳 Message Card Design')
                    ->description('Choose design style for message cards across all homepage templates and customize colors')
                    ->schema([
                        Forms\Components\Select::make('message_card_design')
                            ->label('Card Design Style')
                            ->options([
                                'solid' => '🎨 Solid Color (Default)',
                                'image' => '🖼️ Background Image with Overlay',
                                'modern' => '✨ Modern Card with Border',
                            ])
                            ->default('solid')
                            ->required()
                            ->live()
                            ->helperText('Select a design style for message cards. Changes apply to all homepage templates.')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('message_card_bg_image')
                            ->label('Background Image (Upload to R2)')
                            ->disk('r2')
                            ->directory('theme')
                            ->visibility('public')
                            ->image()
                            ->helperText('Upload a background image directly to Cloudflare R2.')
                            ->visible(fn(Forms\Get $get) => $get('message_card_design') === 'image')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('message_card_bg_image_gdrive')
                            ->label('Background Image (Google Drive URL)')
                            ->url()
                            ->nullable()
                            ->placeholder('https://drive.google.com/uc?export=view&id=YOUR_FILE_ID')
                            ->helperText('Paste Google Drive direct image link. Format: https://drive.google.com/uc?export=view&id=YOUR_FILE_ID')
                            ->visible(fn(Forms\Get $get) => $get('message_card_design') === 'image')
                            ->columnSpanFull(),
                        Forms\Components\ColorPicker::make('card_header_color')
                            ->label('Card Header Color')
                            ->helperText('Background color for card header')
                            ->default('#134B50'),
                        Forms\Components\ColorPicker::make('card_name_text_color')
                            ->label('Name')
                            ->helperText('Text color for name (e.g., "Maj Gen M Khair Uddin")')
                            ->default('#e8f5e9'),
                        Forms\Components\ColorPicker::make('card_designation_text_color')
                            ->label('Designation')
                            ->helperText('Text color for designation (e.g., "SGP, ndc, afwc, psc")')
                            ->default('#ffffff'),
                        Forms\Components\ColorPicker::make('card_image_border_color')
                            ->label('Image Border Color')
                            ->helperText('Border color for profile images')
                            ->default('#ffffff'),
                        Forms\Components\ColorPicker::make('card_button_bg_color')
                            ->label('Button Background Color')
                            ->helperText('Background color for "Read Message" button')
                            ->default('#134B50'),
                        Forms\Components\ColorPicker::make('card_button_text_color')
                            ->label('Button Text Color')
                            ->helperText('Text color for "Read Message" button')
                            ->default('#ffffff'),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(false),

                Forms\Components\Section::make('🎨 Quick Color Presets')
                    ->description('Choose from 12 professionally designed color schemes. Select a preset to instantly apply all colors.')
                    ->schema([
                        Forms\Components\Select::make('color_preset')
                            ->label('Color Scheme')
                            ->options([
                                'default' => '🇧🇩 Default (Bangladesh Green)',
                                'ocean_blue' => '🌊 Ocean Blue',
                                'forest_green' => '🌲 Forest Green',
                                'sunset_orange' => '🌅 Sunset Orange',
                                'royal_purple' => '👑 Royal Purple',
                                'modern_gray' => '⚫ Modern Gray',
                                'crimson_red' => '🔴 Crimson Red',
                                'teal_aqua' => '💎 Teal Aqua',
                                'golden_yellow' => '⭐ Golden Yellow',
                                'navy_blue' => '⚓ Navy Blue',
                                'mint_green' => '🍃 Mint Green',
                                'chocolate_brown' => '🍫 Chocolate Brown',
                            ])
                            ->placeholder('Choose a color scheme...')
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state && isset($this->getColorPresets()[$state])) {
                                    $preset = $this->getColorPresets()[$state];
                                    foreach ($preset as $key => $value) {
                                        $set($key, $value);
                                    }
                                }
                            })
                            ->helperText('💡 Tip: After selecting a preset, you can still customize individual colors below'),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Forms\Components\Section::make('Basic Colors')
                    ->description('Primary website colors used throughout the site')
                    ->schema([
                        Forms\Components\ColorPicker::make('primary_color')
                            ->label('Primary Color')
                            ->helperText('Main brand color (headers, menu cards, widgets)')
                            ->required(),
                        Forms\Components\ColorPicker::make('secondary_color')
                            ->label('Secondary Color')
                            ->helperText('Accent color (buttons, highlights, calendar)')
                            ->required(),
                        Forms\Components\ColorPicker::make('accent_color')
                            ->label('Accent Color')
                            ->helperText('Light background color (notice board, cards)')
                            ->required(),
                        Forms\Components\ColorPicker::make('text_color')
                            ->label('Text Color')
                            ->helperText('Main text color throughout the site')
                            ->required(),
                        Forms\Components\ColorPicker::make('link_color')
                            ->label('Link Color')
                            ->helperText('Color for hyperlinks')
                            ->required(),
                    ])->columns(3)->collapsible(),

                Forms\Components\Section::make('Header & Navigation Colors')
                    ->description('Colors for top bar, header, and navigation menu')
                    ->schema([
                        Forms\Components\ColorPicker::make('topbar_bg_color')
                            ->label('Top Bar Background')
                            ->helperText('Top bar with email and phone')
                            ->required(),
                        Forms\Components\ColorPicker::make('header_bg_color')
                            ->label('Main Header Background')
                            ->helperText('Header section with logo and school name')
                            ->required(),
                        Forms\Components\ColorPicker::make('navbar_bg_color')
                            ->label('Navigation Bar Background')
                            ->helperText('Main navigation menu background')
                            ->required(),
                        Forms\Components\TextInput::make('navbar_hover_color')
                            ->label('Navigation Hover Color')
                            ->helperText('Use rgba format, e.g., rgba(255,255,255,0.15)')
                            ->required(),
                        Forms\Components\Toggle::make('is_header_gradient')
                            ->label('Enable Header Gradient (গ্রেডিয়েন্ট কালার অন করুন)')
                            ->helperText('হেডার এবং মেনুবারে গ্লসি গ্রেডিয়েন্ট কালার চালু করতে এটি অন রাখুন।')
                            ->default(true)
                            ->columnSpanFull(),
                    ])->columns(2)->collapsible(),

                Forms\Components\Section::make('Footer Colors')
                    ->description('Colors for footer sections')
                    ->schema([
                        Forms\Components\ColorPicker::make('footer_bg_color')
                            ->label('Footer Background')
                            ->helperText('Main footer section background')
                            ->required(),
                        Forms\Components\ColorPicker::make('footer_bottom_bg_color')
                            ->label('Footer Bottom Background')
                            ->helperText('Copyright section background')
                            ->required(),
                    ])->columns(2)->collapsible(),

                Forms\Components\Section::make('News Ticker Colors')
                    ->description('Colors for the scrolling news ticker section')
                    ->schema([
                        Forms\Components\ColorPicker::make('ticker_bg_color')
                            ->label('Ticker Background Color')
                            ->helperText('Background color for the news ticker/scrolling section')
                            ->required(),
                        Forms\Components\ColorPicker::make('ticker_text_color')
                            ->label('Ticker Text Color')
                            ->helperText('Text color for the news ticker/scrolling section')
                            ->required(),
                    ])->columns(2)->collapsible(),

                Forms\Components\Section::make('Page Colors')
                    ->description('Background colors for page elements')
                    ->schema([
                        Forms\Components\ColorPicker::make('body_bg_color')
                            ->label('Body Background Color')
                            ->helperText('Main page background color')
                            ->required(),
                    ])->collapsible(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Theme Settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $themeSetting = ThemeSetting::first();

        if ($themeSetting) {
            $themeSetting->update($data);
        } else {
            ThemeSetting::create($data);
        }

        Notification::make()
            ->success()
            ->title('Theme settings saved successfully')
            ->send();
    }
}
