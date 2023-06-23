<?php

namespace App\Form;

use App\Entity\Achievement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AchievementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('sponsored')
            ->add('private')
            ->add('categories')
            ->add('triggerOn', ChoiceType::class, [
                'choices' => [
                    'Certain amount of experience earnt' => 'experience_amount',
                    'Entering in a certain perimeter' => 'in_perimeter',
                    'Certain count of actions done' => 'actions_amount'
                ]
            ])
            ->add('experience')
            ->add('latitude')
            ->add('longitude')
            ->add('radius')
            ->add('action', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '...' => '',
                    'Having {x} article(s) published' => 'Article',
                    'Having {x} achievement(s) published' => 'Achievement',
                    'Having {x} friends invitation(s) accepted' => 'Invitation',
                ]
            ])
            ->add('actionCount')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Draft' => 'draft',
                    'Active' => 'active'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Achievement::class,
        ]);
    }
}
