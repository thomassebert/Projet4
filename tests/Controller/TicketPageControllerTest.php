<?php

namespace tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;

class TicketPageControllerTest extends WebTestCase
{

    public function testTicketPageSuccess()
    {
        $client = self::createClient();

        $session = $client->getContainer()->get('session');
        $session->set('user_id', 13);
        $session->save();

        $client->request('GET', '/tickets');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testGetRemainingTickets()
    {
        $client = self::createClient();

        $crawler = $client->request('POST', '/tickets/getRemainingTickets', array(), array(), array(
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
        ), json_encode('20-08-2019'));

        $JSON_response = $client->getResponse()->getContent();


        $this->assertNotEmpty($JSON_response);
        $this->assertEquals('{"number":1000}', $JSON_response);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testCalendarPartSubmit()
    {
        $client = self::createClient();

        $crawler = $client->request('POST', '/tickets/calendarPartSubmit', array(), array(), array(
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
        ), json_encode(['day' => '20-01-2017', 'bookingType' => 'Journée']));

        $JSON_response = $client->getResponse()->getContent();

        $this->assertNotEmpty($JSON_response);
        $this->assertEquals('{"message":"Il n\u0027est pas possible de r\u00e9server un billet pour un jour pass\u00e9!"}', $JSON_response);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}


