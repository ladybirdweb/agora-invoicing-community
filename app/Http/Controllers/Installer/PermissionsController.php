<?php

namespace App\Http\Controllers\Installer;

use RachidLaasri\LaravelInstaller\Controllers\PermissionsController as BasePermissionsController;
use RachidLaasri\LaravelInstaller\Helpers\PermissionsChecker;

class PermissionsController extends BasePermissionsController
{
    /**
     * @var PermissionsChecker
     */
    protected $permissions;

    /**
     * @param PermissionsChecker $checker
     */
    public function __construct(PermissionsChecker $checker)
    {
        $this->permissions = $checker;
    }

    /**
     * Display the permissions check page.
     *
     * @return \Illuminate\View\View
     */
    public function permissions()
    {
        $permissions = $this->permissions->check(
            config('installer.permissions')
        );

        return view('vendor.installer.permissions', compact('permissions'));
    }
}
