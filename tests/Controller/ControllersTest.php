<?php

namespace tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControllersTest extends WebTestCase
{

    public function testHomePageSuccess()
    {
        $client = self::createClient();
        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testProtectedPagesFail($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array('/confirmation'),
            array('/tickets'),
            array('/tickets/getFullDays'),
            array('/tickets/getRemainingTickets'),
            array('/tickets/proceedPayment'),
            array('/tickets/calendarPartSubmit'),
            
        );
    }

    public function testNotFoundError()
    {
        $client = self::createClient();
        $client->request('GET', '/error');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

}


