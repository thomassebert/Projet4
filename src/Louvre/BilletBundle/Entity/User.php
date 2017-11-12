<?php

namespace Louvre\BilletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Louvre\BilletBundle\Repository\UserRepository")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255, nullable=false)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner vos nom et prénom."
     * )
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=false)
     * @Assert\Email(
     *     message = "L'adresse '{{ value }}' n'est pas valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer", nullable=false)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner votre âge."
     * )
     * @Assert\Range(
     *      min = 18,
     *      max = 130,
     *      minMessage = "Vous devez avoir au moins 18 ans pour réserver un billet.",
     *      maxMessage = "Vous avez plus de 130 ans?! Ne réservez pas, on vous offre les places."
     * )
     */
    private $age;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return User
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }
}

