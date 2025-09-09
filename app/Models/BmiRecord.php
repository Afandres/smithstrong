<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BmiRecord extends Model
{
    protected $fillable = [
        'client_id',
        'weight_kg',
        'height_cm',
        'bmi',
        'bmi_category',
        'diet_suggestion',
        'routine_suggestion',
        'measured_at'
    ];
    protected $casts = [
        'diet_suggestion' => 'array',
        'routine_suggestion' => 'array',
        'measured_at' => 'datetime',
    ];
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
