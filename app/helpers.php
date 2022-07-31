<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Resources\Json\JsonResource;

if (! function_exists('etag')) {
    /**
     * Etag
     *
     * @param Model $model
     * @param string $etag
     * @param array $ifNoneMatch
     * @param Closure $callback
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    function etag(Model $model, string $etag, array $ifNoneMatch, Closure $callback)
    {
        $tag = get_class($model);
        $taggedCache = Cache::tags($tag);
        $key = $model->getKey();

        if ($taggedCache->has($key) && $cachedEtag = $taggedCache->get($key)) {
            if ($etag === $cachedEtag && in_array("\"{$etag}\"", $ifNoneMatch)) {
                return Response::make('', 304);
            }
        }

        $taggedCache->put($key, $etag);

        $content = $callback();

        $response = $content instanceof JsonResource
            ? $content->response()
            : Response::make($content);

        return $response->setEtag($etag);
    }
}
