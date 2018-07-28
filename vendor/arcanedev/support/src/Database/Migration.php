<?php namespace Arcanedev\Support\Database;

use Closure;
use Illuminate\Database\Migrations\Migration as IlluminateMigration;
use Illuminate\Support\Facades\Schema;

/**
 * Class     Migration
 *
 * @package  Arcanedev\Support\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Migration extends IlluminateMigration
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The table name.
     *
     * @var string|null
     */
    protected $table;

    /**
     * The table prefix.
     *
     * @var string|null
     */
    protected $prefix;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the migration connection name.
     *
     * @param  string  $connection
     *
     * @return self
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Get the table name.
     *
     * @return null|string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Get the prefixed table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->hasPrefix()
             ? $this->getPrefix().$this->getTable()
             : $this->getTable();
    }

    /**
     * Set the table name.
     *
     * @param  string  $table
     *
     * @return self
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get the prefix name.
     *
     * @return null|string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set the prefix name.
     *
     * @param  string  $prefix
     *
     * @return self
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Migrate to database.
     */
    abstract public function up();

    /**
     * Rollback the migration.
     */
    public function down()
    {
        if ( ! $this->hasConnection()) {
            Schema::dropIfExists($this->getTableName());

            return;
        }

        Schema::connection($this->getConnection())->dropIfExists($this->getTableName());
    }

    /**
     * Create Table Schema.
     *
     * @param  \Closure  $blueprint
     */
    protected function createSchema(Closure $blueprint)
    {
        if ($this->hasConnection()) {
            Schema::connection($this->getConnection())
                  ->create($this->getTableName(), $blueprint);
        }
        else {
            Schema::create($this->getTableName(), $blueprint);
        }
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if connection exists.
     *
     * @return bool
     */
    protected function hasConnection()
    {
        return $this->isNotEmpty($this->getConnection());
    }

    /**
     * Check if table has prefix.
     *
     * @return bool
     */
    protected function hasPrefix()
    {
        return $this->isNotEmpty($this->getPrefix());
    }

    /**
     * Check if the value is not empty.
     *
     * @param  string  $value
     *
     * @return bool
     */
    private function isNotEmpty($value)
    {
        return ! (is_null($value) || empty($value));
    }
}
