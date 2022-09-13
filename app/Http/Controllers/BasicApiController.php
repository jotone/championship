<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\{Request, Response};

class BasicApiController extends Controller
{
    /**
     * Default query ordering
     * @var array
     */
    protected $order = ['id'];

    /**
     * Default order direction
     * @var string
     */
    protected $order_dir;

    /**
     * Default query page value
     * @var int
     */
    protected $page = 1;

    /**
     * Default query select field list
     * @var string
     */
    protected $select = '*';

    /**
     * Default query skip value
     * @var int
     */
    protected $skip;

    /**
     * Default query number of limit items
     * @var int
     */
    protected $take = 10;

    /**
     * @param $content
     * @param array $args
     * @return Response
     */
    protected function apiIndexResponse($content, array $args): Response
    {
        // Get total elements count
        $total = $content->count();

        // Apply where query
        if (!empty($args['where'])) {
            foreach ($args['where'] as $key => $value) {
                $content = strpos($key, '+') === 0
                    ? $content->orWhere(substr($key, 1), $value)
                    : $content->where($key, $value);
            }
        }
        // Apply additional query relationships
        if (!empty($args['with'])) {
            $content = $content->with($args['with']);
        }
        // Apply order query
        foreach ($this->order as $field) {
            $content = $content->orderBy($field, $this->order_dir);
        }

        return response([
            // Get paginated content
            'collection' => $content->take($this->take)->skip($this->skip)->get()->map(function ($model) {
                if (method_exists(static::class, 'map')) {
                    $model = $this->map($model);
                }
                return $model;
            }),
            'page'       => $this->page,
            'take'       => $this->take,
            'total'      => $total
        ]);
    }

    /**
     * Parse request data
     * @param Request $request
     * @return array
     */
    protected function parseRequest(Request $request): array
    {
        // Get request data
        $args = $request->only(['take', 'page', 'order', 'select', 'where', 'search', 'with']);

        // Set query selectable fields
        $this->select = $args['select'] ?? $this->select;
        // Set order fields
        if (!empty($args['order']['by'])) {
            $this->order = strpos($args['order']['by'], ',') !== false
                ? explode(',', $args['order']['by'])
                : [$args['order']['by']];
        }

        // Set order direction
        $this->order_dir = !empty($args['order']['dir']) && $args['order']['dir'] == 'desc' ? 'desc' : 'asc';
        // Set number of taken elements
        $this->take = $args['take'] ?? $this->take;
        // Set page number
        $this->page = !empty($args['page']) && is_numeric($args['page']) ? $args['page'] : 1;
        $this->skip = $this->page > 1 ? ($this->page - 1) * $this->take : 0;

        return $args;
    }

    /**
     * Default model remove method
     *
     * @param Model $model
     * @return Response
     */
    protected function defaultRemove(Model $model): Response
    {
        $model->delete();

        return response([], 204);
    }
}