<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'url_id',
        'user_id'
    ];

    public function url()
    {
        return $this->belongsTo(Url::class);
    }

    
}
