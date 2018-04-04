<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Client;
use App\Project;

class ClientTest extends TestCase
{
    use DatabaseMigrations, ModelHelpers;
    
    /**
     * @return void
     * @test
     */
    public function it_list_all_clients()
    {
        $clients = factory('App\Client', 4)->create();
        $response = $this->get('/clients')->seeStatusCode(200);
        $this->seeJson($clients->first()->toArray());
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_a_client()
    {
        $client = factory('App\Client')->create();
        $route = 'clients/' . $client->id;
        $response = $this->get($route)->seeStatusCode(200);
        $this->seeJson($client->toArray());        
    }

    /**
     * @return void
     * @test
     */
    public function it_store_a_client()
    {
        $route = '/clients';
        $data = [
            'slug' => 'sssddfasd',
            'name' => 'proyectoi de de',
            'created_by' => 1,
            'active' => 1
        ];
        $response = $this->post($route, $data)->seeStatusCode(201);

        $client = Client::first(); 
        $this->seeJson($client->toArray());
        $this->assertEquals($client->slug, 'sssddfasd');
    }

    /**
     * @return void
     * @test
     */
    public function it_update_clients()
    {
        $client = factory(Client::class)->create(['name' => 'original']);
        $data = ['name' => 'editado']; 
        $route = 'clients/' . $client->id;
        $response = $this->put($route, $data)->seeStatusCode(200);

        $edited_client = Client::find($client->id);
        $this->seeJson($edited_client->toArray());
        $this->assertEquals($edited_client->name, 'editado');
    }

    /**
     * @return void
     * @test
     */
    public function it_deletes_clients()
    {
        $client = factory(Client::class)->create();
        $route = 'clients/' . $client->id;
        $response = $this->delete($route)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'data' => [],
                'code' => 200,
                'status' => 'ok',
                'messages' => []
            ]);
        $this->assertCount(0, Client::all());    
    }

    /**
     * @return void
     * @test
     */
    public function it_has_many_projects()
    {
        $this->assertBelongsToMany('projects', Project::class, Client::class);
    }

    /**
     * @return void
     * @test
     */
    public function it_loads_projects()
    {
        $client = factory('App\Client')->create();
        $projects = factory('App\Project', 2)->create();
        $projects->each(function($project) use($client){
            $client->projects()->save($project);
        });
        $this->assertCount(2, $client->projects);
        $route = 'clients/' . $client->id . '?with=projects';
        $response = $this->get($route)->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJson(['slug' => $client->projects->first()->slug]);
    }
}
