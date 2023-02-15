<?php

namespace App\Castables;

use App\Casts\Link as LinkCast;
use Illuminate\Contracts\Database\Eloquent\Castable;

class Link implements Castable
{
    /**
     * Link
     */
    public function __construct(public readonly string $path)
    {
    }

    /**
     * Get the name of the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return string
     * @return string|\Illuminate\Contracts\Database\Eloquent\CastsAttributes|\Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes
     */
    public static function castUsing(array $arguments): string
    {
        return LinkCast::class;
    }
}
