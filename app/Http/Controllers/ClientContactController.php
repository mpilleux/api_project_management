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
    protected $contactRepo;

    /**
     * @param ClientRepository $clientRepo
     */
    public function __construct(ClientRepository $clientRepo, ContactRepository $contactRepo) {
        $this->clientRepo = $clientRepo;
        $this->contactRepo = $contactRepo;
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
    public function show(Request $request, $client_id, $contact_id)
    {
        $contact = $this->contactRepo->findOrFail($contact_id);
        $this->loadRelations($contact, $request->query('with'));
        return $this->responseOkWithResource($contact);
    }

    /**
     * Store client
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request, $client_id)
    {
        $client = $this->clientRepo->findOrFail($client_id);
        $contact = $this->contactRepo->create($client, $request);
        return $this->responseOkWithResource($contact, ['code' => 201]);
    }

    /**
     * Store Client
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $client_id, $contact_id)
    {
        $contact = $this->contactRepo->findOrFail($contact_id);
        $update = $this->contactRepo->update($contact, $request);
        $contact = $this->contactRepo->findOrFail($contact->id);
        return $this->responseOkWithResource($contact);
    }

    /**
     * Delete a resource
     *
     * @param [type] $id
     * @return void
     */
    public function delete($client_id, $contact_id)
    {
        $delete = $this->contactRepo->delete($contact_id);
        if(!$delete){
            return $this->responseError('Problem deleting the contact');
        }
        return $this->responseOk();
    }
}
