<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ClientCollection;
use App\Client;
use App\Http\Resources\ClientResource;

class ClientController extends Controller
{

    /**
     * List Clients
     *
     * @return void
     */
    public function index(Request $request)
    {
        $fields = $request->query('fields', '*');
        $fields = explode(",", $fields);
        $sort = $request->query('sort', 'id');
        $order = $request->query('order', 'desc');
        $filters = $request->except('fields', 'sort', 'order');
        $clients = Client::select($fields)->where($filters)->orderBy($sort, $order)->get();
        $clients_collection = new ClientCollection($clients);
        
        return $this->responseOkWithCollection($clients_collection);
    }

    /**
     * Show a client
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $client_resource = new ClientResource($client);
        return $this->responseOkWithResource($client_resource);
    }

    /**
     * Store client
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $client = Client::create($request->all());
        $client_resource = new ClientResource($client);
        return $this->responseOkWithResource($client_resource, ['code' => 201]);
    }

    /**
     * Store Client
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $id)
    {
        $client = Client::find($id);
        $client->update($request->all());
        $new_client = new ClientResource(Client::find($id));
        return $this->responseOkWithResource($new_client);
    }

    /**
     * Delete a resource
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $client = Client::find($id);
        $delete = $client->delete();
        return $this->responseOk();
    }
}
