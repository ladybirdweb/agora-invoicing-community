<?php

namespace JildertMiedema\LaravelPlupload\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JildertMiedema\LaravelPlupload\Manager
 */
class Plupload extends Facade
{
        /**
         * Get the registered name of the component.
         *
         * @return string
         */
        protected static function getFacadeAccessor()
        {
            return 'plupload';
        }
}
