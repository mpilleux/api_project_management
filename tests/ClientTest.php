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
    public function it_shows_client_with_additional_data()
    {
        $client = factory('App\Client')->create();
        $additional = factory('App\Additional')->create([
            'additionable_id' => $client->id,
            'additionable_type' => 'App\Client',
            'key' => 'duracion',
            'value_text' => null,
            'value_int' => 12 
        ]);
        $route = 'clients/' . $client->id . '?with=additionals';
        $response = $this->get($route)->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJsonContains(['duracion' => 12]);
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
            'active' => 1,
            'color' => 'red'
        ];
        $response = $this->post($route, $data)->seeStatusCode(201);

        $client = Client::first(); 
        $this->seeJson($client->toArray());
        $this->assertEquals($client->slug, 'sssddfasd');
        $this->assertEquals($client->additionals()->first()->key, 'color');
        $this->assertEquals($client->additionals()->first()->value_text, 'red');
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_clients()
    {
        $client = factory(Client::class)->create(['name' => 'original']);
        $data = [
            'name' => 'editado',
            'color' => 'blue',
            'size' => '2'
        ]; 
        $route = 'clients/' . $client->id;
        $response = $this->put($route, $data)->seeStatusCode(200);

        $edited_client = Client::find($client->id);
        $this->seeJson($edited_client->toArray());
        $this->assertEquals($edited_client->name, 'editado');
        $this->assertEquals($client->additionals()->first()->key, 'color');
        $this->assertEquals($client->additionals()->first()->value_text, 'blue');
        
        $this->assertEquals($client->additionals->last()->key, 'size');
        $this->assertEquals($client->additionals->last()->value_int, 2);
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
