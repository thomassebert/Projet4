<?php 

namespace tests\Form;

use Louvre\BilletBundle\Forms\TicketType;
use Louvre\BilletBundle\Entity\Booking;
use Symfony\Component\Form\Test\TypeTestCase;

class TicketTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'BROSS',
            'firstname' => 'Mario',
            'country' => 'France',
            'birthDate' => '19-07-1993',
            'discountTicket' => 'true',
        );

        $form = $this->factory->create(TicketType::class);

        $object = new Booking();
        $object->setName('BROSS');
        $object->setFirstname('Mario');
        $object->setCountry('France');
        $object->setBirthDate(\DateTime::createFromFormat('d-m-Y h:i', '19-07-1993 00:00', new \DateTimeZone("UTC")));
        $object->setDiscountTicket(true);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    
}