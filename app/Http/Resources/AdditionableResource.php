<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdditionableResource extends JsonResource
{
    /**
     * Do some prepare work
     *
     * @param [type] $data
     * @return void
     */
    protected function prepareForSend($request, $data)
    {
        $data = $this->mergeWithAdditionals($data);
        $data = $this->filterWithQuery($request, $data);
        return $data->toArray();
    }

    /**
     * Merge data with additionals if relation was load
     *
     * @return void
     */
    protected function mergeWithAdditionals($data)
    {
        if ($this->resource->relationLoaded('additionals')) {
            $this->resource->additionals->each(function($additional) use (&$data){
                $data[$additional->key] = $additional->value_int ?: $additional->value_text; 
            });
        }
        return $data;
    }

    /**
     * Filter with query string param
     *
     * @param [type] $data
     * @return void
     */
    protected function filterWithQuery($request, $data)
    {
        if ($request->query('fields')) {
            $fields = explode(",", $request->query('fields'));
            $data = $data->only($fields);    
        }
        return $data;
    }
}