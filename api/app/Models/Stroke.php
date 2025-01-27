<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stroke extends Model
{
    protected $guarded = [];

    public function drawingSession()
    {
        return $this->belongsTo(DrawingSession::class);
    }
}
