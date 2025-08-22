<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkerResource\Pages;
use App\Models\Customer;
use App\Models\WorkArea;
use App\Models\Worker;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
                Section::make('General')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(fn () => Customer::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->dehydrated(false), // Worker tablosuna yazılmayacak, sadece state olarak kullanılacak
                    ]),

                Section::make('Work Areas')
                    ->schema([
                        Select::make('work_area_ids')
                            ->label('Select Work Areas')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->dehydrated(false) // Pivot’u sayfa hook’larında biz yöneteceğiz
                            ->options(function () {
                                $areas = WorkArea::query()
                                    ->select(['id', 'name', 'parent_id'])
                                    ->get();

                                $byId = $areas->keyBy('id');
                                $cache = [];

                                $path = function ($id) use (&$path, $byId, &$cache) {
                                    if (isset($cache[$id])) return $cache[$id];
                                    $n = $byId[$id] ?? null;
                                    if (!$n) return '';
                                    if (!$n->parent_id) return $cache[$id] = $n->name;
                                    return $cache[$id] = $path($n->parent_id) . ' › ' . $n->name;
                                };

                                return $areas
                                    ->mapWithKeys(fn ($a) => [$a->id => $path($a->id)])
                                    ->sort()
                                    ->all();
                            })
                            ->helperText('Ağaçtaki herhangi bir kategori seçilebilir.')
                            ->afterStateHydrated(function (Select $component, ?\App\Models\Worker $record, Get $get) {
                                if (!$record) return;
                                $customerId = $get('customer_id');
                                if (!$customerId) return;

                                $ids = $record->workAreas()
                                    ->wherePivot('customer_id', $customerId)
                                    ->pluck('work_areas.id')
                                    ->all();

                                $component->state($ids);
                            }),
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
