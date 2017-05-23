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

$app->put('testput', 'LoginController@test');

$app->group(['prefix' => 'wp-json', 'middleware' => 'api_key'], function () use ($app) {

    $app->group(['prefix' => 'login/v1'], function () use ($app) {
        $app->post('wordpress', 'LoginController@byEmail');
        $app->post('wordpress/register', 'RegisterController@byEmail');
        $app->Post('facebook', 'LoginController@byFacebook');
    });

    $app->group(['prefix' => 'wc/v1'], function () use ($app) {
        $app->group(['prefix' => 'products'], function () use ($app) {
            $app->get('/', 'ProductController@all');
            $app->get('/{id}', 'ProductController@one');
        });
        $app->group(['prefix' => 'banners'], function () use ($app) {
            $app->get('/', 'BannerController@all');
        });
        $app->group(['prefix' => 'orders', 'middleware' => 'auth'], function () use ($app) {
            $app->get('/', 'OrderController@all');
            $app->post('/', 'OrderController@create');
            $app->get('/{id}', 'OrderController@one');
            $app->put('/{id}', 'OrderController@updateOne');
        });
        $app->group(['prefix' => 'customers', 'middleware' => 'auth'], function () use ($app) {
            $app->get('current', 'UserController@current');
            $app->post('current', 'UserController@update');
            $app->group(['prefix' => 'address'], function () use ($app) {
                $app->get("/", 'AddressController@index');
                $app->put("{id}", 'AddressController@update');
                $app->post("/", 'AddressController@store');
                $app->delete("{id}", 'AddressController@delete');
            });
        });
        $app->group(['prefix' => 'cart', 'middleware' => 'auth'], function () use ($app) {
            $app->get('/', 'CartController@current');
            $app->put('/', 'CartController@updateCurrent');
            $app->delete('/', 'CartController@clear');
        });

    });

});
