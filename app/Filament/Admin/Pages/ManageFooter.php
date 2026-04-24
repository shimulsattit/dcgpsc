<?php

namespace App\Filament\Admin\Pages;

use App\Models\FooterSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageFooter extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.admin.pages.manage-footer';

    public ?array $data = [];

    public function mount(): void
    {
        $footerSetting = FooterSetting::first();

        if ($footerSetting) {
            $data = $footerSetting->toArray();

            // Ensure arrays are properly formatted
            if (empty($data['contact_phones']) || !is_array($data['contact_phones'])) {
                $data['contact_phones'] = [];
            }
            if (empty($data['featured_links']) || !is_array($data['featured_links'])) {
                $data['featured_links'] = [];
            }

            $this->form->fill($data);
        } else {
            $this->form->fill([
                'contact_phones' => [],
                'featured_links' => [],
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Logo & School Information')
                    ->schema([
                        Forms\Components\Textarea::make('logo_url')
                            ->label('Logo URL (Google Drive)')
                            ->rows(2)
                            ->helperText('Paste Google Drive share link for footer logo'),
                        Forms\Components\TextInput::make('school_name')
                            ->label('School Name')
                            ->required()
                            ->default('Barishal Cantonment Public School & College'),
                    ]),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('contact_title')
                            ->label('Section Title')
                            ->default('CONTACT')
                            ->required(),
                        Forms\Components\Repeater::make('contact_phones')
                            ->label('Phone Numbers')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Label')
                                    ->placeholder('e.g., Account, Admissions')
                                    ->required(),
                                Forms\Components\TextInput::make('number')
                                    ->label('Phone Number')
                                    ->placeholder('e.g., 01870802705')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Phone Number'),
                        Forms\Components\TextInput::make('contact_email')
                            ->label('Email Address')
                            ->email()
                            ->placeholder('bcpsc2019bsl@gmail.com'),
                        Forms\Components\Textarea::make('contact_address')
                            ->label('Address (ঠিকানা)')
                            ->rows(3)
                            ->placeholder('Enter school address...')
                            ->helperText('School এর সম্পূর্ণ ঠিকানা লিখুন'),
                    ]),

                Forms\Components\Section::make('Featured Links')
                    ->schema([
                        Forms\Components\TextInput::make('featured_links_title')
                            ->label('Section Title')
                            ->default('FEATURED LINKS')
                            ->required(),
                        Forms\Components\Repeater::make('featured_links')
                            ->label('Links')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Link Title')
                                    ->required(),
                                Forms\Components\TextInput::make('url')
                                    ->label('URL')
                                    ->url()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Link'),
                    ]),

                Forms\Components\Section::make('Facebook Page')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_title')
                            ->label('Section Title')
                            ->default('OUR FACEBOOK PAGE')
                            ->required(),
                        Forms\Components\Textarea::make('facebook_embed_url')
                            ->label('Facebook Page Plugin URL')
                            ->rows(3)
                            ->helperText('Get embed code from Facebook Page Plugin and paste the iframe src URL'),
                    ]),

                Forms\Components\Section::make('Social Media & Copyright')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook Page URL')
                            ->url(),
                        Forms\Components\TextInput::make('twitter_url')
                            ->label('Twitter URL')
                            ->url(),
                        Forms\Components\TextInput::make('copyright_text')
                            ->label('Copyright Text')
                            ->default('Copyright ©2024 BCPSC | Developed By Trust Innovation Ltd.')
                            ->required(),
                        Forms\Components\TextInput::make('privacy_policy_url')
                            ->label('Privacy Policy URL')
                            ->url(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Footer Settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $footerSetting = FooterSetting::first();

        if ($footerSetting) {
            $footerSetting->update($data);
        } else {
            FooterSetting::create($data);
        }

        Notification::make()
            ->success()
            ->title('Footer settings saved successfully')
            ->send();
    }
}
