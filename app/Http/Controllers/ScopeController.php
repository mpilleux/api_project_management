<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ScopeRepository;

class ScopeController extends Controller
{
    /**
     * @var ScopeRepository
     */
    protected $scopeRepo;

    /**
     * @param ScopeRepository $scopeRepo
     */
    public function __construct(ScopeRepository $scopeRepo) {
        $this->scopeRepo = $scopeRepo;
    }

    /**
     * List Scopes
     *
     * @return void
     */
    public function index(Request $request)
    {
        $scope = $this->scopeRepo->all($request);
        return $this->responseOkWithCollection($scope);
    }

    /**
     * Show a Scope
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $scope = $this->scopeRepo->findOrFail($id);
        $this->loadRelations($scope, $request->query('with'));
        return $this->responseOkWithResource($scope);
    }

    /**
     * Store scope
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $scope = $this->scopeRepo->create($request);
        return $this->responseOkWithResource($scope, ['code' => 201]);
    }

    /**
     * Store Scopes
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $id)
    {
        $scope = $this->scopeRepo->findOrFail($id);
        $update = $this->scopeRepo->update($scope, $request);
        $scope = $this->scopeRepo->findOrFail($scope->id);
        return $this->responseOkWithResource($scope);
    }

    /**
     * Delete a resource
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $delete = $this->scopeRepo->delete($id);
        if(!$delete){
            return $this->responseError('Problem deleting the scope');
        }
        return $this->responseOk();
    }
}
