<?php

namespace Louvre\BilletBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Louvre\BilletBundle\Entity\User;
use Louvre\BilletBundle\Entity\Booking;
use Louvre\BilletBundle\Entity\ShoppingCart;
use Louvre\BilletBundle\Entity\Calendar;

use Louvre\BilletBundle\Form\BookingType;

class BillpageController extends Controller
{
    public function getPageAction(Request $request, SessionInterface $session)
    {
    	$dt = $this->get('doctrine_tools');

    	if(!is_null($session->get('user_id'))) 
    	{
    		if(!is_null($session->get('shopping_cart'))) 
    		{
    			$shoppingCart =  unserialize($session->get('shopping_cart'));
    			
    			$session->remove('user_id');
		    	$session->remove('shopping_cart');
		    	
		    	$bookings = $dt->getB()->findByShoppingCartId($shoppingCart->getId());

    			$html = $this->renderView('LouvreBilletBundle:Emails:bodyPdf.html.twig', array(
    				'datas' => $shoppingCart,
    				'bookings' => $bookings
		        ));

		        $snappy = $this->get('knp_snappy.pdf');

		        $pdf = new Response(
		            $snappy->getOutputFromHtml($html,
		                array('orientation'         => 'portrait',
		                    'enable-javascript'     => true,
		                    'javascript-delay'      => 0,
		                    'no-background'         => false,
		                    'lowquality'            => false,
		                    'page-size'             => 'A4',
		                    'encoding'              => 'utf-8',
		                    'images'                => true,
		                    'cookie'                => array(),
		                    'dpi'                   => 300,
		                    'image-dpi'             => 300,
		                    'enable-external-links' => true,
		                    'enable-internal-links' => true,
		                    'header-spacing'        => 5,
		                    'footer-spacing'        => 5,
		            )),
		            200,
		            [
		                'Content-Type'        => 'application/pdf',
		                'Content-Disposition' => sprintf('attachment; filename='.$shoppingCart->getBookingKey().'pdf'),
		            ]
		        );
    			$attachment = new \Swift_Attachment($pdf, 'billets.pdf', 'application/pdf');
		    	
		        $message = (new \Swift_Message('Musée du Louvre'))
		        	->setContentType("text/html")
                    ->setSubject('Confirmation de commande')
			        ->setFrom('billetterie@louvre.thomassebert.fr')
			        ->setTo($shoppingCart->getUserId()->getEmail())
			        ->setBody(
			            $this->renderView(
			                'LouvreBilletBundle:Emails:confirmation.html.twig',
			                array('shoppingCart' => $shoppingCart)
			            ),
			            'text/html'
			        )
			        ->attach($attachment);
			    ;

			    $this->get('mailer')->send($message);

		    	return $this->render('LouvreBilletBundle:Billpage:billpage.html.twig',
			                array('shoppingCart' => $shoppingCart)
			            );
		    }
		    else 
		    {
		    	return $this->redirectToRoute('louvre_billet_ticketpage');
		    }
	    }
	    else
	    {
	    	return $this->redirectToRoute('louvre_billet_homepage');
	    }

    }

    
}

