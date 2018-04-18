<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

class AdditionalsRepository
{
    /**
     * Store additionals in entity
     *
     * @param Model $entity
     * @param array $additionals
     * @return void
     */
    public function storeForEntity(Model $entity, array $additionals)
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
    public function updateForEntity(Model $entity, array $additionals)
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