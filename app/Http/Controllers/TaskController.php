<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use App\Http\Requests\SubmitCommentRequest;
use App\Models\Comment;

class TaskController extends Controller
{
    
    public function getTasksPage(){

        return view('tasks');
    }


    public function getTasks(){

        $tasks = Task::with('comments', 'user')->withCount('comments')->get();

        return TaskResource::collection($tasks);
        
    }


    public function submitComment(SubmitCommentRequest $request, Comment $comment){

        $validated =  $request->validated();

        $this->authorize('create', $comment);

        $comment =  Comment::create($validated);

        return response()->json(['message' => 'Comment added successfully']);

    }
}
