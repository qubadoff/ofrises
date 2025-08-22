<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WorkerPhotoAndVideo extends Model
{
    protected $table = 'worker_photo_and_videos';

    protected $guarded = ['id'];

    protected $casts = [
        'photos' => 'array',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected function photos(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? json_decode($value, true) : [],
            set: fn ($value) => json_encode(
                array_values($value ?? []),
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
        );
    }
}
