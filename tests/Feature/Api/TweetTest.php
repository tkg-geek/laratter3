// tests/Feature/Api/TweetTest.php

<?php

use App\Models\Tweet;
use App\Models\User;

// 作成のテスト
it('allows authenticated users to create a tweet', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;

  $data = ['tweet' => 'This is a new tweet'];

  $response = $this->postJson('/api/tweets', $data, [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(201);
  $response->assertJson(['tweet' => 'This is a new tweet']);
});

// 一覧取得のテスト
it('displays a list of tweets', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;

  Tweet::factory()->count(3)->create();

  $response = $this->getJson('/api/tweets', [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(200);
  $response->assertJsonCount(3);
});

// 詳細取得のテスト
it('displays a specific tweet', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;

  $tweet = Tweet::factory()->create();

  $response = $this->getJson('/api/tweets/' . $tweet->id, [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(200);
  $response->assertJson(['id' => $tweet->id]);
});

// 更新のテスト
it('allows a user to update their tweet', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;

  $tweet = Tweet::factory()->create(['user_id' => $user->id]);

  $data = ['tweet' => 'Updated tweet content'];

  $response = $this->putJson('/api/tweets/' . $tweet->id, $data, [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(200);
  $response->assertJson(['tweet' => 'Updated tweet content']);
});

// 削除のテスト
it('allows a user to delete their tweet', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;

  $tweet = Tweet::factory()->create(['user_id' => $user->id]);

  $response = $this->deleteJson('/api/tweets/' . $tweet->id, [], [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(200);
  $response->assertJson(['message' => 'Tweet deleted successfully']);
});

