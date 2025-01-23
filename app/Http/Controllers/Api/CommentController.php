<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Tweet;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Tweet $tweet)
    {
        $comments = $tweet->comments()->with('user')->get();
        return response()->json($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Tweet $tweet)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);
        $comment = $tweet->comments()->create([
            'comment' => $request->comment,
            'user_id' => $request->user()->id,
        ]);
        return response()->json($comment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet, Comment $comment)
    {
        return response()->json($comment->load(['user', 'tweet']));
    }

    public function update(Request $request, Tweet $tweet, Comment $comment)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);
        $comment->update($request->only('comment'));
        return response()->json($comment);
    }

    public function destroy(Tweet $tweet, Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
