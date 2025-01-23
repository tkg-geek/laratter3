// tests/Feature/Api/TweetLikeTest.php

<?php

use App\Models\User;
use App\Models\Tweet;

it('allows an authenticated user to like a tweet', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;

  $tweet = Tweet::factory()->create();

  $response = $this->postJson("/api/tweets/{$tweet->id}/like", ['tweet' => $tweet->id], [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(201);
  $response->assertJson(['message' => 'Tweet liked successfully']);

  $this->assertDatabaseHas('tweet_user', [
    'user_id' => $user->id,
    'tweet_id' => $tweet->id
  ]);
});

it('allows an authenticated user to dislike a tweet', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;

  $tweet = Tweet::factory()->create();
  $user->likes()->attach($tweet);

  $response = $this->deleteJson("/api/tweets/{$tweet->id}/like", ['tweet' => $tweet->id], [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(200);
  $response->assertJson(['message' => 'Tweet disliked successfully']);

  $this->assertDatabaseMissing('tweet_user', [
    'user_id' => $user->id,
    'tweet_id' => $tweet->id
  ]);
});

