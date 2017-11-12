<?php /*

namespace tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;

class BillpageControllerTest extends WebTestCase
{

    public function testMailIsSentAndContentIsOk()
    {
        $client = static::createClient();

        // Enable the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('POST', '/confirmation');

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // Check that an email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('Confirmation de commande', $message->getSubject());
        $this->assertEquals('billetterie@louvre.thomassebert.fr', key($message->getFrom()));
    }

}*/


