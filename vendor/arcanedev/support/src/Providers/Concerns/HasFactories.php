<?php namespace Arcanedev\Support\Providers\Concerns;

/**
 * Trait     HasFactories
 *
 * @package  Arcanedev\Support\Providers\Concerns
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasFactories
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Publish the factories.
     */
    protected function publishFactories()
    {
        $this->publishes([
            $this->getDatabasePath().DS.'factories' => database_path('factories'),
        ], 'factories');
    }
}
