<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventLog extends Model
{
    protected $fillable = [
        'event_id',
        'log_date',
        'title',
        'description',
        'image_path',
        'sort_order',
    ];

    protected $casts = [
        'log_date'   => 'date',
        'sort_order' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getDetailDateAttribute()
    {
        return $this->log_date;
    }

    public function setDetailDateAttribute($value): void
    {
        $this->attributes['log_date'] = $value;
    }
}
