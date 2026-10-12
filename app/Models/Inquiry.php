<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $table = 'inquiries';

    protected $fillable = [
        'full_name',
        'email',
        'contact_number',
        'affiliation',
        'inquiry_type',
        'subject',
        'message',
        'status',
        'admin_notes',
        'director_comments',
        'reviewed_by',
        'approved_by',
        'reviewed_at',
        'approved_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status && in_array($status, ['pending', 'approved', 'declined', 'rejected', 'in_review', 'reviewed', 'resolved', 'archived'])) {
            if (in_array($status, ['declined', 'rejected'])) {
                return $query->whereIn('status', ['declined', 'rejected']);
            }
            return $query->where('status', $status);
        }
        return $query;
    }
}
