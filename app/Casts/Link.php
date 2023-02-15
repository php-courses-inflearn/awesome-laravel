<?php

namespace App\Casts;

use Illuminate\Database\Eloquent\Model;
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
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        $path = $this->external($attributes['name'])
            ? $attributes['name']
            : Storage::disk('public')->url($attributes['name']);

        //return $value ?? $path;
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
    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if (! $value instanceof LinkCastable) {
            throw new Exception('The given value is not an Link instance.');
        }

        //return $value;
        return [
            'name' => $value->path,
        ];
    }

    /**
     * 파일이 링크인가?
     *
     * @param  string  $name
     * @return false|int
     */
    private function external(string $name)
    {
        return preg_match('/^https?/', $name);
    }
}
