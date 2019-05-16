<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes, HasLogger;
    protected $table = 'pages';
    protected $fillable = [
        'title',
        'content'
    ];
    protected static $logAttributes = ['title'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
