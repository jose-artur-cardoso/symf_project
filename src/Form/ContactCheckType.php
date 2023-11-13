<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactCheckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('test', ChoiceType::class, [
            'multiple' => true,
            'expanded' => true,
            'choices' => $options['dynamic_choices'],
            // 'choices' => [
            //     'option1' => '1',
            //     'option2' => '2',
            //     'option3' => '3',
            // ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'dynamic_choices' => [], // Default value, will be replaced in the controller
            // 'data_class' => Contact::class,
        ]);
    }
}
