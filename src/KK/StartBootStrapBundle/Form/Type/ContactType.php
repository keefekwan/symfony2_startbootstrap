<?php
// src/KK/StartBootStrapBundle/Form/Type/ContactType.php

namespace KK\StartBootStrapBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'attr' => array(
                    'placeholder' => '',
                    'pattern'     => '.{2,}' //minlength
                )
            ))
            ->add('email', 'email', array(
                'label' => 'Email Address',
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('phone', 'text', array(
                'label' => 'Phone Number',
                'attr' => array(
                    'placeholder' => '',
                    'pattern'     => '.{10,}' //minlength
                )
            ))
            ->add('message', 'textarea'
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'name' => array(
                new NotBlank(array('message' => 'Name field should not be blank')),
                new Length(array('min' => 2))
            ),
            'email' => array(
                new NotBlank(array('message' => 'Email field should not be blank')),
                new Email(array('message' => 'Invalid email address'))
            ),
            'phone' => array(
                new NotBlank(array('message' => 'Phone number must be provided')),
                new Length(array('min' => 10))
            ),
            'message' => array(
                new NotBlank(array('message' => 'Message should not be blank.')),
                new Length(array('min' => 5))
            )
        ));
    }

    public function getName()
    {
        return 'contact';
    }
}