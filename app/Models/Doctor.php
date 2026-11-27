<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public function reviews(): HasMany
    {
        return $this->hasMany(DoctorReview::class);
    }
}
