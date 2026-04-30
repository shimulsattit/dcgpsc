<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PopupResource\Pages;
use App\Filament\Admin\Resources\PopupResource\RelationManagers;
use App\Models\Popup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PopupResource extends Resource
{
    protected static ?string $model = Popup::class;

    protected static ?string $navigationIcon = 'heroicon-o-window';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Popups';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content (কন্টেন্ট)')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title (শিরোনাম)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('content')
                            ->label('Content (বিস্তারিত)')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'link',
                                'h2',
                                'h3',
                            ]),
                        Forms\Components\Textarea::make('image_url')
                            ->label('Image URL (Image/PDF)')
                            ->rows(2)
                            ->helperText('সরাসরি URL দিন অথবা নিচ থেকে Cloudflare R2-তে ফাইল আপলোড করুন'),
                            
                        Forms\Components\FileUpload::make('image_upload')
                            ->live()
                            ->label('Upload Image to Cloudflare R2')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->dehydrated(false)
                            ->storeFiles(false)
                            ->helperText('ফাইল সিলেক্ট করলে Cloudflare R2-তে আপলোড হবে এবং উপরের Image URL ফিল্ডে লিংক বসে যাবে।')
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) return;
                                $file = is_array($state) ? ($state[0] ?? null) : $state;
                                if (! ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                                $mimeType = $file->getMimeType();
                                $folder = str_contains($mimeType, 'pdf') ? 'popups/documents' : 'popups/images';
                                $url = \App\Services\R2Uploader::uploadAndGetUrl($file, $folder);
                                $set('image_url', $url);
                            })
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Button Settings (বাটন সেটিংস)')
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->label('Button Text (বাটন টেক্সট)')
                            ->maxLength(255)
                            ->placeholder('e.g., Learn More, Apply Now'),
                        Forms\Components\TextInput::make('button_link')
                            ->label('Button Link (বাটন লিংক)')
                            ->helperText('Optional: Add a link for the button. সরাসরি URL দিন অথবা ফাইল আপলোড করুন।'),
                            
                        Forms\Components\FileUpload::make('button_link_upload')
                            ->label('Upload File for Button Link to Cloudflare R2')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->dehydrated(false)
                            ->storeFiles(false)
                            ->helperText('ফাইল সিলেক্ট করলে Cloudflare R2-তে আপলোড হবে এবং উপরের Button Link ফিল্ডে লিংক বসে যাবে।')
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) return;
                                $file = is_array($state) ? ($state[0] ?? null) : $state;
                                if (! ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                                $mimeType = $file->getMimeType();
                                $folder = str_contains($mimeType, 'pdf') ? 'popups/documents' : 'popups/images';
                                $url = \App\Services\R2Uploader::uploadAndGetUrl($file, $folder);
                                $set('button_link', $url);
                            })
                            ->columnSpanFull(),
                        Forms\Components\ColorPicker::make('button_bg_color')
                            ->label('Button Background Color')
                            ->default('#006a4e')
                            ->helperText('Background color for the button'),
                        Forms\Components\ColorPicker::make('button_text_color')
                            ->label('Button Text Color')
                            ->default('#ffffff')
                            ->helperText('Text color for the button'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Display Settings (ডিসপ্লে সেটিংস)')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active (সক্রিয়)')
                            ->default(false)
                            ->helperText('Enable to show this popup on the website'),
                        Forms\Components\Select::make('display_on')
                            ->label('Display On (কোথায় দেখাবে)')
                            ->options([
                                'homepage' => 'Homepage Only',
                                'all_pages' => 'All Pages',
                            ])
                            ->default('homepage')
                            ->required(),
                        Forms\Components\Toggle::make('show_once_per_session')
                            ->label('Show Once Per Session')
                            ->default(true)
                            ->helperText('If enabled, popup will show only once per browser session'),
                        Forms\Components\TextInput::make('auto_close_seconds')
                            ->label('Auto Close (seconds)')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('e.g., 10')
                            ->helperText('Optional: Auto-close popup after X seconds'),
                        Forms\Components\TextInput::make('priority')
                            ->label('Priority (অগ্রাধিকার)')
                            ->numeric()
                            ->default(0)
                            ->helperText('Higher number = higher priority (if multiple popups are active)'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('display_on')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'homepage' => 'info',
                        'all_pages' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('priority')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('priority', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All popups')
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
            'index' => Pages\ListPopups::route('/'),
            'create' => Pages\CreatePopup::route('/create'),
            'edit' => Pages\EditPopup::route('/{record}/edit'),
        ];
    }
}
