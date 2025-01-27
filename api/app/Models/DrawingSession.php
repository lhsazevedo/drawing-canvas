<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrawingSession extends Model
{
    protected $guarded = [];

    public function strokes()
    {
        return $this->hasMany(Stroke::class);
    }
}
