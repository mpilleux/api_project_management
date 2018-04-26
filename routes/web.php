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

//ClientContact
$router->get('clients/{id}/contacts', [
    'as' => 'client.contact.index', 'uses' => 'ClientContactController@index'
]);

$router->get('clients/{client_id}/contacts/{contact_id}', [
    'as' => 'client.contact.show', 'uses' => 'ClientContactController@show'
]);

$router->post('clients/{client_id}/contacts/', [
    'as' => 'client.contact.store', 'uses' => 'ClientContactController@store'
]);

$router->put('clients/{client_id}/contacts/{contact_id}', [
    'as' => 'client.contact.update', 'uses' => 'ClientContactController@update'
]);

$router->delete('clients/{client_id}/contacts/{contact_id}', [
    'as' => 'client.contact.delete', 'uses' => 'ClientContactController@delete'
]);

//Providers
$router->get('providers', [
    'as' => 'provider.index', 'uses' => 'ProviderController@index'
]);

$router->get('providers/{id}', [
    'as' => 'provider.show', 'uses' => 'ProviderController@show'
]);

$router->post('providers', [
    'as' => 'provider.store', 'uses' => 'ProviderController@store'
]);

$router->put('providers/{id}', [
    'as' => 'provider.update', 'uses' => 'ProviderController@update'
]);

$router->delete('providers/{id}', [
    'as' => 'provider.delete', 'uses' => 'ProviderController@delete'
]);