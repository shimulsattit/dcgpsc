<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SettingResource\Pages;
use App\Filament\Admin\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('Setting Key')
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn ($record) => $record !== null) // Disable key editing for existing records
                    ->helperText('Common keys: logo, site_name, email, phone, facebook, youtube'),
                Forms\Components\Textarea::make('value')
                    ->label('Setting Value')
                    ->rows(3)
                    ->helperText('For images (logo, etc.), you can provide a direct URL or upload below.'),
                    
                Forms\Components\FileUpload::make('value_upload')
                    ->label('Upload File to Cloudflare R2')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->dehydrated(false)
                    ->storeFiles(false)
                    ->helperText('ফাইল সিলেক্ট করলে Cloudflare R2-তে আপলোড হবে এবং উপরের Setting Value ফিল্ডে লিংক বসে যাবে।')
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) return;
                        $file = is_array($state) ? ($state[0] ?? null) : $state;
                        if (! ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) return;
                        $mimeType = $file->getMimeType();
                        $folder = str_contains($mimeType, 'pdf') ? 'settings/documents' : 'settings/images';
                        $url = \App\Services\R2Uploader::uploadAndGetUrl($file, $folder);
                        $set('value', $url);
                    })
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->limit(50),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
