<?php

namespace App\Casts;

use http\Exception\InvalidArgumentException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Storage;
use App\Castables\Link as LinkCastable;

class Link implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (! $value) {
            return new LinkCastable($model->external
                ? $model->name
                : Storage::disk('public')->url($model->path));
        }

        return new LinkCastable($attributes['link_path']);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (! $value instanceof LinkCastable) {
            throw new InvalidArgumentException('The given value is not an Link instance.');
        }

        return [
            'link_path' => $value->path
        ];
    }
}
