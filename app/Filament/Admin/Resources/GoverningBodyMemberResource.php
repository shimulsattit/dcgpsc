<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GoverningBodyMemberResource\Pages;
use App\Models\GoverningBodyMember;
use App\Services\R2Uploader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class GoverningBodyMemberResource extends Resource
{
    protected static ?string $model = GoverningBodyMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Governing Body';

    protected static ?string $modelLabel = 'Governing Body Member';

    protected static ?string $pluralModelLabel = 'Governing Body Members';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Member Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name (নাম)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('role_id')
                            ->label('Role (পদ)')
                            ->relationship('role', 'name')
                            ->searchable()
                            ->preload()
                            ->default(4)
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Role Name (নাম)')
                                    ->required(),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('name_font_size')
                                            ->label('Name Font Size')
                                            ->options([
                                                '16px' => '16px',
                                                '18px' => '18px',
                                                '20px' => '20px',
                                                '22px' => '22px',
                                                '24px' => '24px',
                                            ])->default('20px'),
                                        Forms\Components\ColorPicker::make('name_color')
                                            ->label('Name Color')
                                            ->default('#2c3e50'),
                                    ]),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('designation_font_size')
                                            ->label('Designation Font Size')
                                            ->options([
                                                '12px' => '12px',
                                                '14px' => '14px',
                                                '16px' => '16px',
                                                '18px' => '18px',
                                            ])->default('16px'),
                                        Forms\Components\ColorPicker::make('designation_color')
                                            ->label('Designation Color')
                                            ->default('#3498db'),
                                    ]),
                                Forms\Components\ColorPicker::make('badge_bg_color')
                                    ->label('Badge Background Color')
                                    ->default('#27ae60'),
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('designation')
                            ->label('Designation (পদবী)')
                            ->maxLength(255)
                            ->placeholder('e.g., Maj Gen Mohammad Asadullah...')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Description (বিবরণ)')
                            ->rows(2)
                            ->placeholder('e.g., GOC, 10 Inf Div & Area Commander, Cox\'s Bazar Area')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Photo')
                    ->schema([
                        Forms\Components\Textarea::make('photo_url')
                            ->label('Photo URL')
                            ->rows(2)
                            ->placeholder('https://...')
                            ->helperText('সরাসরি URL দিন অথবা নিচ থেকে Cloudflare R2-তে ফোটো আপলোড করুন।')
                            ->reactive(),

                        Forms\Components\Placeholder::make('photo_preview')
                            ->label('Photo Preview')
                            ->content(fn(Forms\Get $get) => $get('photo_url')
                                ? new \Illuminate\Support\HtmlString(
                                    '<img src="' . $get('photo_url') . '"
                                     style="max-width: 120px; border-radius: 8px; border: 2px solid #ccc;" referrerpolicy="no-referrer">'
                                )
                                : 'No photo URL provided')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('photo_upload')
                            ->label('Upload Photo to Cloudflare R2')
                            ->image()
                            ->dehydrated(false)
                            ->storeFiles(false)
                            ->helperText('ফোটো সিলেক্ট করলে Cloudflare R2-তে আপলোড হবে এবং উপরের Photo URL ফিল্ডে লিংক বসে যাবে।')
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) return;
                                $file = is_array($state) ? ($state[0] ?? null) : $state;
                                if (! ($file instanceof TemporaryUploadedFile)) return;
                                $url = R2Uploader::uploadAndGetUrl($file, 'governing-body');
                                $set('photo_url', $url);
                            })
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('কম সংখ্যা আগে দেখাবে। Chief Patron = 1, Chairman = 2, Member = 10+'),
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
                Tables\Columns\TextColumn::make('photo_url')
                    ->label('Photo')
                    ->formatStateUsing(fn(?string $state): string => $state
                        ? '<img src="' . $state . '" style="height: 50px; width: 40px; object-fit: cover; border-radius: 4px;" referrerpolicy="no-referrer">'
                        : '<span class="text-gray-400">No photo</span>')
                    ->html(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('role.name')
                    ->label('Role')
                    ->sortable(),

                Tables\Columns\TextColumn::make('designation')
                    ->label('Designation')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->defaultSort('order', 'asc')
            ->filters([])
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoverningBodyMembers::route('/'),
            'create' => Pages\CreateGoverningBodyMember::route('/create'),
            'edit' => Pages\EditGoverningBodyMember::route('/{record}/edit'),
        ];
    }
}
