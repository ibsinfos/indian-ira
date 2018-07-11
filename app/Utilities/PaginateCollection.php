<?php

namespace IndianIra\Utilities;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

trait PaginateCollection
{
    /**
     * Paginate the collection after filtering.
     *
     * @param   \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection  $collection
     * @param   integer  $perPage
     * @param   integer  $currentPage
     * @return  \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($collection, $perPage = 10, $currentPage = 1)
    {
        $offSet = ($currentPage * $perPage) - $perPage;

        $otherParams = [
            'path' => request()->url(),
            'query' => request()->query()
        ];

        // Visit: https://laracasts.com/discuss/channels/laravel/is-it-paginate-available-collection
        // View comment of taekunger
        return new LengthAwarePaginator(
            $collection->forPage(Paginator::resolveCurrentPage() , $perPage),
            $collection->count(),
            $perPage,
            Paginator::resolveCurrentPage(),
            $otherParams
        );
    }
}
