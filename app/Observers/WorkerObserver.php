<?php

namespace App\Observers;

namespace App\Observers;

use App\Models\Worker;
use App\Models\WorkerQRCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class WorkerObserver
{
    /**
     * Handle the Worker "created" event.
     */
    public function created(Worker $worker): void
    {
        $qrContent = (string) $worker->id;

        $qrImage = QrCode::format('png')->size(300)->generate($qrContent);

        $fileName = "qrCodes/worker_{$worker->id}.png";

        Storage::disk('public')->put($fileName, $qrImage);

        WorkerQRCode::query()->create([
            'worker_id'     => $worker->id,
            'qr_code'       => $qrContent,
            'qr_code_image' => $fileName,
        ]);
    }

    /**
     * Handle the Worker "updated" event.
     */
    public function updated(Worker $worker): void
    {
        //
    }

    /**
     * Handle the Worker "deleted" event.
     */
    public function deleted(Worker $worker): void
    {
        //
    }

    /**
     * Handle the Worker "restored" event.
     */
    public function restored(Worker $worker): void
    {
        //
    }

    /**
     * Handle the Worker "force deleted" event.
     */
    public function forceDeleted(Worker $worker): void
    {
        //
    }
}
