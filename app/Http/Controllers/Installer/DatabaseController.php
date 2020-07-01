<?php

namespace App\Http\Controllers\Installer;

use RachidLaasri\LaravelInstaller\Controllers\DatabaseController as BaseDatabaseController;
use RachidLaasri\LaravelInstaller\Helpers\DatabaseManager;

class DatabaseController extends BaseDatabaseController
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     */
    public function database()
    {
        try {
            $response = $this->databaseManager->migrateAndSeed();

            return redirect()->route('AgoraInstaller::final')
                         ->with(['message' => $response]);
        } catch (\Exception $ex) {
            return redirect()->route('AgoraInstaller::environmentWizard')
                         ->with(['fails' =>$ex->getMessage()]);
        }
    }
}
