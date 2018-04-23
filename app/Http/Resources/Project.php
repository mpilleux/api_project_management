<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Project extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = collect([
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'active' => $this->active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->toDateTimeString() : null
        ]);

        return $this->prepareForSend($request, $data);
    }

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