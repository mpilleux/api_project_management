<?php

namespace App\Repositories;

use App\Project;
use Illuminate\Http\Request;

class ProjectRepository extends Repository
{
    use ManageAdditionals;

    /**
     * Find or fail by id
     *
     * @param integer $id
     * @return void
     */
    public function findOrFail($id)
    {
        return Project::with('additionals')->findOrFail($id);  
    }

    /**
     * Create the project from the request
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $project = Project::create($this->getProjectData($request));
        $this->storeAdditionalsForEntity($project, $this->getAdditionalData($request));
        return $project->load('additionals');
    }

    /**
     * update the project
     *
     * @param Project $project
     * @param Request $request
     * @return void
     */
    public function update(Project $project, Request $request)
    {
        $update = $project->update($this->getDataWithoutAdditionals($request));
        $this->updateAdditionalsForEntity($project, $this->getAdditionalData($request));
        return $update;
    }

    /**
     * Delete the project with its additionals
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $project = $this->findOrFail($id);
        $delete = $project->delete();
        $this->deleteAdditionalsForEntityId($id, $type = 'project');
        return $delete;    
    }

    /**
     * Get the data for store
     *
     * @param Request $request
     * @return void
     */
    protected function getProjectData(Request $request)
    {
        return [
            'slug' => $request->get('slug'),
            'name' => $request->get('name'),
            'active' => $request->get('active'),
            'created_by' => $request->get('created_by'),
        ];
    }

    /**
     * Get the fields for the model
     *
     * @return void
     */
    protected function getModelFields()
    {
        return [
            'slug', 
            'name',
            'active',
            'created_by',
            'updated_by',
            'deleted_by',
        ];
    }
}
