<?php

use App\Models\User;
use App\Models\Tweet;

// likeのテスト
it('allows a user to like a tweet', function () {
  $user = User::factory()->create();
  $tweet = Tweet::factory()->create();

  $this->actingAs($user)
    ->post(route('tweets.like', ['tweet' => $tweet->id]))
    ->assertStatus(302);

  $this->assertDatabaseHas('tweet_user', [
    'user_id' => $user->id,
    'tweet_id' => $tweet->id
  ]);
});

// dislikeのテスト
it('allows a user to dislike a tweet', function () {
  $user = User::factory()->create();
  $tweet = Tweet::factory()->create();

  // 最初にlikeをする
  $user->likes()->attach($tweet);

  $this->actingAs($user)
    ->delete(route('tweets.dislike', ['tweet' => $tweet->id]))
    ->assertStatus(302);

  $this->assertDatabaseMissing('tweet_user', [
    'user_id' => $user->id,
    'tweet_id' => $tweet->id
  ]);
});