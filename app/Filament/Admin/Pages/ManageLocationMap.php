<?php

namespace App\Filament\Admin\Pages;

use App\Models\LocationMap;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class ManageLocationMap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Location Map';
    protected static ?string $title = 'Location Map Settings';
    protected static string $view = 'filament.admin.pages.manage-location-map';
    protected static ?string $navigationGroup = 'Site Management';
    protected static ?int $navigationSort = 7;

    public ?array $data = [];

    public function mount(): void
    {
        $locationMap = LocationMap::first();

        $this->form->fill([
            'title' => $locationMap->title ?? 'Our Location',
            'embed_code' => $locationMap->embed_code ?? '',
            'is_active' => $locationMap->is_active ?? false,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('🗺️ Location Map Configuration')
                    ->description('Configure Google Maps embed for your location')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Enable Location Map')
                            ->helperText('Show location map on homepage')
                            ->default(false)
                            ->live()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('title')
                            ->label('Section Title')
                            ->default('Our Location')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('embed_code')
                            ->label('Google Maps Embed Code')
                            ->helperText('Paste the complete Google Maps iframe embed code here')
                            ->placeholder('<iframe src="https://www.google.com/maps/embed?pb=..." ...></iframe>')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $locationMap = LocationMap::firstOrNew();
        $locationMap->fill($data);
        $locationMap->save();

        Notification::make()
            ->title('Location Map settings saved successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save Settings')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }
}
