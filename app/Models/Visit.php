<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_id',
        'user_id',
        'visits_day',
        'visits_week',
        'visits_month',
        'visits_year'
    ];

    public function url()
    {
        return $this->belongsTo(Url::class);
    }

    
}
