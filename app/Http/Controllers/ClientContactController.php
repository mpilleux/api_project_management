<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Repositories\ContactRepository;

class ClientContactController extends Controller
{
    /**
     * @var ClientRepository
     */
    protected $clientRepo;

    /**
     * @var ContactRepository
     */
    protected $conatctRepo;

    /**
     * @param ClientRepository $clientRepo
     */
    public function __construct(ClientRepository $clientRepo, ContactRepository $conatctRepo) {
        $this->clientRepo = $clientRepo;
        $this->contactRepo = $conatctRepo;
    }

    /**
     * List Contacts for client
     *
     * @return void
     */
    public function index(Request $request, $client_id)
    {
        $client = $this->clientRepo->findOrFail($client_id);
        $contacts = $this->contactRepo->allForClient($client, $request);
        return $this->responseOkWithCollection($contacts);
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
