<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'user_id', 'name', 'original_url', 'shortened_url', 'short_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public static function generateUniqueShortCode($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shortCode = '';

        for ($i = 0; $i < $length; $i++) {
            $shortCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        $existingUrl = self::where('short_code', $shortCode)->first();

        if ($existingUrl) {
            return self::generateUniqueShortCode($length);
        }

        return $shortCode;
    }
}
