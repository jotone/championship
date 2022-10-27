<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\BasicMainController;
use App\Http\Requests\{FormMessageUpdateRequest, ForumMessageStoreRequest};
use App\Models\{ForumMessage, ForumTopic};
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ForumMessageController extends BasicMainController
{
    /**
     * View topic comment data
     *
     * @param string $md5_id
     * @return Response
     */
    public function show(string $md5_id): Response
    {
        $comment = ForumMessage::whereRaw("md5(id) = '{$md5_id}'")->with('author')->firstOrFail();

        return response([
            'id'      => md5($comment->id),
            'author'  => $comment->author->name,
            'parent'  => md5($comment->parent_id),
            'message' => $comment->message,
            'created' => $comment->created_at->translatedFormat('j/F/Y о H:i'),
            'routes'  => [
                'update' => route('forum-message.update', md5($comment->id)),
            ]
        ]);
    }

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
            'created' => $comment->created_at->translatedFormat('j/F/Y о H:i'),
            'routes'  => [
                'show'   => route('forum-message.show', md5($comment->id)),
                'update' => route('forum-message.update', md5($comment->id)),
                'remove' => route('forum-message.destroy', md5($comment->id))
            ]
        ], 201);
    }

    public function update(string $md5_id, FormMessageUpdateRequest $request)
    {
        // Request data
        $args = $request->validated();
        // Forum Topic entity
        $topic = ForumTopic::where('url', $args['topic'])->firstOrFail();

        $comment = ForumMessage::where('topic_id', $topic->id)->whereRaw("md5(id) = '{$md5_id}'")->firstOrFail();
        $comment->message = $args['message'];
        $comment->edited_by = Auth::id();
        $comment->edit_reason = $args['edit_reason'] ?? '';
        $comment->edited_at = now();
        $comment->save();

        return response([
            'id'          => md5($comment->id),
            'author'      => Auth::user()->name,
            'parent'      => md5($comment->parent_id),
            'message'     => $comment->message,
            'editor'      => $comment->editedBy()->first()->name,
            'edit_reason' => $comment->edit_reason,
            'edited_at'   => $comment->edited_at->format('j/F/Y о H:i'),
            'created'     => $comment->created_at->translatedFormat('j/F/Y о H:i')
        ]);
    }

    public function destroy(string $md5_id)
    {
        dd($md5_id);
    }
}