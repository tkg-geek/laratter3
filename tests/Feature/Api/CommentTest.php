<?php

use App\Models\Comment;
use App\Models\Tweet;
use App\Models\User;

it('allows authenticated users to create a comment', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test_token')->plainTextToken;

    $data = ['comment' => 'This is a new comment'];

    $response = $this->postJson("/api/tweets/{$tweet->id}/comments", $data, [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertStatus(201);
    $response->assertJson(['comment' => 'This is a new comment']);
});

it('displays a list of comments for a tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test_token')->plainTextToken;

    Comment::factory()->count(3)->create(['tweet_id' => $tweet->id]);

    $response = $this->getJson("/api/tweets/{$tweet->id}/comments", [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertStatus(200);
    $response->assertJsonCount(3);
});

it('displays a specific comment', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create(['tweet_id' => $tweet->id]);
    $token = $user->createToken('test_token')->plainTextToken;

    $response = $this->getJson("/api/tweets/{$tweet->id}/comments/{$comment->id}", [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertStatus(200);
    $response->assertJson(['id' => $comment->id]);
});

it('allows a user to update their comment', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create(['tweet_id' => $tweet->id, 'user_id' => $user->id]);
    $token = $user->createToken('test_token')->plainTextToken;

    $data = ['comment' => 'Updated comment content'];

    $response = $this->putJson("/api/tweets/{$tweet->id}/comments/{$comment->id}", $data, [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertStatus(200);
    $response->assertJson(['comment' => 'Updated comment content']);
});

it('allows a user to delete their comment', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create(['tweet_id' => $tweet->id, 'user_id' => $user->id]);
    $token = $user->createToken('test_token')->plainTextToken;

    $response = $this->deleteJson("/api/tweets/{$tweet->id}/comments/{$comment->id}", [], [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Comment deleted successfully']);
});
