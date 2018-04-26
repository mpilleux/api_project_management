<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Provider;
use App\Project;
use App\Scope;

class ProviderTest extends TestCase
{
    use DatabaseMigrations, ModelHelpers;
    
    /**
     * @return void
     * @test
     */
    public function it_list_all_providers()
    {
        $providers = factory('App\Provider', 4)->create();
        $response = $this->get('/providers')->seeStatusCode(200);
        $this->seeJson($providers->first()->toArray());
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_a_provider()
    {
        $provider = factory('App\Provider')->create();
        $route = 'providers/' . $provider->id;
        $response = $this->get($route)->seeStatusCode(200);
        $this->seeJson($provider->toArray());        
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_provider_with_additional_data()
    {
        Relation::morphMap([
            'provider' => App\Provider::class
        ]);
        $provider = factory('App\Provider')->create();
        $additional = factory('App\Additional')->create([
            'additionable_id' => $provider->id,
            'additionable_type' => 'provider',
            'key' => 'duracion',
            'value_text' => null,
            'value_int' => 12 
        ]);
        $route = 'providers/' . $provider->id . '?with=additionals';
        $response = $this->get($route)->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJsonContains(['duracion' => 12]);
    }

    /**
     * @return void
     * @test
     */
    public function it_store_a_provider()
    {
        $route = '/providers';
        $data = [
            'slug' => 'sssddfasd',
            'name' => 'proyectoi de de',
            'active' => 1,
            'color' => 'red'
        ];
        $response = $this->post($route, $data)->seeStatusCode(201);

        $provider = Provider::first(); 
        $this->seeJson($provider->toArray());
        $this->assertEquals($provider->slug, 'sssddfasd');
        $this->assertEquals($provider->additionals()->first()->key, 'color');
        $this->assertEquals($provider->additionals()->first()->value_text, 'red');
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_provider()
    {
        $provider= factory(Provider::class)->create(['name' => 'original']);
        $data = [
            'name' => 'editado',
            'color' => 'blue',
            'size' => '2'
        ]; 
        $route = 'providers/' . $provider->id;
        $response = $this->put($route, $data)->seeStatusCode(200);

        $edited_provider = Provider::find($provider->id);
        $this->seeJson($edited_provider->toArray());
        $this->assertEquals($edited_provider->name, 'editado');
        $this->assertEquals($provider->additionals()->first()->key, 'color');
        $this->assertEquals($provider->additionals()->first()->value_text, 'blue');
        
        $this->assertEquals($provider->additionals->last()->key, 'size');
        $this->assertEquals($provider->additionals->last()->value_int, 2);
    }

    /**
     * @return void
     * @test
     */
    public function it_deletes_providers()
    {
        $provider = factory(Provider::class)->create();
        $route = 'providers/' . $provider->id;
        $response = $this->delete($route)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'data' => [],
                'code' => 200,
                'status' => 'ok',
                'messages' => []
            ]);
        $this->assertCount(0, Provider::all());    
    }

    /**
     * @return void
     * @test
     */
    public function it_has_many_projects()
    {
        $this->assertRespondsTo('projects', Provider::class);
        $provider = factory(Provider::class)->create();
        $project = factory(Project::class)->create();
        $scope = factory(Scope::class)->create([
            'provider_id' => $provider->id,
            'project_id' => $project->id
        ]);
        $this->assertEquals(1, $provider->projects->count());
    }

    /**
     * @return void
     * @test
     */
    public function it_loads_projects()
    {
        $provider = factory('App\Provider')->create();
        $projects = factory('App\Project', 2)->create();
        $projects->each(function($project) use($provider){
            factory(Scope::class)->create([
                'provider_id' => $provider->id,
                'project_id' => $project->id
            ]);
        });
        $this->assertCount(2, $provider->projects);
        $route = 'providers/' . $provider->id . '?with=projects';
        $response = $this->get($route)->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJson(['slug' => $provider->projects->first()->slug]);
    }
}
