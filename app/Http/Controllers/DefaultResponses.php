<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;

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
     * @param Illuminate\Http\Resources\Json\ResourceCollection $collection
     * @param array $aditionals
     * @return void
     */
    public function responseOkWithCollection($collection, $aditionals = [])
    {
        $aditionals = array_merge($aditionals, $this->default_aditional);
        return $collection->additional($aditionals);
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
     * @return void
     */
    protected function getResourceFromModel(Model $model)
    {
        $model_base_name = str_after(get_class($model), 'App\\');
        $resource_name = 'App\\Http\\Resources\\' . $model_base_name;
        return new $resource_name($model);
    }
}