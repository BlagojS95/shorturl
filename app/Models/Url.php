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

    public function deleteWithVisits()
    {
        $this->visits()->delete();
        $this->delete();
    }

    public static function generateUniqueShortCode($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shortCode = '';

        for ($i = 0; $i < $length; $i++) {
            $shortCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        $existingUrl = self::where('short_code', $shortCode)->first();

        // recursive call to the generateUniqueShortCode function with the same $length parameter to generate a new short code.

        if ($existingUrl) {
            return self::generateUniqueShortCode($length);
        }

        return $shortCode;
    }

    //functions to get the visits per day, week, month and year
    public function visitsToday()
    {
        return $this->visits()->whereDate('visit_date', today())->count();
    }

    public function visitsThisWeek()
    {
        return $this->visits()->whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])->count();
    }

    public function visitsThisMonth()
    {
        return $this->visits()->whereYear('visit_date', now()->year)->whereMonth('visit_date', now()->month)->count();
    }

    public function visitsThisYear()
    {
        return $this->visits()->whereYear('visit_date', now()->year)->count();
    }
}
