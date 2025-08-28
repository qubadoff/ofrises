<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EducationTypeResource\Pages;
use App\Models\EducationType;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EducationTypeResource extends Resource
{

    use Translatable;

    protected static ?string $model = EducationType::class;

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
                TextColumn::make('name')->label('Name')->searchable(),
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
            'index' => Pages\ListEducationTypes::route('/'),
            'create' => Pages\CreateEducationType::route('/create'),
            'edit' => Pages\EditEducationType::route('/{record}/edit'),
        ];
    }
}
