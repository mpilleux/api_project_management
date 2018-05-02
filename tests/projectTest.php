<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Project;
use App\Type;
use Illuminate\Database\Eloquent\Relations\Relation;

class ProjectTest extends TestCase
{
    use DatabaseMigrations, ModelHelpers;
    
    /**
     * @return void
     * @test
     */
    public function it_list_all_projects()
    {
        $projects = factory('App\Project', 4)->create();
        $response = $this->get('/projects')->seeStatusCode(200);
        $this->seeJson($projects->first()->toArray());
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_a_project()
    {
        $project = factory('App\Project')->create();
        $route = 'projects/' . $project->id;
        $response = $this->get($route)->seeStatusCode(200);
        $this->seeJson($project->toArray());        
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_aproject_with_additional_data()
    {
        Relation::morphMap([
            'project' => App\Project::class
        ]);
        $project = factory('App\Project')->create();
        $additional = factory('App\Additional')->create([
            'additionable_id' => $project->id,
            'additionable_type' => 'project',
            'key' => 'duracion',
            'value_text' => null,
            'value_int' => 12 
        ]);
        $route = 'projects/' . $project->id . '?with=additionals';
        $response = $this->get($route)->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJsonContains(['duracion' => 12]);
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_a_project_with_type()
    {
        $type = factory('App\Type')->create();
        $project = factory('App\Project')->create([
            'type_id' => $type->id
        ]);
        $route = 'projects/' . $project->id . '?with=type';
        $response = $this->get($route)->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJsonContains(['slug' => $type->slug]);
        
    }

    /**
     * @return void
     * @test
     */
    public function it_store_a_project()
    {
        //esta en el appServiceProvider, los tests no lo corren
        // hay que ponerlo en un up method
        Relation::morphMap([
            'project' => App\Project::class,
            'client' => App\Client::class,
        ]); 

        $route = '/projects';
        $data = [
            'slug' => 'sssddfasd',
            'name' => 'proyectoi de de',
            'created_by' => 1,
            'active' => 1,
            'color' => 'red'
        ];
        $response = $this->post($route, $data)->seeStatusCode(201);

        $project = Project::first();
        $this->seeJson($project->toArray());
        $this->assertEquals($project->slug, 'sssddfasd');
        $this->assertEquals($project->additionals()->first()->key, 'color');
        $this->assertEquals($project->additionals()->first()->value_text, 'red');
    }

    /**
     * @return void
     * @test
     */
    public function it_update_projects()
    {
        //esta en el appServiceProvider, los tests no lo corren
        // hay que ponerlo en un up method
        Relation::morphMap([
            'project' => App\Project::class,
            'client' => App\Client::class,
        ]);

        $project = factory(Project::class)->create(['name' => 'original']);
        $project->additionals()->create(['key' => 'color', 'value_text' => 'red']);
        $data = [
            'name' => 'editado',
            'color' => 'blue',
            'size' => '2'
        ]; 
        $route = 'projects/' . $project->id;
        $response = $this->put($route, $data)->seeStatusCode(200);

        $edited_project = Project::find($project->id);
        $this->seeJson($edited_project->toArray());
        $this->assertEquals($edited_project->name, 'editado');
        $this->assertEquals($project->additionals()->first()->key, 'color');
        $this->assertEquals($project->additionals()->first()->value_text, 'blue');
        
        $this->assertEquals($project->additionals->last()->key, 'size');
        $this->assertEquals($project->additionals->last()->value_int, 2);
    }

    /**
     * @return void
     * @test
     */
    public function it_deletes_projects()
    {
        $project = factory(Project::class)->create();
        $route = 'projects/' . $project->id;
        $response = $this->delete($route)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'data' => [],
                'code' => 200,
                'status' => 'ok',
                'messages' => []
            ]);
    }

    /**
     * @return void
     * @test
     */
    public function it_has_a_type()
    {
        $this->assertBelongsTo('type', Type::class, Project::class);
    }

}
