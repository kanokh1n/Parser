<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UrlFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, [
                'label' => 'URL адрес для сбора товаров',
                'attr' => [
                    'placeholder' => 'Введите URL адрес',
                ],
            ])
            ->add('pageCount', IntegerType::class)
            ->add('submit', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, [
            'label' => 'Submit',
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
