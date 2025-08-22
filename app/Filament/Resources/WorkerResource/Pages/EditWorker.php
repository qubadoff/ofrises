<?php

namespace App\Filament\Resources\WorkerResource\Pages;

use App\Filament\Resources\WorkerResource;
use App\Models\Worker;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorker extends EditRecord
{
    protected static string $resource = WorkerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $data = $this->form->getState();
        $this->syncWorkAreasForCustomer($this->record, $data['customer_id'] ?? null, $data['work_area_ids'] ?? []);
    }

    private function syncWorkAreasForCustomer(Worker $worker, ?int $customerId, array $workAreaIds): void
    {
        $customerId = $customerId ?: auth()->user()?->customer_id;
        $ids = collect($workAreaIds ?? [])->filter()->unique()->values();

        $worker->workAreas()->wherePivot('customer_id', $customerId)->detach();

        if ($ids->isEmpty()) {
            return;
        }

        $payload = $ids->mapWithKeys(fn($id) => [
            $id => ['customer_id' => $customerId],
        ])->all();

        $worker->workAreas()->attach($payload);
    }
}
