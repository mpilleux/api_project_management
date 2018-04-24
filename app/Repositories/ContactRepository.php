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
     * Create the contact from the request
     *
     * @param Request $request
     * @return void
     */
    public function create(Client $client, Request $request)
    {
        $contact_data = $this->getContactData($request);
        $contact_data['client_id'] = $client->id;
        $contact = Contact::create($contact_data);
        $this->storeAdditionalsForEntity($contact, $this->getAdditionalData($request));
        return $contact->load('additionals');
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
    protected function getContactData(Request $request)
    {
        return [
            'slug' => $request->get('slug'),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'active' => $request->get('active'),
            'client_id' => null,
            'created_by' => $request->get('created_by'),
            'updated_by' => $request->get('updated_by'),
            'deleted_by' => $request->get('deleted_by'),
            'created_at' => $request->get('created_at'),
            'updated_at' => $request->get('updated_at'),
            'deleted_at' => $request->get('deleted_at')
        ];
    }

    /**
     * The fields for the model
     * Any field not in this list is 
     * an additional field
     *
     * @return void
     */
    protected function getModelFields()
    {
        return [
            'slug',
            'name',
            'email',
            'phone',
            'address',
            'active',
            'client_id',
            'created_by',
            'updated_by',
            'deleted_by',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
    }
}
