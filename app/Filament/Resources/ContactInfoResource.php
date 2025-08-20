<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactInfoResource\Pages;
use App\Models\ContactInfo;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactInfoResource extends Resource
{
    protected static ?string $model = ContactInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Support';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('phone')->nullable(),
                    TextInput::make('email')->nullable(),
                    TextInput::make('whatsapp')->nullable(),
                    TextInput::make('telegram')->nullable(),
                    TextInput::make('instagram')->nullable(),
                    TextInput::make('facebook')->nullable(),
                    TextInput::make('tiktok')->nullable(),
                    TextInput::make('website')->nullable(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('whatsapp'),
                Tables\Columns\TextColumn::make('telegram'),
                Tables\Columns\TextColumn::make('instagram'),
                Tables\Columns\TextColumn::make('facebook'),
                Tables\Columns\TextColumn::make('tiktok'),
                Tables\Columns\TextColumn::make('website'),
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
            'index' => Pages\ListContactInfos::route('/'),
            'create' => Pages\CreateContactInfo::route('/create'),
            'edit' => Pages\EditContactInfo::route('/{record}/edit'),
        ];
    }
}
