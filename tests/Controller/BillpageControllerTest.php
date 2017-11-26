<?php 

namespace tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;

use Louvre\BilletBundle\Entity\ShoppingCart;
use Louvre\BilletBundle\Entity\User;

class BillpageControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->em = $kernel->getContainer()
            ->get('doctrine.orm.entity_manager');
    }

    public function testMailIsSentAndContentIsOk()
    {
        $client = static::createClient();

        // Enable the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $shoppingCart = $this->em->getRepository(ShoppingCart::class)->find(319);
        $user = $this->em->getRepository(User::class)->find(12);

        $session = $client->getContainer()->get('session');
        $session->set('shopping_cart', serialize($shoppingCart));
        $session->set('user', $user->getId());
        $session->save();

        $crawler = $client->request('POST', '/confirmation');

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // Check that an email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('Confirmation de commande', $message->getSubject());
        $this->assertEquals('billetterie@louvre.thomassebert.com', key($message->getFrom()));
    }

}


