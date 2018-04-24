<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait DefaultResponses 
{
    /**
     * defaults aditionals for responses
     *
     * @var array
     */
    protected $default_aditional = [
        'code' => 200,
        'status' => 'ok',
        'messages' => []
    ];

    /**
     * Ok response with resource
     *
     * @param Model $model
     * @param array $aditionals
     * @return void
     */
    public function responseOkWithResource($model, $aditionals = [])
    {
        $aditionals = array_merge($aditionals, $this->default_aditional);
        $resource = $this->getResourceFromModel($model);
        return $resource->additional($aditionals);
    }

    /**
     * Ok response with collection
     *
     * @param Collection $collection
     * @param array $aditionals
     * @return void
     */
    public function responseOkWithCollection($collection, $aditionals = [])
    {
        $aditionals = array_merge($aditionals, $this->default_aditional);
        $resource_collection = $this->getResourceCollection($collection);
        return $resource_collection->additional($aditionals);
    }

    /**
     * Ok response
     * @param array $aditionals
     * @return void
     */
    public function responseOk($aditionals = [])
    {
        $response = array_merge($this->default_aditional, $aditionals);
        $response['data'] = [];
        return $response;
    }

    /**
     * Get the resource for the model
     *
     * @param Model $model
     * @return JsonResource
     */
    protected function getResourceFromModel(Model $model)
    {
        $model_base_name = str_after(get_class($model), 'App\\');
        $resource_name = 'App\\Http\\Resources\\' . $model_base_name;
        return new $resource_name($model);
    }

    /**
     * Get the resource collection for the collection of models
     *
     * @param Collection $collection
     * @return ResourceCollection
     */
    protected function getResourceCollection(Collection $collection)
    {
        $model = $collection->first();
        $model_base_name = str_after(get_class($model), 'App\\');
        $resource_collection_name = 'App\\Http\\Resources\\' . $model_base_name . 'Collection';
        return new $resource_collection_name($collection);
    }
}