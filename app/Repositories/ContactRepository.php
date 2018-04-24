<?php

namespace App\Repositories;

use App\Client;
use Illuminate\Http\Request;
use App\Contact;

class ContactRepository extends Repository
{
    use ManageAdditionals;

    /**
     * All contacts for client
     *
     * @param Client $client
     * @param Request $request
     * @return void
     */
    public function allForClient(Client $client, Request $request)
    {
        $fields = $request->query('fields', '*');
        $fields = explode(",", $fields);
        $sort = $request->query('sort', 'id');
        $order = $request->query('order', 'desc');
        $filters = $request->except('fields', 'sort', 'order');
        $contacts = $client->contacts()
            ->select($fields)
            ->where($filters)
            ->orderBy($sort, $order)
            ->get();
        return $contacts;
    }

    /**
     * Find or fail by id
     *
     * @param integer $id
     * @return void
     */
    public function findOrFail($id)
    {
        return Contact::with('additionals')->findOrFail($id);  
    }

    /**
     * Create the client from the request
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $client = Client::create($this->getClientData($request));
        $this->storeAdditionalsForEntity($client, $this->getAdditionalData($request));
        return $client->load('additionals');
    }

    /**
     * update the client
     *
     * @param Client $client
     * @param Request $request
     * @return void
     */
    public function update(Client $client, Request $request)
    {
        $update = $client->update($this->getDataWithoutAdditionals($request));
        $this->updateAdditionalsForEntity($client, $this->getAdditionalData($request));
        return $update;
    }

    /**
     * Delete the client with its additionals
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $client = $this->findOrFail($id);
        $delete = $client->delete();
        $this->deleteAdditionalsForEntityId($id, $type = 'client');
        return $delete;    
    }

    /**
     * Get the data for store
     *
     * @param Request $request
     * @return void
     */
    protected function getClientData(Request $request)
    {
        return [
            'slug' => $request->get('slug'),
            'name' => $request->get('name'),
            'active' => $request->get('active'),
            'created_by' => $request->get('created_by'),
        ];
    }

    /**
     * The fields for the model
     *
     * @return void
     */
    protected function getModelFields()
    {
        return [
            'slug', 
            'name',
            'active',
            'created_by',
            'updated_by',
            'deleted_by',
        ];
    }
}
