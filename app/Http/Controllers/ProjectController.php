<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectCollection;
use App\Project;
use App\Http\Resources\Project as ProjectResource;
use Illuminate\Http\Request;
use App\Client;
use App\Repositories\AdditionalsRepository;
use App\Repositories\ProjectRepository;

class ProjectController extends Controller
{
    /**
     * @var ProjectRepository
     */
    protected $projectRepo;

    /**
     * @param ProjectRepository $projectRepo
     */
    public function __construct(ProjectRepository $projectRepo) {
        $this->projectRepo = $projectRepo;
    }

    /**
     * List projects
     *
     * @return void
     */
    public function index(Request $request)
    {
        $fields = $request->query('fields', '*');
        $fields = explode(",", $fields);
        $sort = $request->query('sort', 'id');
        $order = $request->query('order', 'desc');
        $filters = $request->except('fields', 'sort', 'order');
        $projects = Project::select($fields)->where($filters)->orderBy($sort, $order)->get();
        $projects_collection = new ProjectCollection($projects);
        
        return $this->responseOkWithCollection($projects_collection);
    }

    /**
     * Show a project
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $project = $this->projectRepo->findOrFail($id);
        $this->loadRelations($project, $request->query('with'));
        return $this->responseOkWithResource($project);
    }

    /**
     * Store Project
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $project = $this->projectRepo->create($request);
        return $this->responseOkWithResource($project, ['code' => 201]);
    }

    /**
     * Store Project
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $id)
    {
        $project = $this->projectRepo->findOrFail($id);
        $update = $this->projectRepo->update($project, $request);
        return $this->responseOkWithResource($project);
    }

    /**
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $delete = $this->projectRepo->delete($id);
        if(!$delete){
            return $this->responseError('Problem deleting the project');
        }
        return $this->responseOk();
    }
}
