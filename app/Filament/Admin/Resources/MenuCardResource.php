<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenuCardResource\Pages;
use App\Models\MenuCard;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\R2Uploader;

class MenuCardResource extends Resource
{
    protected static ?string $model = MenuCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Menu Cards';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Card Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Card Title')
                            ->required()
                            ->placeholder('e.g., About, Academic Info')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('icon_url')
                            ->label('Icon URL')
                            ->placeholder('https://...')
                            ->helperText('সরাসরি URL দিন অথবা নিচ থেকে Cloudflare R2-তে আপলোড করুন।'),
                        Forms\Components\FileUpload::make('icon_upload_handler')
                            ->label('Upload Icon to Cloudflare R2')
                            ->image()
                            ->dehydrated(false)
                            ->storeFiles(false)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if (!$state) return;
                                $file = is_array($state) ? ($state[0] ?? null) : $state;
                                if (!($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                                $url = R2Uploader::uploadAndGetUrl($file, 'menu-cards/icons');
                                if ($url) {
                                    $set('icon_url', $url);
                                }
                            }),
                        Forms\Components\ColorPicker::make('bg_color')
                            ->label('Background Color')
                            ->default('#0a4d3c')
                            ->helperText('Note: Currently not used, cards use primary theme color'),
                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Menu Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Item Title')
                                    ->required()
                                    ->placeholder('e.g., About Institute, Syllabus'),
                                Forms\Components\TextInput::make('url')
                                    ->label('Custom URL')
                                    ->url()
                                    ->placeholder('https://...')
                                    ->helperText('Leave empty if linking to a page'),
                                Forms\Components\Select::make('page_id')
                                    ->label('Or Link to Page')
                                    ->options(Page::all()->pluck('title', 'id'))
                                    ->searchable()
                                    ->helperText('Select a page instead of custom URL'),
                                Forms\Components\TextInput::make('order')
                                    ->label('Order')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Menu Item')
                            ->reorderable()
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('bg_color')
                    ->label('Color'),
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All cards')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
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
            'index' => Pages\ListMenuCards::route('/'),
            'create' => Pages\CreateMenuCard::route('/create'),
            'edit' => Pages\EditMenuCard::route('/{record}/edit'),
        ];
    }
}
