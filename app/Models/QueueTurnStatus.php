<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueTurnStatus extends Model
{
    protected $fillable = ['name'];

    public function queueTurns()
    {
        return $this->hasMany(QueueTurn::class);
    }
}
