<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Scope;

class ScopeRepository extends Repository
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
        $collection = Scope::select($fields)
            ->where($filters)
            ->orderBy($sort, $order)
            ->get();
        return $collection;
    }

    /**
     * Find or fail by id
     *
     * @param integer $id
     * @return void
     */
    public function findOrFail($id)
    {
        return Scope::with('additionals')->findOrFail($id);  
    }

    /**
     * Create the provider from the request
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $entity = Scope::create($this->getModelData($request));
        $this->storeAdditionalsForEntity($entity, $this->getAdditionalData($request));
        return $entity->load('additionals');
    }

    /**
     * update the model
     *
     * @param Scope $scope
     * @param Request $request
     * @return void
     */
    public function update(Scope $scope, Request $request)
    {
        $update = $scope->update($this->getDataWithoutAdditionals($request));
        $this->updateAdditionalsForEntity($scope, $this->getAdditionalData($request));
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
            'provider_id' => $request->get('provider_id'),
            'project_id' => $request->get('project_id'),
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
            'provider_id',
            'project_id'
        ];
    }
}
