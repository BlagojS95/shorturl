<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\User;
use Hashids\Hashids;
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
    
        return view('urls.index', [
            'urls' => $urls,
            'user' => $user, 
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

        Url::create([
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
        //
    }
}
