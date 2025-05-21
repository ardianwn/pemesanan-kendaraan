<?php

namespace App\Traits;

use App\Models\LogAplikasi;
use Illuminate\Support\Facades\Auth;


trait Auditable
{
    /**
     * Boot function from Laravel.
     */
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            self::logActivity($model, 'created');
        });
        
        static::updated(function ($model) {
            self::logActivity($model, 'updated');
        });
        
        static::deleted(function ($model) {
            self::logActivity($model, 'deleted');
        });
    }
    
    /**
     * Log activity for model changes.
     *
     * @param mixed $model
     * @param string $action
     */
    protected static function logActivity($model, $action)
    {
        $modelName = class_basename($model);
        $modelId = $model->id;
        
        $description = "{$action} {$modelName} dengan ID #{$modelId}";
        
        if (Auth::check()) {
            $userId = Auth::id();
            
            LogAplikasi::create([
                'user_id' => $userId,
                'aktivitas' => $action,
                'tabel' => $modelName,
                'id_data' => $modelId,
                'deskripsi' => $description,
            ]);
        }
    }
}
