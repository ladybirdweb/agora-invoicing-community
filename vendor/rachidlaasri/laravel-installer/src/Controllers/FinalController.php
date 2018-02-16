<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager;
use RachidLaasri\LaravelInstaller\Helpers\FinalInstallManager;
use RachidLaasri\LaravelInstaller\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @return \Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $env = base_path('.env');
        if (\File::exists($env))  {
            file_put_contents($env,str_replace(

                "DB_INSTALL=0", "DB_INSTALL=1", file_get_contents($env)
        ));

        $fileManager->update();
                        
        }

        return view('vendor.installer.finished');
    }
}
