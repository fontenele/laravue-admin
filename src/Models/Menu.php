<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasLogger;

    public $timestamps = false;
    protected $fillable = [
        'name',
        'css_class',
    ];

    protected static $logAttributes = [
        'text',
        'icon',
        'css_class',
        'permission_name',
        'route',
        'url',
        'show_if_logged',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }
}
