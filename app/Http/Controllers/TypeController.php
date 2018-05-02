<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TypeRepository;

class TypeController extends Controller
{
    /**
     * @var TypeRepository
     */
    protected $typeRepo;

    /**
     * @param TypeRepository $typeRepo
     */
    public function __construct(TypeRepository $typeRepo) {
        $this->typeRepo = $typeRepo;
    }

    /**
     * List types
     *
     * @return void
     */
    public function index(Request $request)
    {
        $types = $this->typeRepo->all($request);
        return $this->responseOkWithCollection($types);
    }

    /**
     * Show a Type
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $type = $this->typeRepo->findOrFail($id);
        $this->loadRelations($type, $request->query('with'));
        return $this->responseOkWithResource($type);
    }

    /**
     * Store types
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $type = $this->typeRepo->create($request);
        return $this->responseOkWithResource($type, ['code' => 201]);
    }

    /**
     * Update Types
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $id)
    {
        $type = $this->typeRepo->findOrFail($id);
        $update = $this->typeRepo->update($type, $request);
        $type = $this->typeRepo->findOrFail($type->id);
        return $this->responseOkWithResource($type);
    }

    /**
     * Delete a resource
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $delete = $this->typeRepo->delete($id);
        if(!$delete){
            return $this->responseError('Problem deleting the type');
        }
        return $this->responseOk();
    }
}
