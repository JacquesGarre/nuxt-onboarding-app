<?php

namespace App\Form;

use App\Entity\ApiToken;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiTokenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('token')
            ->add('active')

            ->add('getUsers')
            ->add('postUsers')
            ->add('patchUsers')
            ->add('putUsers')
            ->add('deleteUsers')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApiToken::class,
        ]);
    }
}
