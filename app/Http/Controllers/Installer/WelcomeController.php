<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use RachidLaasri\LaravelInstaller\Controllers\WelcomeController as BaseWelcomeController;

class WelcomeController extends BaseWelcomeController
{
    public function welcome()
    {
        return view('vendor.installer.welcome');
    }
}
