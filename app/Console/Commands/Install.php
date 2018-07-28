<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:agora';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installing Agora billing App';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm('Do you want to intall Agora?')) {
            $this->createEnv();
            $default = $this->choice(
                    'Which sql engine would you like to use?', ['mysql']
            );
            $host = $this->ask('Enter your sql host');
            $database = $this->ask('Enter your database name');
            $dbusername = $this->ask('Enter your database username');
            $dbpassword = $this->ask('Enter your database password (blank if not entered)', false);
            $port = $this->ask('Enter your sql port (blank if not entered)', false);
            $array = ['DB_TYPE' => $default, 'DB_HOST' => $host,
             'DB_DATABASE'      => $database, 'DB_USERNAME' => $dbusername, 'DB_PASSWORD' => $dbpassword, ];
            $this->updateDBEnv($array);
            $this->call('key:generate');
            $this->call('migrate');
            $this->call('db:seed');
            $this->createAdmin();
            $headers = ['user_name', 'email', 'password'];
            $data = [
                [
                    'user_name' => 'demo',
                    'email'     => 'demo@admin.com',
                    'password'  => 'password',
                ],
            ];
            $this->table($headers, $data);
            $this->warn('Please change the password immediately');
            $this->info('Thank you! Agora has been installed successfully');
        }
    }

    public function createEnv()
    {
        $contents = 'APP_ENV=developing
APP_DEBUG=true
APP_KEY=base64:7dBi4ai8SxvmqnWZAfUSxCGfZdOJkl6m0g1mNwgCK8g=
APP_BUGSNAG=false
APP_URL=http://localhost
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync';
        $env = base_path().DIRECTORY_SEPARATOR.'.env';
        if (is_file($env)) {
            unlink($env);
        }
        if (!is_file($env)) {
            \File::put($env, $contents);
        }
    }

    public function updateEnv($key, $value)
    {
        $env = base_path().DIRECTORY_SEPARATOR.'.env';
        if (is_file($env)) {
            $contents = "$key=$value";
            file_put_contents($env, $contents.PHP_EOL, FILE_APPEND | LOCK_EX);
        } else {
            throw new \Exception('.env not found');
        }
    }

    public function updateDBEnv($array)
    {
        foreach ($array as $key => $value) {
            $this->updateEnv($key, $value);
        }
    }

    public function createAdmin()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('users')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return \App\User::create([
                    'first_name'      => 'Demo',
                    'last_name'       => 'Admin',
                    'user_name'       => 'demo',
                    'email'           => 'demo@admin.com',
                    'role'            => 'admin',
                    'password'        => \Hash::make('password'),
                    'active'          => 1,
                    'mobile_verified' => 1,
                    'currency'        => 'INR',
        ]);
    }
}
