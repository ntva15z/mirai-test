<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    protected $primaryKey = 'register_id';

    protected $fillable = [
        'login',
        'password',
        'phone'
    ];
}
