<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'role',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAdminRole(): bool
    {
        return $this->role === 'admin';
    }

    public function isDirector(): bool
    {
        return $this->role === 'director';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Staff and Admin have authority to create and edit activities and content.
     */
    public function canEdit(): bool
    {
        return in_array($this->role, ['staff', 'admin'], true);
    }

    /**
     * Director and Admin can provide quality review and feedback comments.
     */
    public function canReview(): bool
    {
        return in_array($this->role, ['director', 'admin'], true);
    }

    /**
     * Only Admin has authority to approve official inquiries and manage system governance.
     */
    public function canApprove(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Only Admin has authority to delete activities or content.
     */
    public function canDelete(): bool
    {
        return $this->role === 'admin';
    }
}
