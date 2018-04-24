<?php

use Illuminate\Database\Seeder;
use App\Project;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Client::class, 2)->create();
        factory(App\Contact::class, 2)->create([
            'client_id' => 1
        ]);
    }
}
