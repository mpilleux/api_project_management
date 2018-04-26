<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model 
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
        'updated_by' => 'int',
        'deleted_by' => 'int'
    ];

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'scopes');    
    }

    /**
     * Get all of the client's additionals.
     */
    public function additionals()
    {
        return $this->morphMany('App\Additional', 'additionable');
    }

}
