<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic functional test of the services homepage.
     *
     * @return void
     */
    public function testServicepage()
    {
        $this->visit( env('BASE_URL').'/services' )
         ->click('Add Service')
         ->seePageIs (env('BASE_URL').'/service/create');
    }

    /**
     * A basic functional test of registering a new service.
     *
     * @return void
     */
    public function testNewUserRegistration()
    {
        $this->visit(env('BASE_URL').'/service/create')
             ->type('Example.com Site', 'name')
             ->type(80, 'port')
             ->type('A simple wobsite', 'bio')
             ->type('http://example.org', 'url')
             ->type('Edmonton', 'city')
             ->type('Canada', 'country')
             ->press('Submit')
             ->see('Example.com Site');
    }
}