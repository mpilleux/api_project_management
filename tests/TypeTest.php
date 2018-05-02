<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Type;

class TypeTest extends TestCase
{
    use DatabaseMigrations, ModelHelpers;
    
    /**
     * @return void
     * @test
     */
    public function it_list_all_types()
    {
        $collection = factory('App\Type', 4)->create();
        $response = $this->get('/types')->seeStatusCode(200);
        $this->seeJson($collection->first()->toArray());
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_a_type()
    {
        $entity = factory('App\Type')->create();
        $route = 'types/' . $entity->id;
        $response = $this->get($route)->seeStatusCode(200);
        $this->seeJson($entity->toArray());        
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_types_with_additional_data()
    {
        Relation::morphMap([
            'type' => App\Type::class
        ]);
        $entity = factory('App\Type')->create();
        $additional = factory('App\Additional')->create([
            'additionable_id' => $entity->id,
            'additionable_type' => 'type',
            'key' => 'duracion',
            'value_text' => null,
            'value_int' => 12 
        ]);
        $route = 'types/' . $entity->id . '?with=additionals';
        $response = $this->get($route)->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJsonContains(['duracion' => 12]);
    }

    /**
     * @return void
     * @test
     */
    public function it_store_a_type()
    {
        $route = '/types';
        $data = [
            'slug' => 'sssddfasd',
            'name' => 'proyectoi de de',
            'active' => 1,
            'color' => 'red'
        ];
        $response = $this->post($route, $data)->seeStatusCode(201);

        $entity = Type::first(); 
        $this->seeJson($entity->toArray());
        $this->assertEquals($entity->slug, 'sssddfasd');
        $this->assertEquals($entity->additionals()->first()->key, 'color');
        $this->assertEquals($entity->additionals()->first()->value_text, 'red');
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_types()
    {
        $entity = factory(Type::class)->create(['name' => 'original']);
        $data = [
            'name' => 'editado',
            'color' => 'blue',
            'size' => '2'
        ]; 
        $route = 'types/' . $entity->id;
        $response = $this->put($route, $data)->seeStatusCode(200);

        $edited = Type::find($entity->id);
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
    public function it_deletes_types()
    {
        $entity = factory(Type::class)->create();
        $route = 'types/' . $entity->id;
        $response = $this->delete($route)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'data' => [],
                'code' => 200,
                'status' => 'ok',
                'messages' => []
            ]);
        $this->assertCount(0, Type::all());    
    }

    /**
     * @return void
     * @test
     */
    public function it_belongs_to_parent_type()
    {
        $this->assertBelongsTo('parent', Type::class, Type::class);
    }

    /**
     * @return void
     * @test
     */
    public function it_has_many_childs()
    {
        $this->assertHasMany('childs', Type::class, Type::class);
    }

    /**
     * @return void
     * @test
     */
    public function it_loads_projects()
    {
        $type = factory('App\Type')->create();
        $projects = factory('App\Project', 2)->create([
            'type_id' => $type->id,
        ]);
        $route = 'types/' . $type->id . '?with=projects';
        $response = $this->get($route)->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJson(['slug' => $projects->first()->slug]);
        $this->seeJson(['slug' => $projects->last()->slug]);
    }
}
