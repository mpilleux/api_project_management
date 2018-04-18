<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model 
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
     * @param array $additionals
     * @return void
     */
    public function storeAdditionals(array $additionals)
    {
        foreach ($additionals as $key => $value) {
            $val_field = is_numeric($value) ? 'value_int' : 'value_text';
            $this->additionals()->create(['key' => $key, $val_field => $value]);
        }
    }

    /**
     * Update or insert additionals
     *
     * @param array $additionals
     * @return void
     */
    public function updateAdditionals(array $additionals)
    {
        $old_additionals = $this->additionals;
        foreach ($additionals as $key => $value) {
            $val_field = is_numeric($value) ? 'value_int' : 'value_text';
            //Si existe, update
            if ($old_additionals->where('key', $key)->first()) {
                $old = $old_additionals->where('key', $key)->first();
                $old->update([
                    $val_field => $value
                ]);
            //Si no, crealo  
            }else{
                $this->additionals()->create(['key' => $key, $val_field => $value]);
            }
        }
    }

}
