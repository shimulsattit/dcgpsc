<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PhotoGalleryResource\Pages;
use App\Filament\Admin\Resources\PhotoGalleryResource\RelationManagers;
use App\Models\PhotoGallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Services\R2Uploader;
use Illuminate\Support\Str;

class PhotoGalleryResource extends Resource
{
    protected static ?string $model = PhotoGallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationGroup = 'Photo Gallery'; // User requested "Photo Gallerie" expandable

    protected static ?string $modelLabel = 'Photo Gallery';

    protected static ?string $pluralModelLabel = 'Photo Gallery';

    protected static ?string $navigationLabel = 'Photo Gallery';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Album Details')
                    ->description('Create a photo album using Cloudflare R2 storage')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Album Title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('thumbnail_url')
                            ->label('Thumbnail URL (Manual/Override)')
                            ->helperText('সরাসরি URL দিন অথবা নিচ থেকে Cloudflare R2-তে আপলোড করুন। (ফাঁকা রাখলে গ্যালারির প্রথম ছবি থাম্বনেইল হিসেবে ব্যবহৃত হবে)')
                            ->placeholder('https://...')
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('thumbnail_upload_handler')
                            ->label('Upload New Thumbnail to Cloudflare R2')
                            ->image()
                            ->dehydrated(false)
                            ->storeFiles(false)
                            ->helperText('নতুন ছবি সিলেক্ট করলে সেটি R2-তে আপলোড হবে এবং উপরের Thumbnail URL ফিল্ডটি আপডেট হবে।')
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) return;
                                $file = is_array($state) ? ($state[0] ?? null) : $state;
                                if (! ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                                $url = R2Uploader::uploadAndGetUrl($file, 'galleries/thumbnails');
                                $set('thumbnail_url', $url);
                            })
                            ->columnSpanFull(),

                        Forms\Components\DatePicker::make('published_at')
                            ->label('Published Date')
                            ->default(now()),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required(),

                        Forms\Components\Section::make('Gallery Management')
                            ->description('Manage album images stored in Cloudflare R2.')
                            ->schema([
                                Forms\Components\FileUpload::make('gallery_upload_handler')
                                    ->label('Add More Images to Gallery')
                                    ->multiple()
                                    ->image()
                                    ->imageEditor()
                                    ->dehydrated(false)
                                    ->storeFiles(false)
                                    ->helperText('এখানে ছবি দিলে সেগুলো R2-তে আপলোড হবে এবং নিচের তালিকায় যুক্ত হবে।')

                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if (!$state) return;
                                        
                                        $currentImages = $get('images') ?? [];
                                        
                                        foreach ((array)$state as $file) {
                                            if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                                                $url = \App\Services\R2Uploader::uploadAndGetUrl($file, 'galleries/images');
                                                if ($url) {
                                                    $currentImages[] = ['url' => $url];
                                                }
                                            }
                                        }
                                        
                                        $set('images', $currentImages);
                                    })
                                    ->columnSpanFull(),

                                Forms\Components\Repeater::make('images')
                                    ->label('Existing Gallery Images (URLs)')
                                    ->schema([
                                        Forms\Components\TextInput::make('url')
                                            ->label('Image URL')
                                            ->disabled()
                                            ->columnSpan(3),
                                        Forms\Components\Placeholder::make('preview')
                                            ->content(fn ($get) => view('filament.forms.components.image-preview', ['imageUrl' => $get('url')]))
                                            ->columnSpan(1),
                                    ])
                                    ->grid(2)
                                    ->reorderable()
                                    ->dehydrated(true)
                                    ->columnSpanFull()
                                    ->afterStateHydrated(function (Forms\Components\Repeater $component, $state) {
                                        // Convert simple array of strings to array of objects for Repeater
                                        if (is_array($state)) {
                                            $formatted = [];
                                            foreach($state as $url) {
                                                if (is_string($url)) {
                                                    $formatted[] = ['url' => $url];
                                                } else {
                                                    $formatted[] = $url;
                                                }
                                            }
                                            $component->state($formatted);
                                        }
                                    })
                                    ->dehydrateStateUsing(function ($state) {
                                        // Convert back to simple array of strings for DB
                                        return collect($state)->pluck('url')->filter()->values()->toArray();
                                    }),
                            ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->square()
                    ->defaultImageUrl('https://via.placeholder.com/150?text=No+Image'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('google_drive_folder_link')
                    ->label('Folder Link')
                    ->limit(30)
                    ->url(fn($record) => $record->google_drive_folder_link)
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('published_at')
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPhotoGalleries::route('/'),
            'create' => Pages\CreatePhotoGallery::route('/create'),
            'edit' => Pages\EditPhotoGallery::route('/{record}/edit'),
        ];
    }
}
