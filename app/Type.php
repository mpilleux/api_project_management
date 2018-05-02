<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model 
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

    /**
     * Get all of the projects's additionals.
     */
    public function additionals()
    {
        return $this->morphMany('App\Additional', 'additionable');
    }

    /**
     * @return void
     */
    public function projects()
    {
        return $this->hasMany('App\Project');    
    }

    /**
     * @return void
     */
    public function parent()
    {
        return $this->belongsTo('App\Type', 'parent_id');
    }

    /**
     * @return void
     */
    public function childs()
    {
        return $this->hasMany('App\Type', 'parent_id');
    }
}
