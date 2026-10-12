<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'badge_label',
        'event_date',
        'end_date',
        'start_time',
        'end_time',
        'venue',
        'venue_type',
        'summary',
        'image_url',
        'description',
        'registration_url',
        'is_featured',
        'status',
        'approval_status',
        'director_comments',
        'reviewed_by',
        'approved_by',
        'reviewed_at',
        'approved_at',
        'news_id',
        'enable_view_link',
    ];

    protected function casts(): array
    {
        return [
            'is_featured'      => 'boolean',
            'enable_view_link' => 'boolean',
            'event_date'       => 'date',
            'end_date'         => 'date',
            'reviewed_at'      => 'datetime',
            'approved_at'      => 'datetime',
            'news_id'          => 'integer',
        ];
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * The optional related News article for this event.
     */
    public function relatedNews(): BelongsTo
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    /**
     * Daily activity log entries for this event.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(EventLog::class)->orderBy('log_date', 'asc')->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    public function dailyDetails(): HasMany
    {
        return $this->logs();
    }

    public function getFormattedDateRangeAttribute(): string
    {
        if (!$this->event_date) {
            return '';
        }
        if (!$this->end_date || $this->event_date->isSameDay($this->end_date)) {
            return $this->event_date->format('F d, Y');
        }
        if ($this->event_date->format('Y-m') === $this->end_date->format('Y-m')) {
            return $this->event_date->format('F d') . '–' . $this->end_date->format('d, Y');
        }
        if ($this->event_date->format('Y') === $this->end_date->format('Y')) {
            return $this->event_date->format('F d') . ' – ' . $this->end_date->format('F d, Y');
        }
        return $this->event_date->format('F d, Y') . ' – ' . $this->end_date->format('F d, Y');
    }

    public function getStartDateAttribute()
    {
        return $this->event_date;
    }

    public function getEnableViewDetailsAttribute(): bool
    {
        return (bool) $this->enable_view_link;
    }

    public function setEnableViewDetailsAttribute($value): void
    {
        $this->enable_view_link = (bool) $value;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', '!=', 'cancelled')->where('status', 'upcoming');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        if (empty($slug)) {
            $slug = 'event-' . time();
        }

        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}

