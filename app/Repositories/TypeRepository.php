<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Scope;
use App\Type;

class TypeRepository extends Repository
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
        $collection = Type::select($fields)
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
        return Type::with('additionals')->findOrFail($id);  
    }

    /**
     * Create the provider from the request
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $entity = Type::create($this->getModelData($request));
        $this->storeAdditionalsForEntity($entity, $this->getAdditionalData($request));
        return $entity->load('additionals');
    }

    /**
     * update the model
     *
     * @param Type $type
     * @param Request $request
     * @return void
     */
    public function update(Type $type, Request $request)
    {
        $update = $type->update($this->getDataWithoutAdditionals($request));
        $this->updateAdditionalsForEntity($type, $this->getAdditionalData($request));
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
            'parent_id' => $request->get('parent_id'),
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
            'parent_id',
        ];
    }
}
