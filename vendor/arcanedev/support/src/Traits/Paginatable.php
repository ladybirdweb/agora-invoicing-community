<?php namespace Arcanedev\Support\Traits;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Trait     Paginatable
 *
 * @package  Arcanedev\Support\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait Paginatable
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Paginate the collection.
     *
     * @param  \Illuminate\Support\Collection  $data
     * @param  \Illuminate\Http\Request        $request
     * @param  int                             $perPage
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginate(Collection $data, Request $request, $perPage)
    {
        $page = $request->get('page', 1);
        $path = $request->url();

        return new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            compact('path')
        );
    }
}
