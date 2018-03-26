<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
     * @return void
     * @test
     */
    public function it_list_all_projects()
    {
        $projects = factory('App\Project', 4)->create();
        $this->get(route('project.index'))->seeStatusCode(200);
        $this->seeJson($projects->first()->toArray());
    }
}
