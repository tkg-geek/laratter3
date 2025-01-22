<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tweet;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tweets = Tweet::with('user')->latest()->get();
        return response()->json($tweets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tweet' => 'required|max:255',
        ]);
        $tweet = $request->user()->tweets()->create($request->only('tweet'));
        return response()->json($tweet, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        return response()->json($tweet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        $request->validate([
            'tweet' => 'required|string|max:255',
        ]);
        $tweet->update($request->all());

        return response()->json($tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        $tweet->delete();
        return response()->json(['message' => 'Tweet deleted successfully']);
    }
}
