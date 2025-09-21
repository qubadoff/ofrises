<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationGroup = 'Company';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('company_type')->relationship('companyType', 'name')->required(),
                    TextInput::make('name')->required(),
                    Select::make('work_area_id')->relationship('workArea', 'name')->required(),
                    DatePicker::make('created_date')->required(),
                    TextInput::make('location')->required(),
                    TextInput::make('latitude')->required(),
                    TextInput::make('longitude')->required(),
                    TextInput::make('phone')->required(),
                    TextInput::make('email')->email()->required(),
                    TextInput::make('employee_count')->numeric()->required(),
                ])->columns(5),
                Section::make([
                    FileUpload::make('profile_photo')->image()->required()->openable()->downloadable(),
                    FileUpload::make('media')->multiple()->required()->openable()->downloadable(),
                ])->columns(),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('customer.name')->label('Customer Name')->searchable(),
                TextColumn::make('customer.surname')->label('Customer Surname')->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('workArea.name')->label('Work Area')->searchable(),
                TextColumn::make('created_date')->sortable()->date(),
                TextColumn::make('location')->label('Location')->searchable(),
                TextColumn::make('phone')->label('Phone')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('employee_count')->label('Employee count')->sortable(),
                ImageColumn::make('profile_photo')->label('Profile Photo'),
                TextColumn::make('status')->label('Status')->badge(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make('deleted_at'),
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $modelClass = static::$model;

        return (string) $modelClass::count();
    }
}
