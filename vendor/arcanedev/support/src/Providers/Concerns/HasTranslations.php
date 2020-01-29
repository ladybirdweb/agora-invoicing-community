<?php namespace Arcanedev\Support\Providers\Concerns;

/**
 * Trait     HasTranslations
 *
 * @package  Arcanedev\Support\Providers\Concerns
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasTranslations
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the translations path.
     *
     * @return string
     */
    protected function getTranslationsPath()
    {
        return $this->getBasePath().DS.'resources'.DS.'lang';
    }

    /**
     * Get the destination views path.
     *
     * @return string
     */
    protected function getTranslationsDestinationPath()
    {
        return $this->app['path.lang'].DS.'vendor'.DS.$this->package;
    }

    /**
     * Publish and load the translations if $load argument is true.
     *
     * @param  bool  $load
     */
    protected function publishTranslations(bool $load = true)
    {
        $this->publishes([
            $this->getTranslationsPath() => $this->getTranslationsDestinationPath()
        ], 'lang');

        if ($load)
            $this->loadTranslations();
    }

    /**
     * Load the translations files.
     */
    protected function loadTranslations()
    {
        $this->loadTranslationsFrom($this->getTranslationsPath(), $this->package);
    }
}
