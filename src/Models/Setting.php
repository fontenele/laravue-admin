<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasLogger;
    public $timestamps = false;
    protected $table = 'settings';
    protected $fillable = [
        'key',
        'value'
    ];
    protected static $logAttributes = [
        'key',
        'value'
    ];
}
