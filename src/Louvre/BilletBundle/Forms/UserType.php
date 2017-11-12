<?php 

namespace Louvre\BilletBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('fullname',  TextType::class)
	      ->add('age',       IntegerType::class)
	      ->add('email',     EmailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'Louvre\BilletBundle\Entity\User',
	    ));
	}
}
