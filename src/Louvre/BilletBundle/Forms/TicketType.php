<?php 

namespace Louvre\BilletBundle\Forms;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType 
{

	private $countries = array('France' => 'France', 'Royaumes-Unis' => 'Royaumes-Unis', 'Espagne' => 'Espagne', 
							   'Italie' => 'Italie', 'Portugal' => 'Portugal', 'Allemagne' => 'Allemagne', 
							   'Suisse' => 'Suisse', 'Belgique' => 'Belgique', 'Luxembourg' => 'Luxembourg', 
							   'Europe' => 'Europe', 'Amérique du Nord' => 'Amérique du nord', 'Amérique du Sud' => 'Amérique du sud', 
							   'Afrique' => 'Afrique', 'Asie' => 'Asie');

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

	      ->add('name',     		 TextType::class, array(
	      			'label'    => 'Nom',
    				'attr' => array('class' => 'validate name')))

	      ->add('firstname', 		 TextType::class, array(
	      			'label'    => 'Prénom',
    				'attr' => array('class' => 'validate firstname')))

	      ->add('country', 			 ChoiceType::class, array(
	      			'label'    => 'Pays',
				    'choices' => $this->countries,
    				'attr' => array('class' => 'validate')))

	      ->add('birthDate', 		 DateType::class, array(
	      			'label'    => 'Date de naissance',
    				'widget' => 'single_text',
    				'attr' => array('class' => 'datepickerBirthday birthDate'),
    				'format' => 'yyyy-MM-dd',
    				'html5' => false))

	      ->add('discountTicket', 	 CheckboxType::class, array(
				    'label'    => 'Tarif réduit',
				    'required' => false,
    				'attr' => array('class' => 'discount')
				))

	       ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
			    $data = $event->getData();
			    $data['birthDate'] = \DateTime::createFromFormat('d-m-Y', $data['birthDate']);
			    $data['birthDate'] = $data['birthDate']->format('Y-m-d');
			    $event->setData($data);
			});
    }


    public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'Louvre\BilletBundle\Entity\Booking',
	    ));
	}
}
