<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\PhoneType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('phones', CollectionType::class, [
                'label' => false,
                'entry_type' => TextType::class, // Use PhoneType for phone_list
                'entry_options' => [
                    'label' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Phone number cannot be empty.',
                        ]),
                        new Regex([
                            'pattern' => '/^\d{10}$/',  // 10 digits without any other characters (no "+" sign or spaces)
                            'message' => 'Please enter a valid phone number with exactly 10 digits.'
                        ]),
                    ],
                ], // Disable the label for individual entries
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__name__',
                'delete_empty' => false,
                'data_class' => null
            ])
            ->add('birthday', DateType::class, [
                'format' => 'dd MMM yyyy',
                'years' => range(1900, date('Y')),
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'allow_extra_fields' => false,
        ]);
    }
}
