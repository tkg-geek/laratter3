<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Auth;
// 🔽 追加
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
    return view('tweets.index', compact('tweets'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('tweets.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'tweet' => 'required|max:255',
    ]);

    // 🔽 編集
    $this->tweetService->createTweet($request);

    return redirect()->route('tweets.index');
  }

  /**
   * Display the specified resource.
   */
  public function show(Tweet $tweet)
  {
    return view('tweets.show', compact('tweet'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Tweet $tweet)
  {
    return view('tweets.edit', compact('tweet'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Tweet $tweet)
  {
    $request->validate([
      'tweet' => 'required|max:255',
    ]);

    // 🔽 編集
    $this->tweetService->updateTweet($request, $tweet);

    return redirect()->route('tweets.show', $tweet);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Tweet $tweet)
  {
    // 🔽 編集
    $this->tweetService->deleteTweet($tweet);

    return redirect()->route('tweets.index');
  }
}
