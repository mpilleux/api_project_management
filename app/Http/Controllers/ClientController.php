<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ClientCollection;
use App\Client;
use App\Http\Resources\ClientResource;
use App\Repositories\ClientRepository;

class ClientController extends Controller
{
    /**
     * @var ClientRepository
     */
    protected $clientRepo;

    /**
     * @param ClientRepository $clientRepo
     */
    public function __construct(ClientRepository $clientRepo) {
        $this->clientRepo = $clientRepo;
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
        $client = $this->clientRepo->findOrFail($id);
        $this->loadRelations($client, $request->query('with'));
        return $this->responseOkWithResource($client);
    }

    /**
     * Store client
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $client = $this->clientRepo->create($request);
        return $this->responseOkWithResource($client, ['code' => 201]);
    }

    /**
     * Store Client
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $id)
    {
        $client = $this->clientRepo->findOrFail($id);
        $update = $this->clientRepo->update($client, $request);
        return $this->responseOkWithResource($client);
    }

    /**
     * Delete a resource
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $delete = $this->clientRepo->delete($id);
        if(!$delete){
            return $this->responseError('Problem deleting the client');
        }
        return $this->responseOk();
    }
}
