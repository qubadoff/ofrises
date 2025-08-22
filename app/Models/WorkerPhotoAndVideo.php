<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerPhotoAndVideo extends Model
{
    protected $table = 'worker_photo_and_videos';

    protected $guarded = ['id'];

    protected $casts = [
        'photos' => 'array',
    ];


    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}
