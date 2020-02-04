<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\PerformanceSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerformanceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchText', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                'placeholder' => 'Search'
                    ]
            ])
            ->add('city', EntityType::class, [
                    'class' => City::class,
                    'choice_label' => 'name',
                    'required' => false,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PerformanceSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }
}
