<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverLicenseResource\Pages;
use App\Models\DriverLicense;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DriverLicenseResource extends Resource
{
    protected static ?string $model = DriverLicense::class;

    protected static ?string $navigationGroup = 'Workers';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('id')->sortable(),
                TextColumn::make('name')->label('name')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListDriverLicenses::route('/'),
            'create' => Pages\CreateDriverLicense::route('/create'),
            'edit' => Pages\EditDriverLicense::route('/{record}/edit'),
        ];
    }
}
