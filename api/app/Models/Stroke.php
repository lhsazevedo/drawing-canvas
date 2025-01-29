<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stroke extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'uuid';

    protected $casts = [
        'points' => 'array',
    ];

    public function drawingSession()
    {
        return $this->belongsTo(DrawingSession::class);
    }
}
