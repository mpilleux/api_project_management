<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Additional;

trait ManageAdditionals
{
    /**
     * Store additionals in entity
     *
     * @param Model $entity
     * @param array $additionals
     * @return void
     */
    protected function storeAdditionalsForEntity(Model $entity, array $additionals)
    {
        foreach ($additionals as $key => $value) {
            $val_field = $this->getAdditionalValField($value);
            $entity->additionals()->create(['key' => $key, $val_field => $value]);
        }
    }

    /**
     * Update or inster additionals for entity
     *
     * @param Model $entity
     * @param array $additionals
     * @return void
     */
    protected function updateAdditionalsForEntity(Model $entity, array $additionals)
    {
        foreach ($additionals as $key => $value) {
            $val_field = $this->getAdditionalValField($value);
            //Si existe, update
            if ($entity->additionals->where('key', $key)->first()) {
                $entity->additionals->where('key', $key)->first()->update([
                    $val_field => $value
                ]);
            //Si no, crealo  
            }else{
                $entity->additionals()->create(['key' => $key, $val_field => $value]);
            }
        }    
    }

    /**
     * Delete by model id and type
     *
     * @param integer $id
     * @param string $type
     * @return void
     */
    protected function deleteAdditionalsForEntityId(int $id, string $type)
    {
        $additionals = Additional::where([
            ['additionable_id', '=', $id],
            ['additionable_type', '=', $type],
        ])->get();
        $additionals->each(function($additional){
            $additional->delete();
        });
    }

    /**
     * Get the value field for additionals
     *
     * @param [type] $value
     * @return void
     */
    protected function getAdditionalValField($value)
    {
        return is_numeric($value) ? 'value_int' : 'value_text';    
    }    
}
