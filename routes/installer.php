<?php

use App\Http\Controllers\BillingInstaller\InstallerController;
use Illuminate\Support\Facades\Route;

Route::post('db-setup', [InstallerController::class, 'configuration'])->name('db-setup');

Route::post('posting', [InstallerController::class, 'configurationcheck',
])->name('posting');

Route::get('config-check', [InstallerController::class, 'database',
])->name('config-check');

Route::post('create/env', [InstallerController::class, 'createEnv',
])->name('create.env');

Route::post('preinstall/check', [InstallerController::class, 'checkPreInstall',
])->name('preinstall.check');

Route::post('migrate', [InstallerController::class, 'migrate',
])->name('migrate');

Route::get('getTimeZone', [InstallerController::class, 'getTimeZoneDropDown']);

Route::post('accountcheck', [InstallerController::class, 'accountcheck']);
