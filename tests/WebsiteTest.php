<?php

class WebsiteTest extends TestCase
{

    /**
     * A basic functional test of the homepage.
     *
     * @return void
     */
    public function testHomepage()
    {
        $this->visit( env('BASE_URL').'/' )
             ->see('Hyperboria meet Hub.');
    }

    /**
     * A test to ensure data and migration collection.
     *
     * @return void
     */
    public function testMigrationsAndData()
    {
        $this->visit( env('BASE_URL').'/'.'nodes/'.env('CJDNS_IP') )
             ->seePageIs( env('BASE_URL').'/'.'nodes/'.env('CJDNS_IP') );
    }

    /**
     * A test to ensure the source code page exists and contains the github link.
     *
     * @return void
     */
    public function testSourceCodeLink()
    {
        $this->visit( env('BASE_URL').'/site/source-code' )
             ->see( 'https://github.com/dansup/hub' );
    }
}