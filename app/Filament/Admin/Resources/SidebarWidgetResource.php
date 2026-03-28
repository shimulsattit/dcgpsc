<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SidebarWidgetResource\Pages;
use App\Models\SidebarWidget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SidebarWidgetResource extends Resource
{
    protected static ?string $model = SidebarWidget::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $navigationGroup = 'Site Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title (শিরোনাম)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('Type (ধরন)')
                    ->options([
                        'image_link' => 'Image Link (ছবি ও লিংক)',
                        'video' => 'Video Embed (ভিডিও)',
                        'text' => 'Text Content (লেখা)',
                        'calendar' => 'Calendar (ক্যালেন্ডার)',
                        'important_links' => 'Important Links List (সবগুলো লিংক)',
                        'link' => 'Single Link (একটি লিংক)',
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\Textarea::make('image_url')
                    ->label('Image URL (ছবির লিংক)')
                    ->rows(2)
                    ->placeholder('Paste Google Drive share link...')
                    ->helperText('Google Drive লিংক দিন।')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'image_link'),
                Forms\Components\TextInput::make('link')
                    ->label('Link URL (লিংক)')
                    ->url()
                    ->placeholder('https://...')
                    ->visible(fn (Forms\Get $get) => in_array($get('type'), ['image_link', 'link'])),
                Forms\Components\Textarea::make('content')
                    ->label(fn (Forms\Get $get) => $get('type') === 'link' ? 'Link Text (লিংকের লেখা)' : 'Content / Video URL')
                    ->rows(fn (Forms\Get $get) => $get('type') === 'link' ? 2 : 3)
                    ->helperText(fn (Forms\Get $get) => match ($get('type')) {
                        'video' => 'YouTube Embed URL দিন।',
                        'link' => 'লিংকের উপরে যে লেখা দেখাতে চান তা দিন।',
                        default => 'Text হলে লেখা দিন।',
                    })
                    ->visible(fn (Forms\Get $get) => in_array($get('type'), ['video', 'text', 'link'])),
                Forms\Components\TextInput::make('order')
                    ->label('Order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable(),
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
            'index' => Pages\ListSidebarWidgets::route('/'),
            'create' => Pages\CreateSidebarWidget::route('/create'),
            'edit' => Pages\EditSidebarWidget::route('/{record}/edit'),
        ];
    }
}
