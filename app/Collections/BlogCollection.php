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
    public function feed()
    {
        return $this->flatMap->posts->sortByDesc('created_at');
    }
}
