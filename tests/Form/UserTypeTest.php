<?php 

namespace tests\Form;

use Louvre\BilletBundle\Forms\UserType;
use Louvre\BilletBundle\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'email' => 'test@test.test',
            'fullname' => 'Test Test',
            'age' => 24,
        );

        $form = $this->factory->create(UserType::class);

        $object = new User();
        $object->setEmail('test@test.test');
        $object->setFullname('Test Test');
        $object->setAge(24);

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