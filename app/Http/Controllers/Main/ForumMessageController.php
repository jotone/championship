<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\BasicMainController;
use App\Http\Requests\ForumMessageStoreRequest;
use App\Models\{ForumMessage, ForumTopic};
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Auth;

class ForumMessageController extends BasicMainController
{
    /**
     * Create topic comment
     *
     * @param ForumMessageStoreRequest $request
     * @return Response
     */
    public function store(ForumMessageStoreRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Forum Topic entity
        $topic = ForumTopic::where('url', $args['topic'])->firstOrFail();
        // Comment parent
        $parent = (!empty($args['parent']) && $args['parent'] != '0')
            ? ForumMessage::where('topic_id', $topic->id)
                ->whereRaw("md5(id) = '{$args['parent']}'")
                ->first()
            : null;

        // Create comment
        $comment = ForumMessage::create([
            'topic_id'  => $topic->id,
            'author_id' => Auth::id(),
            'parent_id' => !empty($parent) ? $parent->id : null,
            'message'   => $args['message']
        ]);
        return response([
            'id'      => md5($comment->id),
            'author'  => Auth::user()->name,
            'parent'  => md5($comment->parent_id),
            'message' => $comment->message,
            'created' => $comment->created_at->translatedFormat('j/F/Y о H:i')
        ], 201);
    }

    public function update(string $md5_id, Request $request)
    {
        dd($request->all(), $md5_id);
    }

    public function destroy(string $md5_id)
    {
        dd($md5_id);
    }
}