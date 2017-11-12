<?php 

namespace tests\Form;

use Louvre\BilletBundle\Forms\CalendarType;
use Louvre\BilletBundle\Entity\Calendar;
use Symfony\Component\Form\Test\TypeTestCase;

class CalendarTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'day' => '01-03-2019',
            'bookingType' => 'Demi-journée',
        );

        $form = $this->factory->create(CalendarType::class);

        $object = new Calendar();
        $object->setDay(\DateTime::createFromFormat('d-m-Y h:i', '01-03-2019 00:00', new \DateTimeZone("UTC")));
        $object->setBookingType('Demi-journée');

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