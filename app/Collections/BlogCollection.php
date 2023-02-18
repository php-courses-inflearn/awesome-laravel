<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;

class BlogCollection extends Collection
{
    /**
     * 피드
     */
    public function feed(): BaseCollection
    {
        return $this->flatMap->posts->sortByDesc('created_at');
    }
}
