<?php

class IndexControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     */
    public function testRouteIndex()
    {
        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->getStatusCode());
    }

}
