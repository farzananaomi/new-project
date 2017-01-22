<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//
Route::group(['prefix' => 'v1', 'as' => 'api.v1.', 'namespace' => 'API\V1'], function () {

    Route::post('auth/login', ['as' => 'login', 'uses' => 'AuthController@postLogin']);

    //
    Route::group(['middleware' => ['auth:api'],], function () {

        Route::post('change_password', ['as' => 'change_password', 'uses' => 'AuthController@postChangePassword']);

        Route::get('versions', ['as' => 'versions', 'uses' => 'DataController@getVersions',]);
        Route::get('districts', ['as' => 'districts.index', 'uses' => 'DataController@getDistricts',]);
        Route::get('distribution-houses', ['as' => 'distribution-houses.index', 'uses' => 'DataController@getDistributionHouses',]);
        Route::get('signboards', ['as' => 'signboards.index', 'uses' => 'DataController@getSignboards',]);
        Route::get('posm-materials', ['as' => 'posm-materials.index', 'uses' => 'DataController@getPOSMMaterials',]);
        Route::get('brands', ['as' => 'brands.index', 'uses' => 'DataController@getBrands',]);
        Route::get('trade-issues', ['as' => 'trade-issues.index', 'uses' => 'DataController@getTradeIssues',]);
        Route::get('apk', ['as' => 'apk-download', 'uses' => 'DataController@getAPK',]);

        Route::post('attendance', [
            'as'   => 'attendance.store',
            'uses' => 'AttendanceController@store',
        ]);

        Route::resource('outlets', OutletController::class, [
            'except' => [
                'create', 'edit', 'destroy'
            ],
        ]);

        Route::post('posm', ['as' => 'posm_submission', 'uses' => 'ActivityController@postPOSM']);
        Route::post('pmm', ['as' => 'pmm_submission', 'uses' => 'ActivityController@postPMM']);
        Route::post('campaigns', ['as' => 'campaigns_submission', 'uses' => 'ActivityController@postCampaigns']);
        Route::post('competitions', ['as' => 'competitions_submission', 'uses' => 'ActivityController@postCompetitions']);
        Route::post('trade-relations', ['as' => 'trade_relations_submission', 'uses' => 'ActivityController@postTradeRelations']);
        Route::post('complaints', ['as' => 'complaints_submission', 'uses' => 'ActivityController@postComplaints']);

    });
});
