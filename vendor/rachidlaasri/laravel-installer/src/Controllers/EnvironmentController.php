<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager;
use Validator;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\BufferedOutput;

class EnvironmentController extends Controller
{
    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Environment menu page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentMenu()
    {
        return view('vendor.installer.environment');
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentWizard()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('vendor.installer.environment-wizard', compact('envConfig'));
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentClassic()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('vendor.installer.environment-classic', compact('envConfig'));
    }

    /**
     * Processes the newly saved environment configuration (Classic).
     *
     * @param Request $input
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveClassic(Request $input, Redirector $redirect)
    {
        $message = $this->EnvironmentManager->saveFileClassic($input);

        return $redirect->route('LaravelInstaller::environmentClassic')
                        ->with(['message' => $message]);
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param Request $request
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveWizard(Request $request, Redirector $redirect)
    {

        // $rules = config('installer.environment.form.rules');
        // $messages = [
        //     'environment_custom.required_if' => trans('installer_messages.environment.wizard.form.name_required'),
        // ];

        // $validator = Validator::make($request->all(), $rules, $messages);

        // if ($validator->fails()) {
        //     $errors = $validator->errors();
        //     return view('vendor.installer.environment-wizard', compact('errors', 'envConfig'));
        // }

        $results = $this->EnvironmentManager->saveFileWizard($request);
        $response = $this->migrateAndSeed($request);
     
        return redirect()->route('LaravelInstaller::final')
                         ->with(['message' => $response]);
    }

      public function migrateAndSeed($request)
    {
        // dd($request->all());
        
        $outputLog = new BufferedOutput;
        $dummyCheck = $request->input('dummy-data');
       
        $this->sqlite($outputLog);

        return $this->migrate($outputLog,$dummyCheck);
    }

    /**
     * Run the migration and call the seeder.
     *
     * @param collection $outputLog
     * @return collection
     */
    private function migrate($outputLog,$dummyCheck)
    {
        try{

            Artisan::call('migrate', ["--force"=> true], $outputLog);
                             // \DB::unprepared();

        }
        catch(Exception $e){
            return $this->response($e->getMessage());
        }

        return $this->seed($outputLog,$dummyCheck);
    }

    /**
     * Seed the database.
     *
     * @param collection $outputLog
     * @return array
     */
    private function seed($outputLog,$dummyCheck)
    {
        try{

             if ($dummyCheck != null) {
                // dd(file_get_contents(storage_path('dummy-data.sql')));
                $path = storage_path() . '\dummy-data.sql';
                $path1= storage_path() . '\agora.sql';
                $path2 = storage_path() . '\states.sql';
                DB::unprepared(DB::raw(file_get_contents($path)));
                  DB::unprepared(DB::raw(file_get_contents($path1)));
                    DB::unprepared(DB::raw(file_get_contents($path2)));
                   // \DB::unprepared(file_get_contents(storage_path('dummy-data.sql')));
            }
             else{
            Artisan::call('db:seed', [], $outputLog);
        }
        }
        catch(Exception $e){
            return $this->response($e->getMessage());
        }

        return $this->response(trans('installer_messages.final.finished'), 'success', $outputLog);
       
    }

    /**
     * Return a formatted error messages.
     *
     * @param $message
     * @param string $status
     * @param collection $outputLog
     * @return array
     */
    private function response($message, $status = 'danger', $outputLog)
    {
        return [
            'status' => $status,
            'message' => $message,
            'dbOutputLog' => $outputLog->fetch()
        ];
    }

    /**
     * check database type. If SQLite, then create the database file.
     *
     * @param collection $outputLog
     */
    private function sqlite($outputLog)
    {
        if(DB::connection() instanceof SQLiteConnection) {
            $database = DB::connection()->getDatabaseName();
            if(!file_exists($database)) {
                touch($database);
                DB::reconnect(Config::get('database.default'));
            }
            $outputLog->write('Using SqlLite database: ' . $database, 1);
        }
    }
}
