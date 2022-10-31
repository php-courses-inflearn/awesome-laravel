<?php

namespace App\Casts;

use App\Castables\Link as LinkCastable;
use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Storage;

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
        $path = $model->external
            ? $attributes['name']
            : Storage::disk('public')->url($attributes['name']);

        return new LinkCastable($path);
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
            throw new Exception('The given value is not an Link instance.');
        }

        return [
            'name' => $value->path,
        ];
    }
}
