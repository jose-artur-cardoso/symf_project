<?php

namespace App\Form;

// use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ContactCheckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('selected_contacts', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
            ])
            // Add other fields from Entity as needed
            // Example: ->add('attribute', null, ['label' => 'Attribute'])
            // Make sure to replace 'attribute' with your actual entity field name
            // Repeat this for each field you want to display in the table
            ->add('deleteSelected', SubmitType::class, ['label' => 'Delete Selected']);
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Contact::class,
    //     ]);
    // }
}
