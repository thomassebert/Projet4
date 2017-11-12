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
use Louvre\BilletBundle\Forms\BookingType;

class TicketpageController extends Controller
{


    public function getPageAction(Request $request, SessionInterface $session)
    {
        if(!is_null($session->get('user_id')))
        {
            // SERVICES
            // 
            $em = $this->getDoctrine()->getManager();
            $repositoryUser = $em->getRepository(User::class);
            $repositoryCalendar = $em->getRepository(Calendar::class);
            $repositoryShoppingCart = $em->getRepository(ShoppingCart::class);

            $doctrine = array('user' => $repositoryUser, 'calendar' => $repositoryCalendar, 'shoppingCart' => $repositoryShoppingCart);

            // RECUPERE L'UTILISATEUR
            // 
            $user = $this->getSessionUser($doctrine['user'], $session);

            // CREATION DU FORMULAIRE BOOKING
            // 
        	$form = $this->createForm(BookingType::class);
    	    $form->handleRequest($request);

            // SI LE FORMULAIRE A ETE VALIDE
            // 
    	    if ($form->isSubmitted() && $form->isValid()) {
            	
                // RECUPERE LES DONNEES DU FORMULAIRE
                // 
                $datas = $form->getData();

                // RECUPERE L'OBJET CALENDAR A PARTIR DE LA DATE CHOISIE
                // 
                $calendar = $doctrine['calendar']->findOneByDay($datas['calendar']->getDay());
                if(is_null($calendar))
                {
                    $calendar = new Calendar();
                    $calendar->setDay($datas['calendar']->getDay());
                    $calendar->setBookings(count($datas['tickets']));
                }
                else
                {
                    $calendar->setBookings($calendar->getBookings() + count($datas['tickets']));
                }
                

                // RECUPERE L'OBJET SHOPPINGCART A PARTIR DE LA CLE DE RESERVATION
                // 
                $shoppingCart = $doctrine['shoppingCart']->findOneByBookingKey($datas['key']);

                // POUR CHAQUE TICKET,
                // 
                foreach ($datas['tickets'] as $data) {

                    // ON DONNE DES VALEURS AUX ATTRIBUTS VIDES: PRIX, PANIER, DATE
                    // 
                    $data->setTicketPrice($this->getPrice($calendar->getBookingType(), $data->getBirthDate(), $data->getDiscountTicket()));
                    $data->setShoppingCartId($shoppingCart);
                    $data->setCalendarId($calendar);
                    $data->setBookingType($datas['calendar']->getBookingType());

                    // ON MET A JOUR LE PRIX TOTAL DU PANIER
                    // 
                    $shoppingCart->setBookingPrice($data->getTicketPrice());
                    
                    $em->persist($data);
                }

                // ON MET A JOUR L'ETAT DU PANIER AINSI QUE SA DATE
                // 
                $shoppingCart->setBookingDate(new \Datetime());
                $shoppingCart->setState('full');

                // ON ENREGISTRE EN BDD LE PANIER, LES TICKETS, LE CALENDRIER
                // 
                $em->persist($calendar);
                $em->persist($shoppingCart);
                $em->flush();

                $session->set('shopping_cart', serialize($shoppingCart));

                return $this->redirectToRoute('louvre_billet_billpage');
            }

            // SINON, ON CREE UN NOUVEAU PANIER
            // 
            $shoppingCart = $this->createShoppingCart($user, $em);

        	return $this->render('LouvreBilletBundle:Ticketpage:ticketpage.html.twig', array(
        	'form' => $form->createView(),
            'key' => $shoppingCart->getBookingKey(),
            'user' => $user
        	));
        }
        else
        {
            return $this->redirectToRoute('louvre_billet_homepage');
        }

    }




    public function getSessionUser($repositoryUser, SessionInterface $session) 
    {
        $userId = $session->get('user_id');
        $em = $this->getDoctrine()->getManager();
        $repositoryUser = $em->getRepository(User::class);
            
        $user = $repositoryUser->findOneById($userId);

        return $user;
    }




    public function createShoppingCart($user, $em)
    {
        $shoppingCart = new ShoppingCart();
        $shoppingCart->newShoppingCart($user);
        $shoppingCart->setBookingDate(new \Datetime());

        $em->persist($shoppingCart);
        $em->flush();

        return $shoppingCart;
    }




    public function getPrice($bookingType, $birthdate, $discountTicket)
    {
        $today = new \Datetime();
        $age = $today->format('Y') - $birthdate->format('Y');
        if($discountTicket)
        {
            $price = 10;
        }
        else
        {
            if($age < 12 && $age >= 4)
            {
                $price = 8;
            }
            elseif ($age < 60 && $age >= 12) 
            {
                $price = 16;
            }
            elseif ($age >= 60) 
            {
                $price = 12;
            }
            else
            {
                $price = 0;
            }
        }
        if($bookingType == 'Demi-journée')
        {
            $price = $price/2;
        }

        return $price;
    }




    public function getFullDaysAction(Request $request) 
    {
        if($request->isXMLHttpRequest())
        {
            $dates = array();
            $em = $this->getDoctrine()->getManager();
            $repositoryUser = $em->getRepository(User::class);
            $repositoryCalendar = $em->getRepository(Calendar::class);
            $repositoryShoppingCart = $em->getRepository(ShoppingCart::class);

            $doctrine = array('user' => $repositoryUser, 'calendar' => $repositoryCalendar, 'shoppingCart' => $repositoryShoppingCart);

            $searchForFullDays = $doctrine['calendar']->findByBookings('1000');

            foreach ($searchForFullDays as $day) 
            {
                $dateTime = $day->getDay();
                $annee = $dateTime->format('Y');
                $mois = $dateTime->format('n') - 1;
                $jour = $dateTime->format('j');
                $date = array(intval($annee), intval($mois), intval($jour));
                $dates[] = $date;
            }

            return new JsonResponse(array('dates'=>$dates));
        }

        return new Response("Erreur: ceci n'est pas une requête ajax", 400);
    }

    public function getRemainingTicketsAction(Request $request) 
    {
        if($request->isXMLHttpRequest())
        {
            $date = json_decode($request->getContent(), true);
            $day = \DateTime::createFromFormat('d-m-Y', $date);
            $em = $this->getDoctrine()->getManager();
            $repositoryUser = $em->getRepository(User::class);
            $repositoryCalendar = $em->getRepository(Calendar::class);
            $repositoryShoppingCart = $em->getRepository(ShoppingCart::class);

            $doctrine = array('user' => $repositoryUser, 'calendar' => $repositoryCalendar, 'shoppingCart' => $repositoryShoppingCart);

            $searchForDay = $doctrine['calendar']->findOneByDay($day);

            if(!$searchForDay)
            {
                $number = 1000;
            }
            else
            {
                $number = 1000 - $searchForDay->getBookings();
            }
            

            return new JsonResponse(array('number'=>$number));
        }

        return new Response("Erreur: ceci n'est pas une requête ajax", 400);
    }




    public function calendarPartSubmitAction(Request $request) 
    {
        if($request->isXMLHttpRequest())
        {
            $formData = json_decode($request->getContent(), true);

            if(isset($formData['day']) && preg_match("#[0123][0-9]-[01][0-9]-20[12][7890]#", $formData['day'])&& isset($formData['bookingType']))
            {

            $day = \DateTime::createFromFormat('d-m-Y', $formData['day']);

            $bookingType = $formData['bookingType'];

            $dateTime = new \DateTime();

                if($bookingType == 'Journée' || $bookingType == 'Demi-journée')
                {
                    if($day->format('Ymd') == $dateTime->format('Ymd'))
                    {
                        if($dateTime->format('G') >= 14)
                        {
                            if($bookingType != 'Demi-journée')
                            {
                                $message = "Il n'est pas possible de réserver un billet 'Journée' pour le jour même après 14h.";
                            }
                        }
                    }
                    elseif ($day < $dateTime) 
                    {
                        $message = "Il n'est pas possible de réserver un billet pour un jour passé!";
                    }       
                }
                else
                {
                    $message = "Veuillez choisir un type de billet: journée ou demi-journée.";
                }
            }
            else
            {
                $message = "Merci de sélectionner une date et un type de billet.";
            }
            
            if(!isset($message))
            {
                $message = 0;
            }

            return new JsonResponse(array('message'=>$message));
            
        }

        return new Response("Erreur: ceci n'est pas une requête ajax", 400);
    }


    public function proceedPaymentAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $token = json_decode($request->getContent(), true);
            $state = true;
            
            return new JsonResponse(array('state'=>$state));
        }

        return new Response("Erreur: ceci n'est pas une requête ajax", 400);
    }
    
}





