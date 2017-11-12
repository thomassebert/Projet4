<?php 

namespace Louvre\BilletBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CalendarType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('day',  	    DateType::class, array(
					'widget' => 'single_text',
					'html5' => false,
					'format' => 'yyyy-MM-dd',
					))

          ->add('bookingType',  ChoiceType::class, array(
					'choices' => array('Journée' => 'Journée', 'Demi-journée' => 'Demi-journée')
          			))

          ->add('save', SubmitType::class)

          ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
		    $data = $event->getData();
		    $data['day'] = \DateTime::createFromFormat('d-m-Y', $data['day']);
		    $data['day'] = $data['day']->format('Y-m-d');
		    $event->setData($data);
		});
    }

    public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'Louvre\BilletBundle\Entity\Calendar',
	    ));
	}
}