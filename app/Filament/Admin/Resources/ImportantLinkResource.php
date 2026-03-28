<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ImportantLinkResource\Pages;
use App\Models\ImportantLink;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ImportantLinkResource extends Resource
{
    protected static ?string $model = ImportantLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationLabel = 'Important Links';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Link Settings')
                    ->schema([
                        Forms\Components\Radio::make('link_source')
                            ->label('লিংকের ধরন')
                            ->options([
                                'menu' => 'Existing Menu (মেনু থেকে)',
                                'custom' => 'Custom Link (নিজে লিখুন)',
                            ])
                            ->default('menu')
                            ->inline()
                            ->dehydrated(false)
                            ->reactive()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('menu_id')
                            ->label('Menu (মেনু সিলেক্ট করুন)')
                            ->relationship('menu', 'title')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get) => $get('link_source') !== 'custom')
                            ->required(fn (Forms\Get $get) => $get('link_source') !== 'custom')
                            ->helperText('যে মেনু গুলোকে Important Links-এ দেখাতে চান, শুধু সেগুলো সিলেক্ট করুন।'),

                        Forms\Components\TextInput::make('title')
                            ->label('Title (শিরোনাম)')
                            ->visible(fn (Forms\Get $get) => $get('link_source') === 'custom')
                            ->required(fn (Forms\Get $get) => $get('link_source') === 'custom')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('url')
                            ->label('URL (লিংক)')
                            ->visible(fn (Forms\Get $get) => $get('link_source') === 'custom')
                            ->required(fn (Forms\Get $get) => $get('link_source') === 'custom')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(30),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
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
            'index' => Pages\ListImportantLinks::route('/'),
            'create' => Pages\CreateImportantLink::route('/create'),
            'edit' => Pages\EditImportantLink::route('/{record}/edit'),
        ];
    }
}
