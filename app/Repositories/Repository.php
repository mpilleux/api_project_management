<?php

namespace App\Repositories;

use Illuminate\Http\Request;

abstract class Repository
{
    /**
     * The fields for the object
     */
    abstract protected function getModelFields();

    /**
     * Only the additionals fields
     *
     * @param Request $request
     * @return void
     */
    protected function getAdditionalData(Request $request)
    {
        return $request->except($this->getModelFields());
    }

    /**
     * All but the additionals fields
     *
     * @param Request $request
     * @return void
     */
    protected function getDataWithoutAdditionals(Request $request)
    {
        return $request->only($this->getModelFields());
    }
}