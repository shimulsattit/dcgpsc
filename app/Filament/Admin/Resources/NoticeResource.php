<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NoticeResource\Pages;
use App\Filament\Admin\Resources\NoticeResource\RelationManagers;
use App\Models\Notice;
use App\Services\R2Uploader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class NoticeResource extends Resource
{
    protected static ?string $model = Notice::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title (শিরোনাম)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('content')
                    ->label('Content (বিস্তারিত)')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('file')
                    ->label('File URL (ফাইল লিংক)')
                    ->rows(2)
                    ->placeholder('https://...')
                    ->helperText('সরাসরি URL দিন অথবা নিচ থেকে Cloudflare R2-তে আপলোড করুন'),

                Forms\Components\FileUpload::make('file_upload')
                    ->label('Upload File to Cloudflare R2 (PDF/Image)')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->dehydrated(false)
                    ->storeFiles(false)
                    ->helperText('ফাইল সিলেক্ট করলে Cloudflare R2-তে আপলোড হবে এবং উপরের File URL ফিল্ডে লিংক বসে যাবে।')
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) return;
                        $file = is_array($state) ? ($state[0] ?? null) : $state;
                        if (! ($file instanceof TemporaryUploadedFile)) return;
                        $url = R2Uploader::uploadAndGetUrl($file, 'notices');
                        $set('file', $url);
                    })
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('link')
                    ->label('Notice Link (নোটিশ লিংক)')
                    ->url()
                    ->placeholder('https://...')
                    ->helperText('Optional: External link for this notice'),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Published Date')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('expiry_days')
                    ->label('Expire After (Days)')
                    ->numeric()
                    ->minValue(1)
                    ->helperText('Notice will auto-delete after this many days from publish date')
                    ->dehydrated(false)
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        if ($state && $get('published_at')) {
                            $publishedAt = \Carbon\Carbon::parse($get('published_at'));
                            $expiresAt = $publishedAt->copy()->addDays($state);
                            $set('expires_at', $expiresAt->format('Y-m-d H:i:s'));
                        }
                    }),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Expires At')
                    ->helperText('Set expiry days above or manually select date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable()
                    ->color(fn($record) => $record->expires_at && $record->expires_at->isPast() ? 'danger' : 'success')
                    ->badge()
                    ->placeholder('Never')
                    ->formatStateUsing(fn($state) => $state ? $state->format('M d, Y H:i') : 'Never'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Notice'),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete Notice')
                    ->modalDescription('Are you sure you want to delete this notice? This will also delete the associated page.')
                    ->successNotificationTitle('Notice deleted')
                    ->after(function ($record) {
                        // Delete associated page if exists
                        if ($record->page) {
                            $record->page->delete();
                        }
                    }),
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
            'index' => Pages\ListNotices::route('/'),
            'create' => Pages\CreateNotice::route('/create'),
            'edit' => Pages\EditNotice::route('/{record}/edit'),
        ];
    }
}
