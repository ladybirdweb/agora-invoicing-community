<?php namespace Arcanedev\Support;

/**
 * Class     Stub
 *
 * @package  Arcanedev\Support
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Stub
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The stub path.
     *
     * @var string
     */
    protected $path;

    /**
     * The base path of stub file.
     *
     * @var string|null
     */
    protected static $basePath = null;

    /**
     * The replacements array.
     *
     * @var array
     */
    protected $replaces = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Create a new instance.
     *
     * @param  string  $path
     * @param  array   $replaces
     */
    public function __construct($path, array $replaces = [])
    {
        $this->setPath($path);
        $this->setReplaces($replaces);
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getPath()
    {
        $path = $this->path;

        if ( ! empty(static::$basePath)) {
            $path = static::$basePath.DS.ltrim($path, DS);
        }

        return $path;
    }

    /**
     * Set stub path.
     *
     * @param  string  $path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get base path.
     *
     * @return string|null
     */
    public static function getBasePath()
    {
        return static::$basePath;
    }

    /**
     * Set base path.
     *
     * @param  string  $path
     */
    public static function setBasePath($path)
    {
        static::$basePath = $path;
    }

    /**
     * Get replacements.
     *
     * @return array
     */
    public function getReplaces()
    {
        return $this->replaces;
    }

    /**
     * Set replacements array.
     *
     * @param  array  $replaces
     *
     * @return self
     */
    public function setReplaces(array $replaces = [])
    {
        $this->replaces = $replaces;

        return $this;
    }

    /**
     * Set replacements array.
     *
     * @param  array  $replaces
     *
     * @return self
     */
    public function replaces(array $replaces = [])
    {
        return $this->setReplaces($replaces);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create new self instance.
     *
     * @param  string  $path
     * @param  array   $replaces
     *
     * @return self
     */
    public static function create($path, array $replaces = [])
    {
        return new static($path, $replaces);
    }

    /**
     * Create new self instance from full path.
     *
     * @param  string  $path
     * @param  array   $replaces
     *
     * @return self
     */
    public static function createFromPath($path, array $replaces = [])
    {
        return tap(new static($path, $replaces), function (self $stub) {
            $stub->setBasePath('');
        });
    }

    /**
     * Get stub contents.
     *
     * @return string
     */
    public function render()
    {
        return $this->getContents();
    }

    /**
     * Save stub to base path.
     *
     * @param  string  $filename
     *
     * @return bool
     */
    public function save($filename)
    {
        return $this->saveTo(self::getBasePath(), $filename);
    }

    /**
     * Save stub to specific path.
     *
     * @param  string  $path
     * @param  string  $filename
     *
     * @return bool
     */
    public function saveTo($path, $filename)
    {
        return file_put_contents($path.DS.$filename, $this->render());
    }

    /**
     * Get stub contents.
     *
     * @return string|mixed
     */
    public function getContents()
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->getReplaces() as $search => $replace) {
            $contents = str_replace('$'.strtoupper($search).'$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Handle magic method __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
