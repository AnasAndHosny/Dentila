<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckInCode extends Model
{
    protected $fillable = [
        'code',
        'is_active',
    ];
}
