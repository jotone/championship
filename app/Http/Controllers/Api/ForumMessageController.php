<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
use App\Models\ForumMessage;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Auth;

class ForumMessageController extends BasicApiController
{
    /**
     * Update comment data
     *
     * @param ForumMessage $forum_message
     * @param Request $request
     * @return Response
     */
    public function update(ForumMessage $forum_message, Request $request): Response
    {
        if (empty($forum_message->deleted_at)) {
            // Request data
            $args = $request->only(['message', 'pinned']);
            // Check message data exists
            if (isset($args['message'])) {
                $forum_message->message = $args['message'];
                $forum_message->edited_by = Auth::id();
                $forum_message->edited_at = now();
            }
            // Check message is pinned
            if (isset($args['pinned'])) {
                $forum_message->pinned = checkboxResponseToBool($args['pinned']);
            }
            $response = $forum_message;
        } else {
            $forum_message->deleted_at = null;
            $response = null;
        }

        $forum_message->save();

        return response($response);
    }

    /**
     * Set comment as removed
     *
     * @param ForumMessage $forum_message
     * @return Response
     * @throws \Exception
     */
    public function destroy(ForumMessage $forum_message): Response
    {
        if (empty($forum_message->deleted_at)) {
            $forum_message->deleted_at = now();
            $forum_message->save();
        } else {
            $forum_message->delete();
        }

        return response([], 204);
    }
}