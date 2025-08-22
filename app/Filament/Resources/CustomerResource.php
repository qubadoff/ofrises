<?php

namespace App\Filament\Resources;

use App\Enum\Customer\CustomerStatusEnum;
use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('name')->required(),
                    TextInput::make('surname')->required(),
                    TextInput::make('email')->unique('customers', ignoreRecord: true)->email()->required(),
                    TextInput::make('phone')->unique('customers', ignoreRecord: true)->required(),
                    DatePicker::make('birthday')->required(),
                    Select::make('sex')->options([
                        1 => 'Male',
                        2 => 'Female',
                        3 => 'Other',
                    ])->required(),
                    TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->dehydrated(fn ($state) => filled($state))
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->required(fn (Page $livewire) => $livewire instanceof Pages\CreateCustomer)
                        ->afterStateHydrated(fn (TextInput $component, $state) => $component->state('')),
                    TextInput::make('otp_code')->nullable(),
                    DateTimePicker::make('otp_expires_at')->nullable(),
                    DateTimePicker::make('email_verified_at')->nullable(),
                    Select::make('status')->options([
                        CustomerStatusEnum::ACTIVE->value => CustomerStatusEnum::ACTIVE->getLabel(),
                        CustomerStatusEnum::INACTIVE->value => CustomerStatusEnum::INACTIVE->getLabel(),
                        CustomerStatusEnum::PENDING->value => CustomerStatusEnum::PENDING->getLabel(),
                    ])->nullable()->default(CustomerStatusEnum::ACTIVE->value),
                    FileUpload::make('photo')->image()->required(),
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('surname')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
