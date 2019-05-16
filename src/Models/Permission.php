<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasLogger;

    protected $fillable = [
        'name',
        'label'
    ];

    protected static $logAttributes = [
        'name',
        'label'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
