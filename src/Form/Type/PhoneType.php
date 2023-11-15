<?php

// src/Form/Type/PhoneType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PhoneType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'constraints' => [
                new Regex([
                    'pattern' => '/^\d{10}$/', // Adjust this regex pattern as per your phone number format
                    'message' => 'Phone number should be a 10-digit number.',
                ]),
            ],
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}