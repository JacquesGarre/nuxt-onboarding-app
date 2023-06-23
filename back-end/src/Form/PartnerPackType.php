<?php

namespace App\Form;

use App\Entity\PartnerPack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class PartnerPackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Draft' => 'draft',
                    'Published' => 'published',
                    'Archived' => 'archived'
                ]
            ])
            ->add('price')
            ->add('nbPlaces')
            ->add('nbQuests')
            ->add('nbStories')
            ->add('nbEpics')
            ->add('nbArticles')
            ->add('hasDuration')
            ->add('startsAt')
            ->add('endsAt')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PartnerPack::class,
        ]);
    }
}
