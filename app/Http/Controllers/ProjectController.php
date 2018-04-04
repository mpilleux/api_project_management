<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectCollection;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

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
        $project = Project::findOrFail($id);
        $this->loadRelations($project, 'additionals');
        $project_resource = new ProjectResource($project);
        return $this->responseOkWithResource($project_resource);
    }

    /**
     * Store Project
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $project = Project::create($request->all());
        $project_resource = new ProjectResource($project);
        return $this->responseOkWithResource($project_resource, ['code' => 201]);
    }

    /**
     * Store Project
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        $project->update($request->all());
        $new_project = new ProjectResource(Project::find($id));
        return $this->responseOkWithResource($new_project);
    }

    public function delete($id)
    {
        $project = Project::find($id);
        $delete = $project->delete();
        return $this->responseOk();
    }
}
