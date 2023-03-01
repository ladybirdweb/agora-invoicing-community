<?php

namespace App\Console\Commands;

use App\Http\Controllers\SyncBillingToLatestVersion;
use Artisan;
use Config;
use DB;
use Illuminate\Console\Command;

class SetupTestEnv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing-setup {--username=} {--password=} {--database=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a testing_db, runs migration and seeder for testing';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dbUsername = $this->option('username') ? $this->option('username') : env('DB_USERNAME');
        $dbPassword = $this->option('password') ? $this->option('password') : env('DB_PASSWORD');
        $dbName = $this->option('database') ? $this->option('database') : 'billing_testing_db';

        $dbPassword = ! $dbPassword ? '' : $dbPassword;
        $this->setupConfig($dbUsername, $dbPassword, '', 'Innodb');

        echo "\nCreating database...\n";

        $this->createDB($dbName);

        echo "\nDatabase Created Successfully!\n";

        //setting up new database name
        Config::set('database.connections.mysql.database', $dbName);

        //setting up app env to testing
        Config::set('app.env', 'testing');

        //opening a database connection
        DB::purge('mysql');

        echo "\nRunning migrations!\n";

        Artisan::call('migrate', ['--force' => true]);

        echo Artisan::output();

        echo "\nMigrations completed!\n";

        echo "\nRunning seeders!\n";

        $this->handlePluginsDatabaseOperations();

        echo Artisan::output();

        echo "\nSeeders ran successfully!\n";

        //closing the database connection
        DB::disconnect('mysql');

        $this->createEnv($dbUsername, $dbPassword, $dbName);

        echo "\nTesting Database setup Successfully\n";
    }

    /**
     * Creates an env file if not exists already.
     *
     * @param  string  $dbUsername
     * @param  string  $dbPassword
     * @return null
     */

        private function handleFaveoDatabaseOperations()
    {
        
        // getting seeder base path
        $seederBasePath = base_path().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'seeders';

        // get all directories inside seeder folder
        // sort versions from oldest to latest
        if (file_exists($seederBasePath)) {
            $seederVersions = scandir($seederBasePath);

            natsort($seederVersions);
            // convert older and newer version into underscore format
            $formattedOlderVersion = $olderVersion;
            foreach ($seederVersions as $version) {
                if (version_compare($this->getPHPCompatibleVersionString($version), $formattedOlderVersion) == 1) {
                    // scan for $version directory and get file names
                    $this->log = $this->log."\n"."Running Seeder for version $version";

                    Artisan::call('db:seed', ['--class' => "Database\Seeders\\$version\DatabaseSeeder", '--force' => true]);
                    $this->handleArtisanLogs();
                }
            }
        }
    }

      private function getPHPCompatibleVersionString(string $version): string
    {
        return preg_replace('#v\.|v#', '', str_replace('_', '.', $version));
    }

    private function createEnv(string $dbUsername, string $dbPassword, string $dbName)
    {
        $env['DB_USERNAME'] = $dbUsername;
        $env['DB_PASSWORD'] = $dbPassword;
        $env['DB_DATABASE'] = $dbName;
        $env['APP_ENV'] = 'development';

        $config = '';

        foreach ($env as $key => $val) {
            $config .= "{$key}={$val}\n";
        }

        $envLocation = base_path().DIRECTORY_SEPARATOR.'.env.testing';

        // Write environment file
        $fp = fopen(base_path().DIRECTORY_SEPARATOR.'.env.testing', 'w');
        fwrite($fp, $config);
        fclose($fp);
    }

    /**
     * Sets up DB config for testing.
     *
     * @param  string  $dbUsername  mysql username
     * @param  string  $dbPassword  mysql password
     * @return null
     */
    private function setupConfig($dbUsername, $dbPassword, $port = '', $dbengine = '')
    {
        Config::set('app.env', 'development');
        Config::set('database.connections.mysql.port', '');
        Config::set('database.connections.mysql.database', null);
        Config::set('database.connections.mysql.username', $dbUsername);
        Config::set('database.connections.mysql.password', $dbPassword);
        Config::set('database.connections.mysql.engine', $dbengine);
        Config::set('database.install', 0);
    }

    /**
     * Creates an empty DB with given name.
     *
     * @param  string  $dbName  name of the DB
     * @return null
     */
    private function createDB(string $dbName)
    {
        \DB::purge('mysql');
        // removing old db
        \DB::connection('mysql')->getPdo()->exec("DROP DATABASE IF EXISTS `{$dbName}`");

        // Creating testing_db
        \DB::connection('mysql')->getPdo()->exec("CREATE DATABASE `{$dbName}`");
        //disconnecting it will remove database config from the memory so that new database name can be
        // populated
        \DB::disconnect('mysql');
    }
}
