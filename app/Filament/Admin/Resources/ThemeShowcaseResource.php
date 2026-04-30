<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ThemeShowcaseResource\Pages;
use App\Models\ThemeShowcase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ThemeShowcaseResource extends Resource
{
    protected static ?string $model = ThemeShowcase::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationGroup = 'Site Management';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('image')
                    ->label('Thumbnail Image URL (Image/PDF)')
                    ->required()
                    ->helperText('সরাসরি URL দিন অথবা নিচ থেকে Cloudflare R2-তে ফাইল আপলোড করুন'),
                    
                Forms\Components\FileUpload::make('image_upload')
                    ->label('Upload Thumbnail to Cloudflare R2')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->dehydrated(false)
                    ->storeFiles(false)
                    ->helperText('ফাইল সিলেক্ট করলে Cloudflare R2-তে আপলোড হবে এবং উপরের Image URL ফিল্ডে লিংক বসে যাবে।')
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) return;
                        $file = is_array($state) ? ($state[0] ?? null) : $state;
                        if (! ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                        $mimeType = $file->getMimeType();
                        $folder = str_contains($mimeType, 'pdf') ? 'themes/documents' : 'themes/images';
                        $url = \App\Services\R2Uploader::uploadAndGetUrl($file, $folder);
                        $set('image', $url);
                    })
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('url')
                    ->label('Demo URL or File')
                    ->helperText('Link to the live demo or uploaded file.'),
                    
                Forms\Components\FileUpload::make('url_upload')
                    ->label('Upload File for URL to Cloudflare R2')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->dehydrated(false)
                    ->storeFiles(false)
                    ->helperText('ফাইল সিলেক্ট করলে Cloudflare R2-তে আপলোড হবে এবং উপরের Demo URL ফিল্ডে লিংক বসে যাবে।')
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) return;
                        $file = is_array($state) ? ($state[0] ?? null) : $state;
                        if (! ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                        $mimeType = $file->getMimeType();
                        $folder = str_contains($mimeType, 'pdf') ? 'themes/documents' : 'themes/images';
                        $url = \App\Services\R2Uploader::uploadAndGetUrl($file, $folder);
                        $set('url', $url);
                    })
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('theme_key')
                    ->label('Template Key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Unique key for the system (e.g., template_1, template_custom)'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('google') // Assuming logic is handled by accessor, but standard ImageColumn might expect disk.
                    // Since we use accessor, we might need TextColumn or ViewColumn if ImageColumn fails with external URLs. 
                    // Actually, ImageColumn works with external URLs if state returns a URL.
                    ->defaultImageUrl(url('images/placeholder.png')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->icon('heroicon-m-link')
                    ->openUrlInNewTab(),
                Tables\Columns\ToggleColumn::make('is_active'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListThemeShowcases::route('/'),
            'create' => Pages\CreateThemeShowcase::route('/create'),
            'edit' => Pages\EditThemeShowcase::route('/{record}/edit'),
        ];
    }
}
