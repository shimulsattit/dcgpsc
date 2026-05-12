<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OfferResource\Pages;
use App\Models\Offer;
use App\Services\R2Uploader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Offer Title (অফারের শিরোনাম)')
                    ->maxLength(255)
                    ->placeholder('e.g. Special Discount or New Notice'),

                Forms\Components\Textarea::make('image_url')
                    ->label('Image URL')
                    ->rows(3)
                    ->placeholder('সরাসরি image URL দিন অথবা নিচ থেকে R2-তে আপলোড করুন...')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image_upload')
                    ->label('Upload to Cloudflare R2')
                    ->image()
                    ->dehydrated(false)
                    ->storeFiles(false)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) return;
                        $file = is_array($state) ? ($state[0] ?? null) : $state;
                        if (! ($file instanceof TemporaryUploadedFile)) return;
                        $url = R2Uploader::uploadAndGetUrl($file, 'offers');
                        if ($url) {
                            $set('image_url', $url);
                        }
                    })
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('link')
                    ->label('Action Link (ক্লিক করলে কোথায় যাবে)')
                    ->placeholder('https://example.com/offer'),

                Forms\Components\TextInput::make('order')
                    ->label('Order (ক্রম)')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active (সক্রিয়)')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
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
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
