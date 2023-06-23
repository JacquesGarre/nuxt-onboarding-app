<?php

namespace App\Form;

use App\Entity\UserNode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserNodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('latitude')
            ->add('longitude');
        
        if (!$options['from_user']) {
            $builder->add('user');
        } else {
            $builder->add('user', null, [
                'disabled' => true,
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserNode::class,
            'from_user' => false
        ]);
    }


}
