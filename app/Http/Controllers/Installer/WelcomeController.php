<?php

namespace App\Http\Controllers\Installer;

use RachidLaasri\LaravelInstaller\Controllers\WelcomeController as BaseWelcomeController;

class WelcomeController extends BaseWelcomeController
{
    public function welcome()
    {
        return view('vendor.installer.welcome');
    }
}
