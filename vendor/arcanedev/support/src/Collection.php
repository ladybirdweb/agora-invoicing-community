<?php namespace Arcanedev\Support;

use Illuminate\Support\Collection as IlluminateCollection;

/**
 * Class     Collection
 *
 * @package  Arcanedev\Support
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @deprecated Use the basic laravel collection
 */
class Collection extends IlluminateCollection
{
    /* -----------------------------------------------------------------
     |  Custom Methods
     | -----------------------------------------------------------------
     */

    /**
     * Reset the collection.
     *
     * @return static
     */
    public function reset()
    {
        $this->items = [];

        return $this;
    }
}
