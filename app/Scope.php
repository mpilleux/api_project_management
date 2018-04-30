<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scope extends Model 
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'created_by' => 'int',
        'project_id' => 'int',
        'provider_id' => 'int',
        'updated_by' => 'int',
        'deleted_by' => 'int'
    ];

    /**
     * @return void
     */
    public function project()
    {
        return $this->belongsTo('App\Project');    
    }

    /**
     * @return void
     */
    public function provider()
    {
        return $this->belongsTo('App\Provider');    
    }

    /**
     * Get all of the client's additionals.
     */
    public function additionals()
    {
        return $this->morphMany('App\Additional', 'additionable');
    }

}
