<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Url;
use App\Models\User;
use Hashids\Hashids;
use App\Models\Visit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user(); 
        $urls = Url::with('user')->get();

        $visits = Visit::all();
    
        return view('urls.index', [
            'urls' => $urls,
            'user' => $user,
            'visits' => $visits
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

     protected function base62_encode($num) {
        $base62_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base62_string = '';

        do {
            $remainder = $num % 62;
            $base62_string = $base62_chars[$remainder] . $base62_string;
            $num = intdiv($num, 62);
        } while ($num > 0);

        return $base62_string;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'original_url' => 'required|url|max:2048',
        ]);
    
        $shortCode = Url::generateUniqueShortCode();

        $shortUrl = 'https://shorturl.com/' . $shortCode;

        $url = Url::create([
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
            'original_url' => $request->input('original_url'),
            'shortened_url' => $shortUrl,
            'short_code' => $shortCode,
        ]);

        return redirect(route('urls.index'))->with('success', 'URL created successfully.');
    }


    public function redirectToOriginalUrl(Request $request, $shortCode)
    {
        $url = Url::where('short_code', $shortCode)->first();

        if ($url) {

            $now = Carbon::now();
            
            $today = $now->toDateString();
            $visit = Visit::firstOrCreate([
                'url_id' => $url->id,
                'visit_date' => $today,
            ]);

            //Each column will be reseted to 1 if the IF condition is satisified. So the link must be clicked. In order for these counts to be updated regardless of whether a link is clicked, we would need to implement a separate mechanism, such as a scheduled task or cron job, to update these counts at the start of each week, month, and year. 

            if ($visit->visit_date != $today) {
                $visit->visits_day = 1;
            } else {
                $visit->increment('visits_day');
            }
    
            if ($now->isMonday()) {
                $visit->visits_week = 1;
            } else {
                $visit->increment('visits_week');
            }
    
            if ($now->day === 1) {
                $visit->visits_month = 1;
            } else {
                $visit->increment('visits_month');
            }
    

            if ($now->month === 1 && $now->day === 1) {
                $visit->visits_year = 1;
            } else {
                $visit->increment('visits_year');
            }
    
            $visit->save();
    
            return redirect()->away($url->original_url);
        } else {
            return response()->view('errors.404', [], 404);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Url $url)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Url $url)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Url $url)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Url $url)
    {
        if ($url->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this URL.');
        }
    
        $url->deleteWithVisits();
    
        return redirect()->route('urls.index')->with('success', 'URL deleted successfully.');
    }
}
