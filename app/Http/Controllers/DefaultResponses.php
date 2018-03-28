<?php

namespace App\Http\Controllers;

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
     * @param Illuminate\Http\Resources\Json\Resource $resource
     * @param array $aditionals
     * @return void
     */
    public function responseOkWithResource($resource, $aditionals = [])
    {
        $aditionals = array_merge($aditionals, $this->default_aditional);
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
}