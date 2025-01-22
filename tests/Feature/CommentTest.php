<?php

use App\Models\User;
use App\Models\Tweet;
use App\Models\Comment;

it('displays the comment creation form', function () {
  $user = User::factory()->create();
  $this->actingAs($user);

  $tweet = $user->tweets()->create(Tweet::factory()->raw());

  $response = $this->get(route('tweets.comments.create', $tweet));
  $response->assertStatus(200);
  $response->assertViewIs('tweets.comments.create');
  $response->assertViewHas('tweet', $tweet);
});

it('allows authenticated users to create a comment', function () {
  $user = User::factory()->create();
  $this->actingAs($user);

  $tweet = $user->tweets()->create(Tweet::factory()->raw());
  $commentData = ['comment' => 'store test comment'];

  $response = $this->post(route('tweets.comments.store', $tweet), $commentData);
  $response->assertRedirect(route('tweets.show', $tweet));
  $this->assertDatabaseHas('comments', [
    'comment' => $commentData['comment'],
    'tweet_id' => $tweet->id,
    'user_id' => $user->id,
  ]);
});

it('displays a comment', function () {
  $user = User::factory()->create();
  $this->actingAs($user);

  $tweet = $user->tweets()->create(Tweet::factory()->raw());
  $comment = $tweet->comments()->create(Comment::factory()->raw(['user_id' => $user->id]));

  $response = $this->get(route('tweets.comments.show', [$tweet, $comment]));
  $response->assertStatus(200);
  $response->assertViewIs('tweets.comments.show');
  $response->assertViewHas('tweet', $tweet);
  $response->assertViewHas('comment', $comment);
});

it('displays the edit comment page', function () {
  $user = User::factory()->create();
  $this->actingAs($user);

  $tweet = $user->tweets()->create(Tweet::factory()->raw());
  $comment = $tweet->comments()->create(Comment::factory()->raw(['user_id' => $user->id]));

  $response = $this->get(route('tweets.comments.edit', [$tweet, $comment]));
  $response->assertStatus(200);
  $response->assertViewIs('tweets.comments.edit');
  $response->assertViewHas('tweet', $tweet);
  $response->assertViewHas('comment', $comment);
});

it('allows a user to update their comment', function () {
  $user = User::factory()->create();
  $this->actingAs($user);

  $tweet = $user->tweets()->create(Tweet::factory()->raw());
  $comment = $tweet->comments()->create(Comment::factory()->raw(['user_id' => $user->id]));
  $updatedData = ['comment' => 'update test comment'];

  $response = $this->put(route('tweets.comments.update', [$tweet, $comment]), $updatedData);
  $response->assertRedirect(route('tweets.comments.show', [$tweet, $comment]));
  $this->assertDatabaseHas('comments', [
    'id' => $comment->id,
    'comment' => $updatedData['comment'],
  ]);
});

it('allows a user to delete their comment', function () {
  $user = User::factory()->create();
  $this->actingAs($user);

  $tweet = $user->tweets()->create(Tweet::factory()->raw());
  $comment = $tweet->comments()->create(Comment::factory()->raw(['user_id' => $user->id]));

  $response = $this->delete(route('tweets.comments.destroy', [$tweet, $comment]));
  $response->assertRedirect(route('tweets.show', $tweet));
  $this->assertDatabaseMissing('comments', [
    'id' => $comment->id,
  ]);
});

