<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use DefaultResponses;

    /**
     * Load relations for the model
     *
     * @param Illuminate\Database\Eloquent\Model $entity
     * @param string $relations
     * @return void
     */
    public function loadRelations($entity, $relations)
    {
        if ($relations) {
            $entity->load(explode(",", $relations));    
        }    
    }
}
