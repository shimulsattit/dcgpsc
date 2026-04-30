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
                    ->placeholder('সরাসরি URL দিন অথবা ফাইল আপলোড করুন...')
                    ->helperText('সরাসরি URL দিন অথবা নিচ থেকে Cloudflare R2-তে ফাইল আপলোড করুন।')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'image_link'),
                    
                Forms\Components\FileUpload::make('image_upload')
                    ->live()
                    ->label('Upload Image to Cloudflare R2')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->dehydrated(false)
                    ->storeFiles(false)
                    ->visible(fn (Forms\Get $get) => $get('type') === 'image_link')
                    ->helperText('ফাইল সিলেক্ট করলে Cloudflare R2-তে আপলোড হবে এবং উপরের Image URL ফিল্ডে লিংক বসে যাবে।')
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) return;
                        $file = is_array($state) ? ($state[0] ?? null) : $state;
                        if (! ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                        $mimeType = $file->getMimeType();
                        $folder = str_contains($mimeType, 'pdf') ? 'widgets/documents' : 'widgets/images';
                        $url = \App\Services\R2Uploader::uploadAndGetUrl($file, $folder);
                        $set('image_url', $url);
                    })
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('link')
                    ->label('Link URL (লিংক)')
                    ->placeholder('https://...')
                    ->helperText('সরাসরি URL দিন অথবা ফাইল আপলোড করুন।')
                    ->visible(fn (Forms\Get $get) => in_array($get('type'), ['image_link', 'link'])),
                    
                Forms\Components\FileUpload::make('link_upload')
                    ->label('Upload File for Link to Cloudflare R2')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->dehydrated(false)
                    ->storeFiles(false)
                    ->visible(fn (Forms\Get $get) => in_array($get('type'), ['image_link', 'link']))
                    ->helperText('ফাইল সিলেক্ট করলে Cloudflare R2-তে আপলোড হবে এবং উপরের Link URL ফিল্ডে লিংক বসে যাবে।')
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) return;
                        $file = is_array($state) ? ($state[0] ?? null) : $state;
                        if (! ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                        $mimeType = $file->getMimeType();
                        $folder = str_contains($mimeType, 'pdf') ? 'widgets/documents' : 'widgets/images';
                        $url = \App\Services\R2Uploader::uploadAndGetUrl($file, $folder);
                        $set('link', $url);
                    })
                    ->columnSpanFull(),
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
