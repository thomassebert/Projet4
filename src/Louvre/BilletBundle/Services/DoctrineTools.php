<?php
namespace Louvre\BilletBundle\Services;

use Louvre\BilletBundle\Entity\User;
use Louvre\BilletBundle\Entity\Booking;
use Louvre\BilletBundle\Entity\ShoppingCart;
use Louvre\BilletBundle\Entity\Calendar;

class DoctrineTools
{
  private $em;

  private $u;
  private $c;
  private $sc;
  private $b;

  public function __construct(\Doctrine\ORM\EntityManager $em) { 
      $this->em = $em;
      $this->u  = $this->em->getRepository(User::class);
      $this->c  = $this->em->getRepository(Calendar::class);
      $this->sc = $this->em->getRepository(ShoppingCart::class);
      $this->b  = $this->em->getRepository(Booking::class);
  }

  public function __invoke() {
      return $this->em;
  }

  public function __call($function, $array) {

    if(!empty($array)) {
      $arg = $array[0];
      return $this->em->$function($arg);
    }
    return $this->em->$function();
    
  }

  public function getU() {
      return $this->u;
  }

  public function getC() {
      return $this->c;
  }

  public function getSc() {
      return $this->sc;
  }

  public function getB() {
      return $this->b;
  }
} 

