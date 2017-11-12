<?php
// src/OC/PlatformBundle/Antispam/OCAntispam.php

namespace Louvre\BilletBundle\Services;

class DoctrineTools
{

  private $doctrine;
    
  public function __construct(Doctrine\ORM $doctrine) { //Son constructeur avec l'entity manager en paramètre
      $this->doctrine = $doctrine;
  }

  public function getManagerAndRepo() {

  $em = $this->doctrine->getManager();
  $repositoryUser = $this->doctrine->getRepository(User::class);
  $repositoryCalendar = $this->doctrine->getRepository(Calendar::class);
  $repositoryShoppingCart = $this->doctrine->getRepository(ShoppingCart::class);

  $doctrineTools = array('em' => $em, 'user' => $repositoryUser, 'calendar' => $repositoryCalendar, 'shoppingCart' => $repositoryShoppingCart);

  return $doctrineTools;
  }
}