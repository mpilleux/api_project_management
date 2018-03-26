<?php

namespace App\Http\Controllers;

use App\Project;


class ProjectController extends Controller
{
    /**
     * List projects
     *
     * @return void
     */
    public function index()
    {
        $projects = Project::all();
        return $projects;    
    }

    
}
