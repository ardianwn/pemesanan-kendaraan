<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot function from Laravel.
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getUuidColumnName()})) {
                $model->{$model->getUuidColumnName()} = (string) Str::uuid();
            }
        });
    }
    
    /**
     * Get the uuid column name.
     *
     * @return string
     */
    public function getUuidColumnName()
    {
        return 'uuid';
    }
    
    /**
     * Scope a query to find by UUID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $uuid
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindByUuid($query, $uuid)
    {
        return $query->where($this->getUuidColumnName(), $uuid);
    }
}
