<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ClientCollection;
use App\Client;
use App\Http\Resources\ClientResource;
use App\Repositories\AdditionalsRepository;

class ClientController extends Controller
{
    /**
     * @var AdditionalsRepository
     */
    protected $additionalsRepo;

    /**
     * @param AdditionalsRepository $additionalsRepo
     */
    public function __construct(AdditionalsRepository $additionalsRepo) {
        $this->additionalsRepo = $additionalsRepo;
    }

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
        
        $this->loadRelations($client, $request->query('with'));
        
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
        $client = Client::create($request->except('additionals'));
        $this->additionalsRepo->storeForEntity($client, $request->get('additionals'));
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
        $client->update($request->except('additionals'));
        $this->additionalsRepo->updateForEntity($client, $request->get('additionals'));
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
