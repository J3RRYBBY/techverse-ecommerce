<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'title',
        'message',
    ];
}
