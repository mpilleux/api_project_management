<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\Relations\Relation;

class ClientContactTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
     * @return void
     * @test
     */
    public function it_list_all_contacts_for_a_client()
    {
        $client = factory('App\Client')->create();
        $contacts = factory('App\Contact', 3)->create([
            'client_id' => $client->id
        ]);
        $response = $this->get("clients/$client->id/contacts")->seeStatusCode(200);
        $phone = $contacts->first()->phone;
        $this->seeJson(['phone' => $phone]);
    }
}
