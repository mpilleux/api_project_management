<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Contact;

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

    /**
     * @return void
     * @test
     */
    public function it_shows_a_contact()
    {
        $client = factory('App\Client')->create();
        $contact = factory('App\Contact')->create([
            'client_id' => $client->id
        ]);
        $route = "clients/$client->id/contacts/$contact->id";
        $response = $this->get($route)->seeStatusCode(200);
        $this->seeJson($contact->toArray());      
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_contact_with_additional_data()
    {
        Relation::morphMap([
            'contact' => App\Contact::class
        ]); 
        $client = factory('App\Client')->create();
        $contact = factory('App\Contact')->create([
            'client_id' => $client->id
        ]);
        $additional = factory('App\Additional')->create([
            'additionable_id' => $contact->id,
            'additionable_type' => 'contact',
            'key' => 'phone2',
            'value_text' => '9999',
            'value_int' => null 
        ]);
        $route = 'clients/' . $client->id . '/contacts/' . $contact->id . '?with=additionals';
        $response = $this->get($route)->seeStatusCode(200);
        $this->seeJsonContains(['phone2' => '9999']);
    }

    /**
     * @return void
     * @test
     */
    public function it_store_a_contact()
    {
        Relation::morphMap([
            'contact' => App\Contact::class
        ]); 
        $client = factory('App\Client')->create();
        $route = '/clients/' . $client->id . '/contacts';
        $data = [
            'slug' => 'sssddfasd',
            'name' => 'proyectoi de de',
            'email' => 'john@example.com',
            'created_by' => 1,
            'active' => 1,
            'color' => 'red'
        ];
        $response = $this->post($route, $data)->seeStatusCode(201);

        $contact = $client->contacts->first(); 
        $this->seeJson($contact->toArray());
        $this->assertEquals($contact->slug, 'sssddfasd');
        $this->assertEquals($contact->additionals()->first()->key, 'color');
        $this->assertEquals($contact->additionals()->first()->value_text, 'red');
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_contacts()
    {
        Relation::morphMap([
            'contact' => App\Contact::class
        ]); 
        $client = factory('App\Client')->create();
        $contact = factory('App\Contact')->create([
            'client_id' => $client->id,
            'email' => 'old_email@example.com'
        ]);
        $additional = factory('App\Additional')->create([
            'additionable_id' => $contact->id,
            'additionable_type' => 'contact',
            'key' => 'phone2',
            'value_text' => '9999',
            'value_int' => null 
        ]);
        $route = '/clients/' . $client->id . '/contacts/' . $contact->id;
        $data = [
            'email' => 'new_email@example.com',
            'phone2' => '9-(8888)',
            'size' => 2
        ]; 
        $response = $this->put($route, $data)->seeStatusCode(200);

        $edited_contact = Contact::find($contact->id);
        $this->seeJson($edited_contact->toArray());
        $this->assertEquals($edited_contact->email, 'new_email@example.com');
        $this->assertEquals($contact->additionals()->first()->key, 'phone2');
        $this->assertEquals($contact->additionals()->first()->value_text, '9-(8888)');
        
        $this->assertEquals($contact->additionals->last()->key, 'size');
        $this->assertEquals($contact->additionals->last()->value_int, 2);
    }

    /**
     * @return void
     * @test
     */
    public function it_deletes_contacts()
    {
        $contact = factory(contact::class)->create();
        $route = 'clients/' . $contact->client->id . '/contacts/' . $contact->id;
        $response = $this->delete($route)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'data' => [],
                'code' => 200,
                'status' => 'ok',
                'messages' => []
            ]);
        $this->assertCount(0, Contact::all());    
    }
}
