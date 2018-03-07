<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'address',
        'filename',
        'filetype',
        'filesize',
        'message',
        'status',
        'code',
    ];
}
