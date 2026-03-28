<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SliderResource\Pages;
use App\Filament\Admin\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use App\Services\GoogleDriveUploader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title (শিরোনাম)')
                    ->maxLength(255)
                    ->placeholder('Optional slider title'),

                Forms\Components\Textarea::make('image_url')
                    ->label('Image URL (Google Drive লিংক)')
                    ->rows(3)
                    ->placeholder('Paste Google Drive share link or direct image URL...')
                    ->helperText('Google Drive share link পেস্ট করলেই ফ্রন্টএন্ডে ছবি ঠিকমতো দেখা যাবে (helper already configured).')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image_upload')
                    ->label('Upload to Google Drive (optional)')
                    ->image()
                    ->dehydrated(false)
                    ->storeFiles(false)
                    ->helperText('লোকাল থেকে ফাইল সিলেক্ট করে আপলোড করলে Google Drive এ যাবে এবং উপরের Image URL ফিল্ডে অটো share link বসে যাবে।')
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) {
                            return;
                        }

                        $file = $state;
                        if (is_array($state)) {
                            $file = $state[0] ?? null;
                        }

                        if (! ($file instanceof TemporaryUploadedFile)) {
                            return;
                        }

                        $shareLink = GoogleDriveUploader::uploadAndGetShareLink($file);
                        $set('image_url', $shareLink);
                    })
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('link')
                    ->label('Link (ক্লিক করলে কোথায় যাবে)')
                    ->url()
                    ->placeholder('https://example.com')
                    ->helperText('Optional: Slider এ click করলে কোথায় redirect হবে'),

                Forms\Components\Select::make('image_position')
                    ->label('Image Position (ছবির পজিশন)')
                    ->options([
                        'top left' => 'Top Left',
                        'top' => 'Top',
                        'top right' => 'Top Right',
                        'center left' => 'Center Left',
                        'center' => 'Center',
                        'center right' => 'Center Right',
                        'bottom left' => 'Bottom Left',
                        'bottom' => 'Bottom',
                        'bottom right' => 'Bottom Right',
                    ])
                    ->default('center')
                    ->helperText('ছবির পজিশন নির্ধারণ করুন, কোনো ক্রপিং হবে না।'),

                Forms\Components\Select::make('image_fit')
                    ->label('Image Fit (কিভাবে ফিট হবে)')
                    ->options([
                        'cover' => 'Cover (পুরো জায়গা জুড়ে - ক্রপ হতে পারে)',
                        'contain' => 'Contain (পুরো ছবি দেখাবে - ফাঁকা থাকতে পারে)',
                        'fill' => 'Fill (টেনে ফিট করবে - চ্যাপ্টা হতে পারে)',
                    ])
                    ->default('contain')
                    ->helperText('Cover: পুরো স্লাইডার জুড়ে থাকবে। Contain: পুরো ছবি দেখা যাবে।'),

                Forms\Components\TextInput::make('order')
                    ->label('Order (ক্রম)')
                    ->numeric()
                    ->default(0)
                    ->helperText('Slider এর প্রদর্শন ক্রম নির্ধারণ করুন। ছোট সংখ্যা আগে দেখাবে।')
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
                Tables\Columns\TextColumn::make('image_url')
                    ->label('Image')
                    ->formatStateUsing(fn(string $state): string => '<img src="' . $state . '" style="height: 50px; width: 80px; object-fit: cover; border-radius: 5px;" referrerpolicy="no-referrer">')
                    ->html(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('link')
                    ->label('Link')
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
