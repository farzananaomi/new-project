<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', ['as' => 'auth.login', 'uses' => function () {
    if (auth()->guard('web')->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
}]);

Route::post('auth/login',['as' => 'auth.check', 'uses' => 'AuthController@postLogin']);

Route::get('app', function(\DBBL\RocketTMR\Data\Repositories\APKRepository $repository) {
    $apk = $repository->findLatestAPK();
    $apk = $repository->incrementDownload($apk);

    return response()->download(storage_path("app/uploads/apks/$apk->path"), null, [
        'Content-Type' => 'application/vnd.android.package-archive',
    ]);
});

Route::group(['middleware' => 'auth:web'], function() {

    Route::group(['prefix' => 'ajax'], function() {
        Route::get('zones', ['as' => 'ajax.zones', 'uses' => 'AjaxController@getZones']);
        Route::get('regions', ['as' => 'ajax.regions', 'uses' => 'AjaxController@getRegions']);
        Route::get('districts', ['as' => 'ajax.districts', 'uses' => 'AjaxController@getDistricts']);
        Route::get('distribution-houses', ['as' => 'ajax.distribution-houses', 'uses' => 'AjaxController@getDistributionHouses']);
        Route::get('outlets', ['as' => 'ajax.outlets', 'uses' => 'AjaxController@getOutlets']);
    });

    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
    Route::get('map', ['as' => 'map', 'uses' => 'MapController@index']);

    Route::resource('outlets', OutletController::class);
    Route::resource('outlets.posm', Reports\POSMController::class, ['only' => ['index', 'show']]);
    Route::resource('outlets.pmm', Reports\PMMController::class, ['only' => ['index', 'show']]);

    Route::resource('distribution_houses', DistributionHouseController::class);
    Route::resource('users', UserController::class);
    Route::resource('agents', AgentController::class);

    Route::get('reports', ['as' => 'reports', 'uses' => 'Reports\ReportingController@index', ]);
    Route::get('reports/posms', ['as' => 'reports.posms', 'uses' => 'Reports\ReportingController@getPOSMs', ]);
    Route::get('reports/pmms', ['as' => 'reports.pmms', 'uses' => 'Reports\ReportingController@getPMMs', ]);
    Route::get('reports/campaigns', ['as' => 'reports.campaigns', 'uses' => 'Reports\ReportingController@getCampaigns', ]);
    Route::get('reports/competitions', ['as' => 'reports.competitions', 'uses' => 'Reports\ReportingController@getCompetitions', ]);
    Route::get('reports/trade_relations', ['as' => 'reports.trade_relations', 'uses' => 'Reports\ReportingController@getTradeRelations', ]);
    Route::get('reports/complaints', ['as' => 'reports.complaints', 'uses' => 'Reports\ReportingController@getComplains', ]);
    Route::get('reports/attendances', ['as' => 'reports.attendances', 'uses' => 'AttendanceController@index', ]);

    Route::get('reports/posms/{id}', ['as' => 'reports.posms.show', 'uses' => 'Reports\ReportViewerController@showPOSM', ]);
    Route::get('reports/pmms/{id}', ['as' => 'reports.pmms.show', 'uses' => 'Reports\ReportViewerController@showPMM', ]);
    Route::get('reports/campaigns/{id}', ['as' => 'reports.campaigns.show', 'uses' => 'Reports\ReportViewerController@showCampaign', ]);
    Route::get('reports/competitions/{id}', ['as' => 'reports.competitions.show', 'uses' => 'Reports\ReportViewerController@showCompetition', ]);
    Route::get('reports/trade_relations/{id}', ['as' => 'reports.trade_relations.show', 'uses' => 'Reports\ReportViewerController@showTradeRelation', ]);
    Route::get('reports/complaints/{id}', ['as' => 'reports.complaints.show', 'uses' => 'Reports\ReportViewerController@showComplain', ]);

    Route::any('auth/logout',['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
    Route::get('error_logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});