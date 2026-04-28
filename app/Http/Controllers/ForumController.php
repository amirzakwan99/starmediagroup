<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\Platform;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forum = Forum::findOrFail(1);
        $content = $forum->content;
        $forumId = $forum->id;
        $platforms = Platform::all();
        return view('forum.index', compact('content', 'forumId', 'platforms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Forum $forum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Forum $forum)
    {
        $forum = Forum::findOrFail(1);
        $content = $forum->content;
        return view('forum.edit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Forum $forum)
    {
        $content = $request->content;

        $forum->update([
            'content' => $content
        ]);

        return back()->with('success', 'Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Forum $forum)
    {
        //
    }
}
