<?php 

namespace Louvre\BilletBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Louvre\BilletBundle\Forms\CalendarType;
use Louvre\BilletBundle\Forms\TicketType;


class BookingType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('calendar',  CalendarType::class)

          ->add('tickets', CollectionType::class, array(
			        'entry_type'   => TicketType::class,
			        'label' => false,
			        'entry_options' => array('label' => false),
			        'allow_add'    => true,
			        'allow_delete' => true,
			        'prototype_name' => '__change__'
			      ))

          ->add('key', HiddenType::class);
    }
}