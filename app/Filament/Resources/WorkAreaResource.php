<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkAreaResource\Pages;
use App\Models\WorkArea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkAreaResource extends Resource
{
    protected static ?string $model = WorkArea::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('parent_id')
                        ->label('Parent Area')
                        ->relationship('parent', 'name')
                        ->searchable()
                        ->nullable()
                        ->preload(),
                    TextInput::make('name')->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('id'),
                TextColumn::make('name')->label('name'),
                TextColumn::make('parent.name')->label('Parent Name'),
                TextColumn::make('created_at')->label('created_at'),
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
            ])->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListWorkAreas::route('/'),
            'create' => Pages\CreateWorkArea::route('/create'),
            'edit' => Pages\EditWorkArea::route('/{record}/edit'),
        ];
    }
}
