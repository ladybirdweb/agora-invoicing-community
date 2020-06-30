<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    public function welcome()
    {
        return view('vendor.installer.welcome');
    }
}
