<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Provider;
use App\Project;
use App\Scope;

class ScopeTest extends TestCase
{
    use DatabaseMigrations, ModelHelpers;
    
    /**
     * @return void
     * @test
     */
    public function it_list_all_scopes()
    {
        $collection = factory('App\Scope', 4)->create();
        $response = $this->get('/scopes')->seeStatusCode(200);
        $this->seeJson($collection->first()->toArray());
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_a_scope()
    {
        $entity = factory('App\Scope')->create();
        $route = 'scopes/' . $entity->id;
        $response = $this->get($route)->seeStatusCode(200);
        $this->seeJson($entity->toArray());        
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_scope_with_additional_data()
    {
        Relation::morphMap([
            'scope' => App\Scope::class
        ]);
        $entity = factory('App\Scope')->create();
        $additional = factory('App\Additional')->create([
            'additionable_id' => $entity->id,
            'additionable_type' => 'scope',
            'key' => 'duracion',
            'value_text' => null,
            'value_int' => 12 
        ]);
        $route = 'scopes/' . $entity->id . '?with=additionals';
        $response = $this->get($route)->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJsonContains(['duracion' => 12]);
    }

    /**
     * @return void
     * @test
     */
    public function it_store_a_scope()
    {
        $route = '/scopes';
        $provider = factory('App\Provider')->create();
        $project = factory('App\Project')->create();
        $data = [
            'slug' => 'sssddfasd',
            'name' => 'proyectoi de de',
            'active' => 1,
            'provider_id' => $provider->id,
            'project_id' => $project->id,
            'color' => 'red'
        ];
        $response = $this->post($route, $data)->seeStatusCode(201);

        $entity = Scope::first(); 
        $this->seeJson($entity->toArray());
        $this->assertEquals($entity->slug, 'sssddfasd');
        $this->assertEquals($entity->additionals()->first()->key, 'color');
        $this->assertEquals($entity->additionals()->first()->value_text, 'red');
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_scopes()
    {
        $entity = factory(Scope::class)->create(['name' => 'original']);
        $data = [
            'name' => 'editado',
            'color' => 'blue',
            'size' => '2'
        ]; 
        $route = 'scopes/' . $entity->id;
        $response = $this->put($route, $data)->seeStatusCode(200);

        $edited = Scope::find($entity->id);
        $this->seeJson($edited->toArray());
        $this->assertEquals($edited->name, 'editado');
        $this->assertEquals($entity->additionals()->first()->key, 'color');
        $this->assertEquals($entity->additionals()->first()->value_text, 'blue');
        
        $this->assertEquals($entity->additionals->last()->key, 'size');
        $this->assertEquals($entity->additionals->last()->value_int, 2);
    }

    /**
     * @return void
     * @test
     */
    public function it_deletes_scopes()
    {
        $entity = factory(Scope::class)->create();
        $route = 'scopes/' . $entity->id;
        $response = $this->delete($route)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'data' => [],
                'code' => 200,
                'status' => 'ok',
                'messages' => []
            ]);
        $this->assertCount(0, Scope::all());    
    }

    /**
     * @return void
     * @test
     */
    public function it_belongs_to_projects()
    {
        return $this->assertBelongsTo('project', Project::class, Scope::class);
    }

    /**
     * @return void
     * @test
     */
    public function it_belongs_to_providers()
    {
        return $this->assertBelongsTo('provider', Provider::class, Scope::class);
    }

    /**
     * @return void
     * @test
     */
    public function it_loads_project()
    {
        $provider = factory('App\Provider')->create();
        $project = factory('App\Project')->create();
        $scope = factory('App\Scope')->create([
            'provider_id' => $provider->id,
            'project_id' => $project->id
        ]);
        $route = 'scopes/' . $scope->id . '?with=project,provider';
        $response = $this->get($route)->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJson(['slug' => $scope->project->slug]);
        $this->seeJson(['slug' => $scope->provider->slug]);
    }
}
