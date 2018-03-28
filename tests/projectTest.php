<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Project;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
     * @return void
     * @test
     */
    public function it_list_all_projects()
    {
        $projects = factory('App\Project', 4)->create();
        $response = $this->get(route('project.index'))->seeStatusCode(200);
        $this->seeJson($projects->first()->toArray());
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_a_project()
    {
        $project = factory('App\Project')->create();
        $route = route('project.show', ['id' => $project->id]);
        $response = $this->get($route)->seeStatusCode(200);
        $this->seeJson($project->toArray());        
    }

    /**
     * @return void
     * @test
     */
    public function it_store_a_project()
    {
        $route = route('project.store');
        $data = [
            'slug' => 'sssddfasd',
            'name' => 'proyectoi de de',
            'created_by' => 1,
            'active' => 1
        ];
        $response = $this->post($route, $data)->seeStatusCode(201);

        $project = Project::first(); 
        $this->seeJson($project->toArray());
        $this->assertEquals($project->slug, 'sssddfasd');
    }

    /**
     * @return void
     * @test
     */
    public function it_update_projects()
    {
        $project = factory(Project::class)->create(['name' => 'original']);
        $data = ['name' => 'editado']; 
        $route = route('project.update', ['id' => $project->id]);
        $response = $this->put($route, $data)->seeStatusCode(200);

        $edited_project = Project::find($project->id);
        $this->seeJson($edited_project->toArray());
        $this->assertEquals($edited_project->name, 'editado');
    }

    /**
     * @return void
     * @test
     */
    public function it_deletes_projects()
    {
        $project = factory(Project::class)->create();
        $route = route('project.delete', ['id' => $project->id]);
        $response = $this->delete($route)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'data' => [],
                'code' => 200,
                'status' => 'ok',
                'messages' => []
            ]);
    }
}
