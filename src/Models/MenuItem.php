<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasLogger;

    protected $fillable = [
        'menu_id',
        'menu_item_id',
        'text',
        'icon',
        'css_class',
        'permission_name',
        'route',
        'url',
        'show_if_logged',
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

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
