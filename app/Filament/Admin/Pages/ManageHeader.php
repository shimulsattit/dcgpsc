<?php

namespace App\Filament\Admin\Pages;

use App\Models\HeaderSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageHeader extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.admin.pages.manage-header';

    public ?array $data = [];

    public function mount(): void
    {
        $headerSetting = HeaderSetting::first();

        if ($headerSetting) {
            $this->form->fill($headerSetting->toArray());
        } else {
            $this->form->fill();
        }

        // Load Admin Tutorial Link
        // Load for all users who can access this page
        $this->data['admin_tutorial_link'] = \App\Models\Setting::get('admin_tutorial_link');
        // Re-fill form to include this extra field
        $this->form->fill(array_merge($this->form->getState(), ['admin_tutorial_link' => $this->data['admin_tutorial_link']]));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Top Bar')
                    ->schema([
                        Forms\Components\Toggle::make('show_top_bar')
                            ->label('Show Top Bar')
                            ->default(true)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->placeholder('info@bacpsc.edu.bd'),
                        Forms\Components\Repeater::make('phones')
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
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->placeholder('https://facebook.com/...'),
                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->placeholder('https://youtube.com/...'),
                        Forms\Components\TextInput::make('twitter_url')
                            ->label('Twitter URL')
                            ->url()
                            ->placeholder('https://twitter.com/...'),
                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->placeholder('https://instagram.com/...'),
                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('LinkedIn URL')
                            ->url()
                            ->placeholder('https://linkedin.com/...'),
                        Forms\Components\Toggle::make('show_login_link')
                            ->label('Show Login Link')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Main Header (Logo & School Info)')
                    ->schema([
                        Forms\Components\Textarea::make('logo_url')
                            ->label('Logo URL (Google Drive)')
                            ->rows(2)
                            ->helperText('Paste Google Drive share link for logo'),
                        Forms\Components\Textarea::make('favicon_url')
                            ->label('Favicon URL (Google Drive)')
                            ->rows(2)
                            ->helperText('Paste Google Drive share link for favicon. Recommended size: 16x16, 32x32, or 512x512 (PNG/ICO)'),
                        Forms\Components\TextInput::make('site_name')
                            ->label('School Name (English)')
                            ->required()
                            ->default(env('APP_NAME', 'School Name'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('site_name_font_size')
                            ->label('School Name Font Size')
                            ->placeholder('e.g., 1.5rem or 24px')
                            ->default('1.5rem')
                            ->maxLength(255)
                            ->helperText('Adjust the font size of the English School Name'),
                        Forms\Components\TextInput::make('site_name_bangla')
                            ->label('School Name (Bangla)')
                            ->required()
                            ->default('স্কুলের নাম (বাংলা)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('eiin')
                            ->label('EIIN & Location')
                            ->default('Barishal Cantonment, EIIN:136998')
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Action Buttons')
                    ->description('Add dynamic action buttons with custom colors. Use # in URL to hide button on frontend.')
                    ->schema([
                        Forms\Components\Repeater::make('action_buttons')
                            ->label('Buttons')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Button Label')
                                    ->placeholder('e.g., Online Admission')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('url')
                                    ->label('Button URL')
                                    ->placeholder('https://... or # to hide')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\ColorPicker::make('bg_color')
                                    ->label('Background Color')
                                    ->default('#006a4e')
                                    ->required(),
                                Forms\Components\ColorPicker::make('text_color')
                                    ->label('Text Color')
                                    ->default('#ffffff')
                                    ->required(),
                                Forms\Components\TextInput::make('order')
                                    ->label('Display Order')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                            ])
                            ->columns(4)
                            ->defaultItems(0)
                            ->addActionLabel('Add Action Button')
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['label'] ?? null)
                            ->reorderable()
                            ->orderColumn('order'),
                    ]),

                Forms\Components\Section::make('Notice Ticker Settings')
                    ->description('Control the notice ticker (scrolling bar) in the header.')
                    ->schema([
                        Forms\Components\Toggle::make('show_notice_ticker')
                            ->label('Show Notice Ticker')
                            ->default(true),
                        Forms\Components\TextInput::make('notice_ticker_label')
                            ->label('Ticker Label (টিকার লেবেল)')
                            ->default('LATEST NEWS')
                            ->placeholder('e.g., LATEST NEWS or ব্রেকিং নিউজ'),
                        Forms\Components\TextInput::make('notice_ticker_limit')
                            ->label('Notice Limit')
                            ->numeric()
                            ->default(10)
                            ->helperText('Number of recent notices to show in the scrolling bar'),
                        Forms\Components\Select::make('ticker_position')
                            ->label('Ticker Position (টিকার পজিশন)')
                            ->options([
                                'above_slider' => '⬆ Slider এর উপরে (Above Slider)',
                                'below_slider' => '⬇ Slider এর নিচে (Below Slider) — Default',
                            ])
                            ->default('below_slider')
                            ->helperText('LATEST NEWS ticker কোথায় দেখাবে তা নির্বাচন করুন')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Shop Module Settings')
                    ->description('Enable or disable the Online Course / Book Sale feature.')
                    ->schema([
                        Forms\Components\Toggle::make('is_shop_enabled')
                            ->label('Active Online Shop')
                            ->default(true)
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->helperText('When disabled, the shop page and "Buy Now" buttons will be hidden from the website.'),
                    ]),

                Forms\Components\Section::make('Admin Settings')
                    // ->visible(fn() => auth()->user()->hasRole('super_admin')) // Temporarily disabled to ensure visibility
                    ->schema([
                        Forms\Components\TextInput::make('admin_tutorial_link')
                            ->label('Admin Video Tutorial Link (YouTube)')
                            ->url()
                            ->placeholder('https://youtube.com/watch?v=...')
                            ->helperText('This icon will appear at the bottom of the admin panel sidebar.')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Handle Admin Tutorial Link (Save to Settings table)
        if (isset($data['admin_tutorial_link'])) {
            \App\Models\Setting::updateOrCreate(
                ['key' => 'admin_tutorial_link'],
                ['value' => $data['admin_tutorial_link']]
            );
            // Remove from data to avoid column error in HeaderSetting
            unset($data['admin_tutorial_link']);
        }

        $headerSetting = HeaderSetting::first();

        if ($headerSetting) {
            $headerSetting->update($data);
        } else {
            HeaderSetting::create($data);
        }

        Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->send();
    }
}
