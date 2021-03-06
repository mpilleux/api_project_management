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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//Projects
$router->get('projects', [
    'as' => 'project.index', 'uses' => 'ProjectController@index'
]);
$router->get('projects/{id}', [
    'as' => 'project.show', 'uses' => 'ProjectController@show'
]);
$router->post('projects', [
    'as' => 'project.store', 'uses' => 'ProjectController@store'
]);
$router->put('projects/{id}', [
    'as' => 'project.update', 'uses' => 'ProjectController@update'
]);
$router->delete('projects/{id}', [
    'as' => 'project.delete', 'uses' => 'ProjectController@delete'
]);

//Clients
$router->get('clients', [
    'as' => 'client.index', 'uses' => 'ClientController@index'
]);
$router->get('clients/{id}', [
    'as' => 'client.show', 'uses' => 'ClientController@show'
]);
$router->post('clients', [
    'as' => 'client.store', 'uses' => 'ClientController@store'
]);
$router->put('clients/{id}', [
    'as' => 'client.update', 'uses' => 'ClientController@update'
]);
$router->delete('clients/{id}', [
    'as' => 'client.delete', 'uses' => 'ClientController@delete'
]);