<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenuResource\Pages;
use App\Filament\Admin\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Menu Item Details')
                    ->schema([
                        Forms\Components\Radio::make('link_source')
                            ->label('Link Source')
                            ->options([
                                'page' => 'Page',
                                'system' => 'System Route (Notice, Gallery, etc.)',
                                'custom' => 'Custom URL',
                            ])
                            ->default('page')
                            ->reactive()
                            ->afterStateHydrated(function ($state, $record, Forms\Set $set) {
                                if ($record && !$record->page_id) {
                                    $systemRoutes = ['/', '/notices', '/gallery', '/video-gallery', '/governing-body', '/achievements', '/news'];
                                    if (in_array($record->url, $systemRoutes)) {
                                        $set('link_source', 'system');
                                        $set('system_route', $record->url);
                                    } else {
                                        $set('link_source', 'custom');
                                    }
                                }
                            })
                            ->afterStateUpdated(fn($state, Forms\Set $set) => $state === 'page' ? $set('url', null) : $set('page_id', null)),

                        Forms\Components\Select::make('system_route')
                            ->label('Select System Route')
                            ->options([
                                '/' => 'Home',
                                '/notices' => 'Notices/Notice Board',
                                '/gallery' => 'Photo Gallery',
                                '/video-gallery' => 'Video Gallery',
                                '/governing-body' => 'Governing Body',
                                '/achievements' => 'Achievements',
                                '/news' => 'News & Events',
                            ])
                            ->visible(fn(Forms\Get $get) => $get('link_source') === 'system')
                            ->reactive()
                            ->afterStateHydrated(function ($state, $record, Forms\Set $set) {
                                if ($record && !$record->page_id) {
                                    $systemRoutes = ['/', '/notices', '/gallery', '/video-gallery', '/governing-body', '/achievements', '/news'];
                                    if (in_array($record->url, $systemRoutes)) {
                                        $set('system_route', $record->url);
                                    }
                                }
                            })
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state) {
                                    $set('url', $state);
                                    if (!$get('title')) {
                                        $options = [
                                            '/' => 'Home',
                                            '/notices' => 'Notice',
                                            '/gallery' => 'Gallery',
                                            '/video-gallery' => 'Video Gallery',
                                            '/governing-body' => 'Governing Body',
                                            '/achievements' => 'Achievements',
                                            '/news' => 'News',
                                        ];
                                        $set('title', $options[$state] ?? '');
                                    }
                                }
                            }),

                        Forms\Components\Select::make('page_id')
                            ->label('Select Page')
                            ->options(\App\Models\Page::where('is_published', true)->pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->visible(fn(Forms\Get $get) => $get('link_source') === 'page')
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state && !$get('title')) {
                                    $page = \App\Models\Page::find($state);
                                    if ($page) {
                                        $set('title', $page->title);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->placeholder('e.g., https://... or #')
                            ->visible(fn(Forms\Get $get) => $get('link_source') !== 'page')
                            ->required(fn(Forms\Get $get) => $get('link_source') === 'custom'),

                        Forms\Components\TextInput::make('title')
                            ->label('Menu Title (Custom Name)')
                            ->required()
                            ->maxLength(255)
                            ->helperText('This text will appear in the navigation menu. You can customize it.'),

                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Menu')
                            ->options(function (?Menu $record) {
                                $query = Menu::query();
                                if ($record) {
                                    $query->where('id', '!=', $record->id);
                                }
                                return $query->pluck('title', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('None (Top Level)')
                            ->helperText('Leave empty for Main Menu (Top Level)'),

                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(1),

                Forms\Components\Section::make('Highlight Settings')
                    ->description('Make this menu stand out with custom highlighting')
                    ->schema([
                        Forms\Components\Toggle::make('is_highlighted')
                            ->label('Mark as Important/Highlighted')
                            ->helperText('Enable this to highlight this menu item in the navigation')
                            ->reactive()
                            ->default(false),

                        Forms\Components\ColorPicker::make('highlight_color')
                            ->label('Highlight Background Color')
                            ->helperText('Choose a background color for the highlighted menu. Suggested: Red (#dc3545) for urgent, Orange (#fd7e14) for important, Green (#28a745) for new items')
                            ->visible(fn(Forms\Get $get) => $get('is_highlighted'))
                            ->default('#dc3545'),
                    ])->columns(1)->collapsed(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->limit(30),
                Tables\Columns\TextColumn::make('parent.title')
                    ->label('Parent')
                    ->default('—'),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_highlighted')
                    ->label('Highlighted')
                    ->boolean(),
                Tables\Columns\ColorColumn::make('highlight_color')
                    ->label('Color')
                    ->default('—'),

            ])
            ->defaultSort('order')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
