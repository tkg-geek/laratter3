// tests/Unit/TweetServiceTest.php

<?php

use App\Models\User;
use App\Models\Tweet;
use App\Services\TweetService;
use Illuminate\Http\Request;

// 作成のテスト
it('creates a new tweet', function () {
  $user = User::factory()->create();
  $tweetService = new TweetService();
  $tweetData = ['tweet' => 'Test tweet'];

  // リクエストクラスのデータを作成
  $request = new Request($tweetData);
  $request->setUserResolver(fn () => $user);

  $tweet = $tweetService->createTweet($request);

  expect($tweet)->toBeInstanceOf(Tweet::class);
  expect($tweet->tweet)->toEqual('Test tweet');
});

// 一覧取得のテスト
it('retrieves all tweets', function () {
  Tweet::factory()->count(3)->create();
  $tweetService = new TweetService();

  $tweets = $tweetService->allTweets();

  expect($tweets)->toHaveCount(3);
});

// 更新のテスト
it('updates a tweet', function () {
  $tweet = Tweet::factory()->create();
  $tweetService = new TweetService();
  $updatedData = ['tweet' => 'Updated tweet'];

  // リクエストクラスのデータを作成
  $request = new Request($updatedData);
  $request->setUserResolver(fn () => $tweet->user);

  $updatedTweet = $tweetService->updateTweet($request, $tweet);

  expect($updatedTweet->tweet)->toEqual('Updated tweet');
});

// 削除のテスト
it('deletes a tweet', function () {
  $tweet = Tweet::factory()->create();
  $tweetService = new TweetService();

  $tweetService->deleteTweet($tweet);

  $this->assertDatabaseMissing('tweets', ['id' => $tweet->id]);
});
