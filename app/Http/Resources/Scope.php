<?php

namespace App\Http\Resources;

class Scope extends AdditionableResource
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
            'project_id' => $this->project_id,
            'project' => new Project($this->whenLoaded('project')),
            'provider_id' => $this->provider_id,
            'provider' => new Provider($this->whenLoaded('provider')),
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->toDateTimeString() : null
        ]);

        return $this->prepareForSend($request, $data);
    }
}
