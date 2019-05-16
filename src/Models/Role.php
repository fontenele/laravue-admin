<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasLogger;

    protected $fillable = [
        'name',
        'label'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected static $logAttributes = [
        'name',
        'label'
    ];

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * @param Permission $permission
     * @return Model
     */
    public function givePermissionTo(Permission $permission): Model
    {
        return $this->permissions()->save($permission);
    }
}
