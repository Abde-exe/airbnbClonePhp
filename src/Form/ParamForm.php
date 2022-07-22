<?php

namespace App\Form;

use App\Data\ParamReservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateE', DateType::class, [
                'label' => false,
                'required' => true,
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'Date entrée']
            ])
            ->add('dateS', DateType::class, [
                'label' => false,
                'required' => true,
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'Date sortie']
            ])
            ->add('nbPers', IntegerType::class, [
                'label' => false,
                'required' => true,
                'attr' => ['placeholder' => 'Nombre de personne']
            ])
            ->add('Valider', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ParamReservation::class,
            'method' => 'GET',
            'csrf_protection' => false

        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
