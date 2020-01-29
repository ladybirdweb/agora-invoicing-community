<?php namespace Arcanedev\Support\Providers\Concerns;

/**
 * Trait     HasMigrations
 *
 * @package  Arcanedev\Support\Providers\Concerns
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasMigrations
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the migrations path.
     *
     * @return string
     */
    protected function getMigrationsPath()
    {
        return $this->getBasePath().DS.'database'.DS.'migrations';
    }

    /**
     * Publish the migration files.
     */
    protected function publishMigrations()
    {
        $this->publishes([
            $this->getMigrationsPath() => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Load the migrations files.
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom($this->getMigrationsPath());
    }
}
