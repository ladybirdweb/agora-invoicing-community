<?php

use App\Http\Controllers\BillingInstaller\InstallerController;
use Illuminate\Support\Facades\Route;

Route::post('db-setup', [InstallerController::class, 'configuration'])->name('db-setup');

Route::post('posting', [InstallerController::class, 'configurationcheck',
])->withoutMiddleware('installer')->name('posting');

Route::get('config-check', [InstallerController::class, 'database',
])->withoutMiddleware('installer')->name('config-check');

Route::post('create/env', [InstallerController::class, 'createEnv',
])->withoutMiddleware('installer')->name('create.env');

Route::post('preinstall/check', [InstallerController::class, 'checkPreInstall',
])->withoutMiddleware('installer')->name('preinstall.check');

Route::post('migrate', [InstallerController::class, 'migrate',
])->withoutMiddleware('installer')->name('migrate');

Route::get('getTimeZone', [InstallerController::class, 'getTimeZoneDropDown'])->withoutMiddleware('installer');
Route::post('accountcheck', [InstallerController::class, 'accountcheck'])->withoutMiddleware('installer');
