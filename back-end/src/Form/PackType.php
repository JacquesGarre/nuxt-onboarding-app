<?php

namespace App\Form;

use App\Entity\Pack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class PackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Premium' => 'premium',
                    'Partner' => 'partner',
                ]
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Draft' => 'draft',
                    'Active' => 'active'
                ]
            ])
            ->add('price')
            ->add('discount')
            ->add('duration', ChoiceType::class, [
                'choices' => [
                    '1 Month' => '+1 month',
                    '6 Months' => '+6 month',
                    '1 Year' => '+1 year',
                    'Unlimited' => 'unlimited'
                ],
                'attr' => [
                    'class' => 'js-select-form-duration',
                ]
            ])
            ->add('nbArticles')
            ->add('nbAchievements')
            ->add('nbStories')
            ->add('nbQuests')
            ->add('nbEpics')
            ->add('nbPlaces')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pack::class,
        ]);
    }
}
