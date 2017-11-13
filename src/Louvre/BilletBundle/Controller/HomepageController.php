<?php

namespace Louvre\BilletBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Louvre\BilletBundle\Entity\User;
use Louvre\BilletBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Cookie;

class HomepageController extends Controller
{
    public function indexAction(Request $request, SessionInterface $session)
    {
    	$em = $this->getDoctrine()->getManager();
    	$repository = $em->getRepository(User::class);

    	$user = new User();

    	if($request->cookies->has('user'))
        {
        	$searchForUser = $repository->find($request->cookies->get('user'));

	        if($searchForUser)
	        {
	        	$user->setEmail($searchForUser->getEmail());
	        	$user->setFullname($searchForUser->getFullname());
	        	$user->setAge($searchForUser->getAge());
        	}
        }

    	$userType = $this->createForm(UserType::class, $user);

	    $userType->handleRequest($request);

	    if ($userType->isSubmitted() && $userType->isValid()) 
	    {
	    	// Si l'utilisateur existe, on met à jour ses infos, sinon, on l'enregistre en BDD
	    	// On récupère son ID pour le passer à la vue suivante
        	$user = $userType->getData();

        	$repository = $em->getRepository(User::class);
        	$searchForUser = $repository->findOneByEmail($user->getEmail());

        	if(!$searchForUser)
        	{
        		$em->persist($user);
        		$em->flush();

        		$userId = $user->getId();	
        	}
        	else 
        	{
        		$searchForUser->setFullname($user->getFullname());
        		$searchForUser->setAge($user->getAge());
        		$em->flush();

        		$userId = $searchForUser->getId();
        	}

        	// Création d'un cookie afin de retrouver les infos de l'utilisateur lors de sa prochaine visite et ouverture d'une session
        	$session->set('user_id', $userId);
        	


        	return $this->redirectToRoute('louvre_billet_ticketpage');
    	}

    	return $this->render('LouvreBilletBundle:Homepage:index.html.twig', array(
    	'form' => $userType->createView(),
    	));

    }
}
