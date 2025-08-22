<?php

namespace App\Filament\Resources\WorkerResource\Pages;

use App\Filament\Resources\WorkerResource;
use App\Models\Worker;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWorker extends CreateRecord
{
    protected static string $resource = WorkerResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        $this->syncWorkAreasForCustomer(
            $this->record,
            (int) ($data['customer_id'] ?? 0),
            (array) ($data['work_area_ids_hidden'] ?? [])
        );
    }

    private function syncWorkAreasForCustomer(Worker $worker, int $customerId, array $workAreaIds): void
    {
        $ids = collect($workAreaIds)->filter()->unique()->values();

        $worker->workAreas()->wherePivot('customer_id', $customerId)->detach();

        if ($ids->isEmpty()) {
            return;
        }

        $payload = $ids->mapWithKeys(fn ($id) => [
            $id => ['customer_id' => $customerId],
        ])->all();

        $worker->workAreas()->attach($payload);
    }
}
