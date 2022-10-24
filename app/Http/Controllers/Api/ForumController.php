<?php

namespace App\Http\Controllers\Api;

use App\Classes\FileHelper;
use App\Http\Controllers\BasicApiController;
use App\Http\Requests\{ForumTopicRequest, ForumTopicUpdateRequest};
use App\Models\ForumTopic;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\{Request, Response};

class ForumController extends BasicApiController
{
    /**
     * Get forum topic list
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->renderIndexPage($request, ForumTopic::class);
    }

    /**
     * Create forum topic
     *
     * @param ForumTopicRequest $request
     * @return Response
     */
    public function store(ForumTopicRequest $request): Response
    {
        $topic = ForumTopic::create(array_merge($request->validated(), [
            'created_by' => auth('api')->id()
        ]));

        // Check img_url file exists
        if ($request->hasFile('img_url')) {
            try {
                // Attempt to save file
                $topic->img_url = FileHelper::saveFile(
                    $request->file('img_url'),
                    'uploads/topics/' . $topic->id,
                    'forum_img'
                );
            } catch (\Exception $e) {
                return response(['errors' => [
                    'img_url' => [$e->getMessage()]
                ]], 400);
            }
        }

        $topic->save();

        return response($topic, 201);
    }

    /**
     * Update forum topic
     *
     * @param ForumTopic $forum
     * @param Request $request
     * @return Response
     */
    public function update(ForumTopic $forum, Request $request): Response
    {
        $args = $request->only(['name', 'url', 'img_url', 'description', 'text', 'position', 'pinned']);

        $rules = [];
        foreach ($args as $key => $val) {
            switch ($key) {
                case 'description':
                case 'text':
                    $rules[$key] = ['nullable', 'string'];
                    $forum->$key = $val;
                    break;
                case 'name':
                    $rules[$key] = ['required', 'string'];
                    $forum->$key = $val;
                    break;
                case 'img_url':
                    $rules[$key] = ['nullable', 'file', 'mimes:jpeg,jpg,png'];
                    break;
                case 'pinned':
                    $rules[$key] = ['required', 'numeric'];
                    $forum->$key = checkboxResponseToBool($val);
                    break;
                case 'position':
                    $rules[$key] = ['required', 'numeric'];
                    $forum->$key = $val;
                    break;
            }
        }

        $validation = Validator::make($args, $rules);

        if ($validation->fails()) {
            return response($validation->errors()->all(), 422);
        }

        // Check img_url file exists
        if ($request->hasFile('img_url')) {
            try {
                // Attempt to save file
                $forum->img_url = FileHelper::saveFile(
                    $request->file('img_url'),
                    'uploads/topics/' . $forum->id,
                    'forum_img'
                );
            } catch (\Exception $e) {
                return response(['errors' => [
                    'img_url' => [$e->getMessage()]
                ]], 400);
            }
        }

        $forum->save();


        $author_name = $forum->author()->first()->name;

        $forum->messages_count = $forum->messages()->count();
        $forum->author = ['name' => $author_name];

        return response($forum, 200);
    }

    /**
     * Remove forum topic
     *
     * @param ForumTopic $forum
     * @return Response
     */
    public function destroy(ForumTopic $forum): Response
    {
        return $this->defaultRemove($forum);
    }

    /**
     * List query. Add messages count row
     *
     * @param $model
     * @return mixed
     */
    protected function map($model)
    {
        $model->messages_count = $model->messages()->count();
        return $model;
    }
}