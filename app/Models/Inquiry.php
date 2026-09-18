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
        'ip_address',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status && in_array($status, ['pending', 'in_review', 'resolved', 'archived'])) {
            return $query->where('status', $status);
        }
        return $query;
    }
}
