<?php namespace Arcanedev\Support\Providers;

use Arcanedev\Support\Exceptions\PackageException;
use Arcanedev\Support\Providers\Concerns\{
    HasConfig, HasFactories, HasMigrations, HasTranslations, HasViews
};
use Illuminate\Contracts\Foundation\Application;
use ReflectionClass;

/**
 * Class     PackageServiceProvider
 *
 * @package  Arcanedev\Support\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class PackageServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasConfig,
        HasFactories,
        HasMigrations,
        HasTranslations,
        HasViews;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Vendor name.
     *
     * @var string
     */
    protected $vendor = 'arcanedev';

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = '';

    /**
     * Package base path.
     *
     * @var string
     */
    protected $basePath;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->basePath = $this->resolveBasePath();
    }

    /**
     * Resolve the base path of the package.
     *
     * @return string
     */
    protected function resolveBasePath()
    {
        return dirname(
            (new ReflectionClass($this))->getFileName(), 2
        );
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Get the base database path.
     *
     * @return string
     */
    protected function getDatabasePath()
    {
        return $this->getBasePath().DS.'database';
    }

    /**
     * Get the base resources path.
     *
     * @deprecated Use the getBasePath() instead!
     *
     * @return string
     */
    protected function getResourcesPath()
    {
        return $this->getBasePath().DS.'resources';
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

        $this->checkPackageName();
    }

    /* -----------------------------------------------------------------
     |  Package Methods
     | -----------------------------------------------------------------
     */

    /**
     * Publish all the package files.
     *
     * @param  bool  $load
     */
    protected function publishAll($load = true)
    {
        $this->publishConfig();
        $this->publishMigrations();
        $this->publishViews($load);
        $this->publishTranslations($load);
        $this->publishFactories();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */

    /**
     * Check package name.
     *
     * @throws \Arcanedev\Support\Exceptions\PackageException
     */
    private function checkPackageName()
    {
        if (empty($this->vendor) || empty($this->package))
            throw new PackageException('You must specify the vendor/package name.');
    }
}
