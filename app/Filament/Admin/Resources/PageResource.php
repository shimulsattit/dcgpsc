<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PageResource\Pages;
use App\Models\Page;
use App\Models\Menu;
use App\Services\GoogleDriveUploader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Page Title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, Forms\Set $set) => $set('slug', Str::slug($state)))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from title. Edit if needed.')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('image_url')
                            ->label('Top Image (Google Drive Link)')
                            ->helperText('Paste a Google Drive image sharing link to show at the top of the page.')
                            ->placeholder('https://drive.google.com/file/d/...')
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image_upload')
                            ->label('Upload Image to Google Drive')
                            ->image()
                            ->dehydrated(false)
                            ->storeFiles(false)
                            ->helperText('ছবি সিলেক্ট করলে Google Drive-এ আপলোড হবে এবং উপরের Image URL ফিল্ডে লিংক বসে যাবে।')
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) return;
                                $file = is_array($state) ? ($state[0] ?? null) : $state;
                                if (! ($file instanceof TemporaryUploadedFile)) return;
                                $shareLink = GoogleDriveUploader::uploadAndGetShareLink($file);
                                $set('image_url', $shareLink);
                            })
                            ->columnSpanFull(),

                        Forms\Components\Select::make('menu_id')
                            ->label('Assign to Menu')
                            ->options(Menu::all()->pluck('title', 'id'))
                            ->searchable()
                            ->nullable()
                            ->helperText('Optional: Link this page to a menu item'),
                    ])->columns(2),

                Forms\Components\Section::make('Page Content')
                    ->schema([
                        Forms\Components\MarkdownEditor::make('content')
                            ->label('Page Content')
                            ->required()
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',
                                'italic',
                                'strike',
                                'link',
                                'heading',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'codeBlock',
                                'table',
                                'undo',
                                'redo',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('page-images')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull()
                            ->helperText('Use the editor to format your page content. Click the paperclip icon to upload images. Supports Bangla typing.'),

                        Forms\Components\Textarea::make('html_content')
                            ->label('Additional HTML Content (Optional)')
                            ->rows(10)
                            ->columnSpanFull()
                            ->helperText('Paste HTML code here for tables, text alignment, or other advanced formatting. This will be displayed after the main content. 
                            
Examples:
• Left align: <div style="text-align: left;">Your text</div>
• Center align: <div style="text-align: center;">Your text</div>
• Right align: <div style="text-align: right;">Your text</div>')
                            ->placeholder('<table>...</table> or <div style="text-align: center;">Centered text</div>'),
                    ]),

                Forms\Components\Section::make('Button Builder')
                    ->description('Add custom buttons to your page')
                    ->schema([
                        Forms\Components\Repeater::make('buttons')
                            ->label('Buttons')
                            ->schema([
                                Forms\Components\TextInput::make('text')
                                    ->label('Button Text')
                                    ->required()
                                    ->placeholder('e.g., Download PDF, Learn More'),

                                Forms\Components\TextInput::make('url')
                                    ->label('Button URL')
                                    ->required()
                                    ->url()
                                    ->placeholder('https://example.com or /page-slug'),

                                Forms\Components\Select::make('color')
                                    ->label('Button Color')
                                    ->options([
                                        'primary' => 'Primary (Blue)',
                                        'success' => 'Success (Green)',
                                        'danger' => 'Danger (Red)',
                                        'warning' => 'Warning (Yellow)',
                                        'info' => 'Info (Cyan)',
                                        'secondary' => 'Secondary (Gray)',
                                        'dark' => 'Dark (Black)',
                                    ])
                                    ->default('primary')
                                    ->required(),

                                Forms\Components\Select::make('icon')
                                    ->label('Icon (Optional)')
                                    ->options([
                                        '' => 'No Icon',
                                        'fa-download' => 'Download',
                                        'fa-file-pdf' => 'PDF File',
                                        'fa-external-link-alt' => 'External Link',
                                        'fa-link' => 'Link',
                                        'fa-arrow-right' => 'Arrow Right',
                                        'fa-info-circle' => 'Info',
                                        'fa-envelope' => 'Email',
                                        'fa-phone' => 'Phone',
                                        'fa-play' => 'Play',
                                        'fa-book' => 'Book',
                                    ])
                                    ->searchable(),

                                Forms\Components\Toggle::make('open_new_tab')
                                    ->label('Open in New Tab')
                                    ->default(false),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Button')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['text'] ?? 'New Button'),
                    ])
                    ->collapsed(),

                Forms\Components\Section::make('Bottom Content Area')
                    ->description('Add content that will appear below the buttons')
                    ->schema([
                        Forms\Components\MarkdownEditor::make('bottom_content')
                            ->label('Bottom Content')
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',
                                'italic',
                                'strike',
                                'link',
                                'heading',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('page-images')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull()
                            ->helperText('This content will appear after the buttons. Click the paperclip icon to upload images. Useful for disclaimers, additional notes, or contact information.'),
                    ])
                    ->collapsed(),

                Forms\Components\Section::make('SEO Settings')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('Recommended: 50-60 characters'),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(160)
                            ->rows(3)
                            ->helperText('Recommended: 150-160 characters'),
                    ])->columns(1)->collapsed(),

                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(false)
                            ->helperText('Make this page visible to the public'),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->default(now())
                            ->helperText('Schedule publication date'),
                    ])->columns(2),

                Forms\Components\Section::make('Notice Expiry Settings')
                    ->schema([
                        Forms\Components\Placeholder::make('notice_info')
                            ->label('')
                            ->content('This page is linked to a notice. Set expiry date below.'),
                        Forms\Components\TextInput::make('notice_expiry_days')
                            ->label('Expire After (Days)')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Notice will auto-delete after this many days from publish date')
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get, $record) {
                                if ($state && $record && $record->notices()->exists()) {
                                    $notice = $record->notices()->first();
                                    $publishedAt = $notice->published_at;
                                    $expiresAt = $publishedAt->copy()->addDays($state);
                                    $set('notice_expires_at', $expiresAt->format('Y-m-d H:i:s'));
                                }
                            }),
                        Forms\Components\DateTimePicker::make('notice_expires_at')
                            ->label('Notice Expires At')
                            ->helperText('Set expiry days above or manually select date')
                            ->afterStateUpdated(function ($state, $record) {
                                if ($record && $record->notices()->exists()) {
                                    $notice = $record->notices()->first();
                                    $notice->expires_at = $state;
                                    $notice->save();
                                }
                            }),
                    ])
                    ->visible(fn($record) => $record && $record->notices()->exists())
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copied!')
                    ->limit(30),
                Tables\Columns\TextColumn::make('menu.title')
                    ->label('Menu')
                    ->badge()
                    ->default('Not assigned'),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published')
                    ->placeholder('All pages')
                    ->trueLabel('Published only')
                    ->falseLabel('Unpublished only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Page $record): string => url('/' . $record->slug))
                    ->openUrlInNewTab()
                    ->visible(fn(Page $record): bool => $record->is_published),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
