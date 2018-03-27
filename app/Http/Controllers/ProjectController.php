<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectCollection;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $default_aditional = [
        'code' => 200,
        'status' => 'ok',
        'messages' => []
    ];

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
        
        return $this->responseOkWithCollection($projects);
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
        $project = Project::create($request->all());
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
        $project = Project::find($id);
        $project->update($request->all());
        $new_project = Project::find($id);
        return $this->responseOkWithResource($new_project);
    }

    /**
     * Ok response with resource
     *
     * @param [type] $resource
     * @param [type] $aditionals
     * @return void
     */
    protected function responseOkWithResource($resource, $aditionals = [])
    {
        return (new ProjectResource($resource))
            ->additional(array_merge($aditionals, $this->default_aditional));
    }

    /**
     * Ok response with collection
     *
     * @param [type] $resource
     * @param [type] $aditionals
     * @return void
     */
    protected function responseOkWithCollection($collection, $aditionals = [])
    {
        return (new ProjectCollection($collection))
            ->additional(array_merge($aditionals, $this->default_aditional));
    }
}
