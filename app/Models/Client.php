<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = ['user_id', 'first_name', 'last_name', 'gender', 'birthdate', 'height_cm', 'condition_notes'];
    protected $casts = ['birthdate' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function bmiRecords(): HasMany
    {
        return $this->hasMany(BmiRecord::class);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birthdate ? $this->birthdate->age : null;
    }

    public function fullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
