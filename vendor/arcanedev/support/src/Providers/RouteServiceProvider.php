<?php

declare(strict_types=1);

namespace Arcanedev\Support\Providers;

use Arcanedev\Support\Routing\Concerns\RegistersRouteClasses;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class     RouteServiceProvider
 *
 * @package  Arcanedev\Support\Laravel\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class RouteServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use RegistersRouteClasses;
}
