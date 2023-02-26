<?php

namespace App\Casts;

use App\Castables\Link as LinkCastable;
use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Link implements CastsAttributes
{
    /**
     * Cast the given value.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): LinkCastable
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
     * @return array<string, string>
     *
     * @throws Exception
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
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
     */
    private function external(string $name): false|int
    {
        return preg_match('/^https?/', $name);
    }
}
