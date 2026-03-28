<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MessageResource\Pages;
use App\Filament\Admin\Resources\MessageResource\RelationManagers;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Type (ধরন)')
                            ->options([
                                'Founder' => 'Founder',
                                'Chief Patron' => 'Chief Patron',
                                'Chairman' => 'Chairman',
                                'Principal' => 'Principal',
                                'Vice Principal' => 'Vice Principal',
                                'Headmaster' => 'Headmaster',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Name (নাম)')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                        Forms\Components\TextInput::make('title')
                            ->label('Message Title (মেসেজ টাইটেল)')
                            ->maxLength(255)
                            ->placeholder('e.g., Message of the Chairman')
                            ->helperText('নেম কার্ডের উপরে প্রদর্শিত টাইটেল'),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL-friendly version of name. Auto-generated from name.'),
                        Forms\Components\TextInput::make('designation')
                            ->label('Designation (পদবী)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('image_url')
                            ->label('Image URL (ছবির লিংক)')
                            ->rows(2)
                            ->placeholder('Paste Google Drive share link...')
                            ->helperText('Google Drive লিংক দিন।')
                            ->reactive(),
                        Forms\Components\Placeholder::make('image_preview')
                            ->label('Image Preview')
                            ->content(fn(Forms\Get $get) => $get('image_url') ? new \Illuminate\Support\HtmlString('<img src="' . \App\Helpers\GoogleDriveHelper::getDirectUrl($get('image_url')) . '" style="max-width: 150px; border-radius: 10px; border: 2px solid #ccc;" referrerpolicy="no-referrer">') : 'No image URL provided')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('message')
                            ->label('Message (বাণী)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('image_url')
                    ->label('Image')
                    ->formatStateUsing(fn(string $state): string => '<img src="' . $state . '" style="height: 50px; width: 50px; object-fit: cover; border-radius: 50%;" referrerpolicy="no-referrer">')
                    ->html(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('designation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Is active'),
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
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}
