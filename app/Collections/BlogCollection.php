<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class BlogCollection extends Collection
{
    /**
     * 피드
     *
     * @return \Illuminate\Support\Collection
     */
    public function feed(): \Illuminate\Support\Collection
    {
        return $this->flatMap->posts->sortByDesc('created_at');
    }
}
