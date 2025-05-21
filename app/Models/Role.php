<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];
    
    /**
     * Boot function to handle UUID generation
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
    
    /**
     * Get the users associated with this role
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * Check if the role has a given permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->permissions) {
            return false;
        }

        if (in_array('*', $this->permissions)) {
            return true;
        }

        return in_array($permission, $this->permissions);
    }
}
