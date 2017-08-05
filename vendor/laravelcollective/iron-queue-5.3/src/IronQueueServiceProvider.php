<?php

namespace Collective\IronQueue;

use Illuminate\Support\ServiceProvider;

class IronQueueServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->afterResolving('queue', function ($queue) {
            $queue->addConnector('iron', function () {
                return new Connectors\IronConnector($this->app['encrypter'], $this->app['request']);
            });
        });

        $this->registerIronRequestBinder();
    }

    /**
     * Register the request rebinding event for the Iron queue.
     *
     * @return void
     */
    protected function registerIronRequestBinder()
    {
        $this->app->rebinding('request', function ($app, $request) {
            if ($app['queue']->connected('iron')) {
                $app['queue']->connection('iron')->setRequest($request);
            }
        });
    }
}
