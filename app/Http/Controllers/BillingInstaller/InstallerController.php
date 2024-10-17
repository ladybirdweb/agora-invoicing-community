<?php

namespace App\Http\Controllers\BillingInstaller;

use App;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SyncBillingToLatestVersion;
use App\Model\Mailjob\QueueService;
use App\User;
use Artisan;
use Cache;
use Exception;
use Illuminate\Http\Request;
use Session;

class InstallerController extends Controller
{
    /**
     * Post configurationcheck
     * checking prerequisites.
     *
     * @return \Illuminate\Http\JsonResponse view
     */
    public function configurationcheck(Request $request)
    {
        $inputs = $request->only([
            'host', 'databasename', 'username', 'password', 'port',
            'db_ssl_key', 'db_ssl_cert', 'db_ssl_ca', 'db_ssl_verify',
        ]);
        Session::put(array_merge($inputs, ['default' => 'mysql', 'db_ssl_key' => $inputs['db_ssl_key'] ?? null, 'db_ssl_cert' => $inputs['db_ssl_cert'] ?? null, 'db_ssl_ca' => $inputs['db_ssl_ca'] ?? null, 'db_ssl_verify' => $inputs['db_ssl_verify'] ?? null]));

        return response()->json((new DatabaseSetupController())->testResult());
    }

    public function checkPreInstall()
    {
        Artisan::call('key:generate', ['--force' => true]);

        $result = ['success' => \Lang::get('installer_messages.pre_migration_success'), 'next' => \Lang::get('installer_messages.migrating_tables')];

        return response()->json(compact('result'));
    }

    public function migrate()
    {
        $db_install_method = '';
        try {
            if (Cache::get('databasename') != env('DB_DATABASE')) {
                throw new Exception(\Lang::get('installer_messages.db_connection_error'), 500);
            }
            $tableNames = \Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
            //allowing migrations table in db as it does not get removed on "migrate:reset"
            $tableNames = array_unique(array_merge(['migrations'], $tableNames));
            if (count($tableNames) === 1) {
                $this->rollBackMigration();
                (new SyncBillingToLatestVersion())->sync();

                if (Cache::get('dummy_data_installation')) {
                    $path = base_path().DIRECTORY_SEPARATOR.'DB'.DIRECTORY_SEPARATOR.'dummy-data.sql';
                    \DB::unprepared(file_get_contents($path));
                }
            }
        } catch (Exception $ex) {
            // $this->rollBackMigration();
            $result = ['error' => $ex->getMessage()];

            return response()->json(compact('result'), 500);
        }

        $message = \Lang::get('installer_messages.database_setup_success');
        $result = ['success' => $message];

        return response()->json(compact('result'));
    }

    public function rollBackMigration()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
//            shell_exec('php ../artisan passport:install');
            // Artisan::call('passport:install', ['--force' => true]);
        } catch (Exception $ex) {
            $result = ['error' => $ex->getMessage()];

            return response()->json(compact('result'), 500);
        }
    }

    public function createEnv($api = true)
    {
        try {
            $default = request()->get('default', Session::get('default'));
            $host = request()->get('host', Session::get('host'));
            $database = request()->get('databasename', Session::get('databasename'));
            $dbusername = request()->get('username', Session::get('username'));
            $dbpassword = request()->get('password', Session::get('password'));
            $port = request()->get('port', Session::get('port'));
            $sslKey = request()->get('db_ssl_key', Session::get('db_ssl_key'));
            $sslCert = request()->get('db_ssl_cert', Session::get('db_ssl_cert'));
            $sslCa = request()->get('db_ssl_ca', Session::get('db_ssl_ca'));
            $sslVerify = request()->get('db_ssl_verify', Session::get('db_ssl_verify'));

            $this->env($default, $host, $port, $database, $dbusername, $dbpassword, null, $sslKey, $sslCert, $sslCa, $sslVerify);
        } catch (Exception $ex) {
            return response()->json(['result' => $ex->getMessage()], 500);
        }

        if ($api) {
            Cache::forever('databasename', $database);
            $url = url('preinstall/check');
            $result = [
                'success' => \Lang::get('installer_messages.env_file_created'),
                'next' => \Lang::get('installer_messages.pre_migration_test'),
            ];

            return response()->json(compact('result'));
        }
    }

    public function env($default, $host, $port, $database, $dbusername, $dbpassword, $appUrl = null)
    {
        $ENV = [
            'APP_NAME' => 'Faveo:'.md5(uniqid()),
            'APP_DEBUG' => 'false',
            'APP_BUGSNAG' => 'true',
            'APP_URL' => $appUrl ?? url('/'), // for CLI installation
            'APP_KEY' => 'base64:h3KjrHeVxyE+j6c8whTAs2YI+7goylGZ/e2vElgXT6I=',
            'APP_LOG_LEVEL' => 'debug',
            'DB_CONNECTION' => $default,
            'DB_HOST' => $host,
            'DB_PORT' => $port,
            'DB_INSTALL' => 0,
            'DB_DATABASE' => $database,
            'DB_USERNAME' => $dbusername,
            'DB_PASSWORD' => str_replace('"', '\"', $dbpassword),
            'DB_ENGINE' => 'InnoDB', // Update after resolving InnoDB issues
            'CACHE_DRIVER' => 'file',
            'SESSION_DRIVER' => 'file',
            'SESSION_COOKIE_NAME' => 'faveo_'.rand(0, 10000),
            'QUEUE_CONNECTION' => 'sync',
            'PROBE_PASS_PHRASE' => md5(uniqid()),
            'REDIS_DATABASE' => '0',
            'BROADCAST_DRIVER' => 'pusher',
            'PUSHER_APP_ID' => str_random(16), // Use Str::random
            'PUSHER_APP_KEY' => md5(uniqid()),
            'PUSHER_APP_SECRET' => md5(uniqid()),
            'PUSHER_APP_CLUSTER' => 'mt1',
            'MAIL_DRIVER' => '',
            'MAIL_HOST' => '',
            'MAIL_PORT' => '',
            'MAIL_USERNAME' => '',
            'MAIL_PASSWORD' => '',
            'MAIL_ENCRYPTION' => '',
        ];

        $config = collect($ENV)
            ->map(fn ($val, $key) => "$key=$val")
            ->implode("\n");

        $envPath = base_path('.env');
        $exampleEnvPath = base_path('example.env');

        // Remove old .env file if it exists
        if (is_file($envPath)) {
            unlink($envPath);
        }

        // Create a new example.env file if it doesn't exist
        if (! is_file($exampleEnvPath)) {
            touch($exampleEnvPath);
        }

        // Write new environment configuration to example.env
        file_put_contents($exampleEnvPath, $config);

        // Rename example.env to .env
        rename($exampleEnvPath, $envPath);
    }

    public function updateInstallEnv(string $environment, string $driver, $redisConfig = [])
    {
        $env = base_path().DIRECTORY_SEPARATOR.'.env';
        if (! is_file($env)) {
            return errorResponse('.env not found', 400);
        }

        $txt1 = "\nAPP_ENV=$environment";
        file_put_contents($env, str_replace('DB_INSTALL='. 0, 'DB_INSTALL='. 1, file_get_contents($env)));
        file_put_contents($env, $txt1.PHP_EOL, FILE_APPEND | LOCK_EX);

        foreach ($redisConfig as $key => $value) {
            $line = strtoupper($key).'='.$value.PHP_EOL;
            file_put_contents($env, $line, FILE_APPEND | LOCK_EX);
        }

        // If Redis is used as cache driver, update .env and relevant database records
        if ($driver == 'redis') {
            // Update .env file to set CACHE_DRIVER to 'redis'
            file_put_contents($env, str_replace('CACHE_DRIVER='.getenv('CACHE_DRIVER'), 'CACHE_DRIVER='.'redis', file_get_contents($env)));

            // Disable all active QueueServices
            QueueService::where('status', 1)->update(['status' => 0]);

            // Enable the Redis QueueService
            $queue = QueueService::where('short_name', 'redis')->first();
            $queue->status = 1;
            $queue->save();

            // Update or create extra field relations for the QueueService
            $queue->extraFieldRelation()->updateOrCreate(['key' => 'driver'], ['key' => 'driver', 'value' => 'redis']);
            $queue->extraFieldRelation()->updateOrCreate(['key' => 'queue'], ['key' => 'queue', 'value' => 'default']);
        }
    }

    /**
     * Post accountcheck
     * checking prerequisites.
     *
     * @param type InstallerRequest $request
     * @return type view
     */
    public function accountcheck(Request $request)
    {
        // Validation rules and custom messages
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'user_name' => 'required|string|max:30|unique:users,user_name',
            'email' => 'required|string|max:50|email|unique:users,email',
            'password' => [
                'required',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[~*!@$#%_+.?:,{ }])[A-Za-z\d~*!@$#%_+.?:,{ }]{8,16}$/',
            ],
            'cache_driver' => 'required|string',
            'redis_host' => 'nullable|required_if:cache_driver,redis|string',
            'redis_password' => 'nullable|required_if:cache_driver,redis|string',
            'redis_port' => 'nullable|required_if:cache_driver,redis|numeric',
            'environment' => 'required|string',
        ], [
            'password.regex' => \Lang::get('installer_messages.password_regex'),
            'redis_host.required_if' => \Lang::get('installer_messages.redis_host_required'),
            'redis_password.required_if' => \Lang::get('installer_messages.redis_password_required'),
            'redis_port.required_if' => \Lang::get('installer_messages.redis_port_required'),
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return errorResponse($validator->errors(), 400);
        }

        try {
            // Create the user
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'user_name' => strtolower($request->input('user_name')),
                'email' => strtolower($request->input('email')),
                'password' => \Hash::make($request->input('password')),
                'active' => 1,
                'role' => 'admin',
                'mobile_verified' => 1,
            ]);
            // Redis configuration based on environment
            if ($request->input('cache_driver') === 'redis') {
                $redisConfig = array_filter([
                    'redis_host' => $request->input('redis_host'),
                    'redis_password' => $request->input('redis_password'),
                    'redis_port' => $request->input('redis_port'),
                ]);

                $this->updateInstallEnv($request->input('environment'), $request->input('cache_driver'), $redisConfig);
            } else {
                $this->updateInstallEnv($request->input('environment'), $request->input('cache_driver'));
            }

            Session::flush();
            \Cache::flush();

            // Return success response
            return successResponse(\Lang::get('installer_messages.setup_completed'), 201);
        } catch (\Exception $e) {
            // Return error response in case of exception
            return errorResponse($e->getMessage(), 400);
        }
    }

    public function getTimeZoneDropDown()
    {
        $timezonesList = \App\Model\Common\Timezone::get();
        foreach ($timezonesList as $timezone) {
            $location = $timezone->location;
            if ($location) {
                $start = strpos($location, '(');
                $end = strpos($location, ')', $start + 1);
                $length = $end - $start;
                $result = substr($location, $start + 1, $length - 1);
                $display[] = ['id' => $timezone->id, 'name' => '('.$result.')'.' '.$timezone->name];
            }
        }

        return $display;
    }

    public function getLang(Request $request)
    {
        $set_lang = $request->input('set_lang');

        if (! empty($set_lang)) {
            App::setLocale($set_lang);
        }
        // Fetch all keys from the 'messages' language file
        $lang = \Lang::get('installer_messages');

        // Return the language array as a JSON response
        return response()->json($lang);
    }
}
