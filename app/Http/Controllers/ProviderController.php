<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProviderRepository;

class ProviderController extends Controller
{
    /**
     * @var ProviderRepository
     */
    protected $providerRepo;

    /**
     * @param ProviderRepository $providerRepo
     */
    public function __construct(ProviderRepository $providerRepo) {
        $this->providerRepo = $providerRepo;
    }

    /**
     * List Provider
     *
     * @return void
     */
    public function index(Request $request)
    {
        $providers = $this->providerRepo->all($request);
        return $this->responseOkWithCollection($providers);
    }

    /**
     * Show a provider
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $provider = $this->providerRepo->findOrFail($id);
        $this->loadRelations($provider, $request->query('with'));
        return $this->responseOkWithResource($provider);
    }

    /**
     * Store provider
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $provider = $this->providerRepo->create($request);
        return $this->responseOkWithResource($provider, ['code' => 201]);
    }

    /**
     * Store Provider
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $id)
    {
        $provider = $this->providerRepo->findOrFail($id);
        $update = $this->providerRepo->update($provider, $request);
        $provider = $this->providerRepo->findOrFail($provider->id);
        return $this->responseOkWithResource($provider);
    }

    /**
     * Delete a resource
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $delete = $this->providerRepo->delete($id);
        if(!$delete){
            return $this->responseError('Problem deleting the provoider');
        }
        return $this->responseOk();
    }
}
