<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Post as PostResource;
use App\Events\PostCreated;

class PostController extends BaseController
{
    public function index()
    {
        $posts = Post::paginate(5);

        //return $this->sendResponse(compact('posts'), 'Ports retrieved successfully.');
        return $posts;
    }

    // public function create()
    // {
    //     return view('posts.create');
    // }

    public function store(Request $request)
    {
        $input = $request->only('title', 'body');
        $validator = Validator::make($input, [
            'title' => 'required | min:3',
            'body' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input['slug'] = Str::slug($input['title']);
        $input['user_id'] = Auth::user()->id;

        $post = Post::create($input);

        // emit event: send mail for editors
        event(new PostCreated($post));

        return $this->sendResponse(new PostResource($post), 'Post created successfully.');

    }

    // public function edit(Post $post)
    // {
    //     return view('posts.edit', compact('post'));
    // }

    public function update(Post $post, Request $request)
    {
        $input = $request->only('title', 'body');

        $validator = Validator::make($input, [
            'title' => 'required | min:3',
            'body' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['slug'] = Str::slug($input['title']);

        $post->fill($input)->save();

        return $this->sendResponse(new PostResource($post), 'Post updated successfully.');
    }


    public function show($id)
    {
        $post = Post::published()->findOrFail($id);

        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }

        return $this->sendResponse(new PostResource($post), 'Post retrieved successfully.');
    }

    public function publish($id)
    {
        $post = Post::find($id);

        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }

        $post->published = true;
        $post->save();

        return $this->sendResponse(new PostResource($post), 'Post retrieved successfully.');
    }

    public function drafts()
    {
        $postsQuery = Post::unpublished();
        if(Gate::denies('post.draft')) {
            $postsQuery = $postsQuery->where('user_id', Auth::user()->id);
        }
        $posts = $postsQuery->paginate();

        return $this->sendResponse(new PostResource($posts), 'Post retrieved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }

        $post->delete();

        return $this->sendResponse([], 'Post deleted successfully.');
    }
}
