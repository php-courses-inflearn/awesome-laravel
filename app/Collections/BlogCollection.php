<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection;

class BlogCollection extends Collection
{
    /**
     * 피드
     *
     * @return \Illuminate\Support\Collection
     */
    public function feed(): Collection
    {
        return $this->flatMap->posts->sortByDesc('created_at');
    }
}
