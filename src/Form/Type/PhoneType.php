<?php

// src/Form/Type/PhoneType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Phone;


class PhoneType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'Phone Number',
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Phone::class,
            // 'constraints' => [
            //     new Regex([
            //         'pattern' => '/^\d{10}$/', // Adjust this regex pattern as per your phone number format
            //         'message' => 'Phone number should be a 10-digit number.',
            //     ]),
            // ],
        ]);
    }

    // public function getParent()
    // {
    //     return TextType::class;
    // }
}