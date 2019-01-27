<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$app->get('/', function () use ($app) {
    return $app->version();
});

/*
 * Default prefix
 *
 * /api
 */
$app->group(['prefix' => 'v1'], function () use ($app) {

    // Only available if application environment is set on production
    if ($app->environment() !== 'production') {
        // get available routes
        $app->get('/', function () use ($app) {
            return $app->getRoutes();
        });
    }

    $app->post( 'raspberrypis', 'RaspberryPiController@store');

    /*
     * Token protected routes
     */
    $app->group(['middleware' => 'auth:api'], function () use ($app) {
        $app->post('energy', 'EnergyDataController@store');

        $app->put('raspberrypis', 'RaspberryPiController@update');
        $app->post('raspberrypis/errors', 'RaspberryPiController@reportError');
    });
});

$app->group(['prefix' => 'v2'], function () use ($app) {
    $app->group(['middleware' => 'rpi'], function () use ($app) {
        $app->post('energy', 'EnergyController@store');

        $app->get('/', function () use ($app) {
            return $app->version();
        });
    });
//    $app->post( 'energy', 'EnergyController@store');
});