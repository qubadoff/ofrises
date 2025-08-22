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
                            ->options(fn () => Customer::query()
                                ->orderBy('name')
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive(),
                    ])
                    ->columns(1),

                Section::make('Work Areas')
                    ->schema([
                        Select::make('work_area_ids')
                            ->label('Select Work Areas')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                // Hiyerarşik etiket üretimi: "Parent › Child › Leaf"
                                $areas = WorkArea::query()
                                    ->select(['id', 'name', 'parent_id'])
                                    ->get();

                                // İndeksleme
                                $byId = $areas->keyBy('id');
                                // Path label cache
                                $cache = [];

                                $makePath = function ($id) use (&$makePath, $byId, &$cache) {
                                    if (isset($cache[$id])) {
                                        return $cache[$id];
                                    }
                                    $node = $byId[$id] ?? null;
                                    if (!$node) return '';
                                    if (!$node->parent_id) {
                                        return $cache[$id] = $node->name;
                                    }
                                    return $cache[$id] = $makePath($node->parent_id) . ' › ' . $node->name;
                                };

                                return $areas
                                    ->sortBy('name') // isterseniz path’e göre de sıralayabilirsiniz
                                    ->mapWithKeys(fn ($a) => [$a->id => $makePath($a->id)])
                                    ->sort() // path etiketine göre alfabetik
                                    ->all();
                            })
                            // Eğer sadece yaprakların seçilmesini istiyorsanız alttaki filter’i açın:
                            // ->options(function () { return WorkArea::query()->whereDoesntHave('children')->get()->... })
                            ->helperText('Ağaçtaki herhangi bir kategori seçilebilir. (İsterseniz sadece yapraklara sınırlandırabiliriz.)')
                            // İlişkiyi kendimiz yöneteceğiz, bu yüzden form verisi olarak dursun ama otomatik kaydetmesin:
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Select $component, ?Worker $record, Get $get) {
                                // Mevcut kaydı açarken, seçili müşteri için zaten bağlı work_area_id'leri göster.
                                if (!$record) return;
                                $customerId = $get('customer_id');
                                if (!$customerId) return;

                                $ids = $record->workAreas()
                                    ->wherePivot('customer_id', $customerId)
                                    ->pluck('work_areas.id')
                                    ->all();

                                $component->state($ids);
                            })
                            ->saveRelationshipsUsing(function (Worker $record, ?array $state, Get $get) {
                                // Bu closure Select üzerinde çağrılmaz (relationship bağlı değil).
                                // Kaydı "mutateFormDataBeforeSave" veya "afterSave" ile yöneteceğiz.
                            }),
                    ])
                    ->columns(1),
            ])
            // KAYITTAN ÖNCE / SONRA pivot işlemi
            ->mutateFormDataBeforeCreate(function (array $data) {
                // Worker oluşturulurken doğrudan pivot yazamayız (id yok); afterCreate’da yapacağız.
                return $data;
            })
            ->mutateFormDataBeforeSave(function (array $data) {
                // work_area_ids form state'ini korumak için ekliyoruz (dehydrated(false) olduğu için $data’ya girmez)
                return $data;
            })
            ->afterCreate(function (Worker $record, array $data, Get $get) {
                static::syncWorkAreasForCustomer($record, $get('customer_id'), request()->input('data.work_area_ids', []));
            })
            ->afterSave(function (Worker $record, array $data, Get $get) {
                static::syncWorkAreasForCustomer($record, $get('customer_id'), request()->input('data.work_area_ids', []));
            });
    }

// Küçük bir yardımcı: seçilen müşteri için pivot’u senkronla
    protected static function syncWorkAreasForCustomer(Worker $worker, ?int $customerId, array $workAreaIds): void
    {
        $customerId = $customerId ?: auth()->user()?->customer_id;
        $ids = collect($workAreaIds ?? [])->filter()->unique()->values();

        // Aynı müşteri için önceki pivotları temizle
        $worker->workAreas()->wherePivot('customer_id', $customerId)->detach();

        if ($ids->isEmpty()) {
            return;
        }

        // attach payload
        $payload = $ids->mapWithKeys(fn ($id) => [
            $id => ['customer_id' => $customerId],
        ])->all();

        $worker->workAreas()->attach($payload);
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
