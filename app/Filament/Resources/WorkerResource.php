<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkerResource\Pages;
use App\Models\Worker;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkerResource extends Resource
{
    protected static ?string $model = Worker::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Section::make('Work Areas')
                        ->schema([
                            Select::make('work_areas')
                            ->label('Work Areas')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->relationship(
                                    name: 'workAreas',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn (Builder $q) => $q->leaves()->orderBy('name')
                                )
                                ->saveRelationshipsUsing(function (Worker $record, ?array $state) {
                                    $customerId = auth()->user()?->customer_id;
                                    $ids = collect($state ?? [])->unique()->values();

                                    $record->workAreas()
                                        ->wherePivot('customer_id', $customerId)
                                        ->detach();

                                    $payload = $ids->mapWithKeys(fn ($id) => [
                                        $id => ['customer_id' => $customerId],
                                    ])->all();

                                    $record->workAreas()->attach($payload);
                                }),
                        ]),
                    ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListWorkers::route('/'),
            'create' => Pages\CreateWorker::route('/create'),
            'edit' => Pages\EditWorker::route('/{record}/edit'),
        ];
    }
}
