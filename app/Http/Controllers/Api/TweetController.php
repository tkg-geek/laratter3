<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Requests\UpdateTweetRequest;
use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Services\TweetService;

class TweetController extends Controller
{
  // 🔽 追加
  protected $tweetService;

  // 🔽 追加
  public function __construct(TweetService $tweetService)
  {
    $this->tweetService = $tweetService;
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    // 🔽 編集
    $tweets = $this->tweetService->allTweets();
    return response()->json($tweets);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreTweetRequest $request)
  {
    $tweet = $this->tweetService->createTweet($request);

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
  public function update(UpdateTweetRequest $request, Tweet $tweet)
  {
    $updatedTweet = $this->tweetService->updateTweet($request, $tweet);

    return response()->json($updatedTweet);
  }
  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Tweet $tweet)
  {
    // 🔽 編集
    $this->tweetService->deleteTweet($tweet);
    return response()->json(['message' => 'Tweet deleted successfully']);
  }
}
