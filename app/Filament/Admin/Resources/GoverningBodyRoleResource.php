<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GoverningBodyRoleResource\Pages;
use App\Models\GoverningBodyRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GoverningBodyRoleResource extends Resource
{
    protected static ?string $model = GoverningBodyRole::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationLabel = 'Governing Roles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Role Styling (রোল ডিজাইন)')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Role Name (নাম)')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('name_font_size')
                                    ->label('Name Font Size (নামের সাইজ)')
                                    ->options([
                                        '14px' => '14px',
                                        '16px' => '16px',
                                        '18px' => '18px',
                                        '20px' => '20px',
                                        '22px' => '22px',
                                        '24px' => '24px',
                                        '28px' => '28px',
                                    ])->default('20px'),
                                Forms\Components\ColorPicker::make('name_color')
                                    ->label('Name Color (নামের কালার)')
                                    ->default('#2c3e50'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('designation_font_size')
                                    ->label('Designation Font Size (পদবীর সাইজ)')
                                    ->options([
                                        '12px' => '12px',
                                        '14px' => '14px',
                                        '16px' => '16px',
                                        '18px' => '18px',
                                        '20px' => '20px',
                                    ])->default('16px'),
                                Forms\Components\ColorPicker::make('designation_color')
                                    ->label('Designation Color (পদবীর কালার)')
                                    ->default('#3498db'),
                            ]),

                        Forms\Components\ColorPicker::make('badge_bg_color')
                            ->label('Badge Background Color (ব্যাজ ব্যাকগ্রাউন্ড কালার)')
                            ->default('#27ae60')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Role Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ColorColumn::make('badge_bg_color')
                    ->label('Badge Color'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoverningBodyRoles::route('/'),
            'create' => Pages\CreateGoverningBodyRole::route('/create'),
            'edit' => Pages\EditGoverningBodyRole::route('/{record}/edit'),
        ];
    }
}
