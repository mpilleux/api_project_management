<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Provider;

class ProviderRepository extends Repository
{
    use ManageAdditionals;

    /**
     * @param Request $request
     * @return void
     */
    public function all(Request $request)
    {
        $fields = $request->query('fields', '*');
        $fields = explode(",", $fields);
        $sort = $request->query('sort', 'id');
        $order = $request->query('order', 'desc');
        $filters = $request->except('fields', 'sort', 'order');
        $providers = Provider::select($fields)
            ->where($filters)
            ->orderBy($sort, $order)
            ->get();
        return $providers;
    }

    /**
     * Find or fail by id
     *
     * @param integer $id
     * @return void
     */
    public function findOrFail($id)
    {
        return Provider::with('additionals')->findOrFail($id);  
    }

    /**
     * Create the provider from the request
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $provider = Provider::create($this->getModelData($request));
        $this->storeAdditionalsForEntity($provider, $this->getAdditionalData($request));
        return $provider->load('additionals');
    }

    /**
     * update the model
     *
     * @param Provider $provider
     * @param Request $request
     * @return void
     */
    public function update(Provider $provider, Request $request)
    {
        $update = $provider->update($this->getDataWithoutAdditionals($request));
        $this->updateAdditionalsForEntity($provider, $this->getAdditionalData($request));
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
    protected function getModelData(Request $request)
    {
        return [
            'slug' => $request->get('slug'),
            'name' => $request->get('name'),
            'active' => $request->get('active'),
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
        ];
    }
}
