<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{

    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
    	  
          if ((!\File::exists(base_path('.env'))) || (env('DB_INSTALL') == 0)) {
             return view('vendor.installer.welcome');
           }
           else{
           	return redirect()->back();
           }
    }

}
