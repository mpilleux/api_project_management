<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model 
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
        'client_id' => 'int',
        'created_by' => 'int',
        'updated_by' => 'int',
        'deleted_by' => 'int'
    ];

    /**
     * @return void
     */
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    /**
     * Get all of the client's additionals.
     */
    public function additionals()
    {
        return $this->morphMany('App\Additional', 'additionable');
    }

}
