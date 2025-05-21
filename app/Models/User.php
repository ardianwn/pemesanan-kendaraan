<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasUuid;
use App\Traits\Auditable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasUuid, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'preferences',
        'avatar',
        'is_active',
    ];

    /**
     * Get the role relation associated with this user (Used for future expansion).
     */
    public function roleRelation(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id')->withTrashed();
    }

    /**
     * Determine if the user has a given role.
     *
     * @param string $roleSlug
     * @return bool
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->role === $roleSlug;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'json',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Mendapatkan semua logs yang dibuat oleh user ini
     */
    public function logs()
    {
        return $this->hasMany(LogAplikasi::class);
    }
    
    /**
     * Mendapatkan semua pemesanan yang dibuat oleh user ini
     */
    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class);
    }
    
    /**
     * Mendapatkan relasi dengan role (legacy method)
     */
    protected function role_relation(): BelongsTo
    {
        return $this->roleRelation();
    }
    
    /**
     * Mendapatkan semua persetujuan yang diminta ke user ini
     */
    public function persetujuans()
    {
        return $this->hasMany(Persetujuan::class, 'approver_id');
    }
    
    /**
     * Mengecek apakah user adalah seorang admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
    
    /**
     * Mengecek apakah user memiliki permission tertentu
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        // Jika di masa depan ingin menggunakan system permission berbasis role
        if ($this->role_id && $this->roleRelation) {
            return $this->roleRelation->hasPermission($permission);
        }
        
        return false;
    }
    
    /**
     * Mengecek apakah user adalah seorang approver
     */
    public function isApprover()
    {
        return $this->hasRole('approver');
    }
}
