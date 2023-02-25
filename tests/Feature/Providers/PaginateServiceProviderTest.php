<?php

namespace Tests\Feature\Providers;

use Illuminate\Support\Collection;
use Tests\TestCase;

class PaginateServiceProviderTest extends TestCase
{
    public function testPaginateMacro(): void
    {
        $collection = new Collection(range(1, 10));

        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
        $paginator = $collection->paginate(5, 1);

        $this->assertEquals(1, $paginator->currentPage());
        $this->assertEquals(5, $paginator->perPage());
        $this->assertEquals(10, $paginator->total());
        $this->assertEquals(2, $paginator->lastPage());
        $this->assertEquals(5, $paginator->count());
        $this->assertEquals(range(1, 5), $paginator->items());
    }
}
