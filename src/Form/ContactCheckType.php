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
            ->add('deleteSelected', SubmitType::class, ['label' => 'Delete Selected']);
    }

}
