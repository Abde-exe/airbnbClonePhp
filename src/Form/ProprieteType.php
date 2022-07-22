<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Propriete;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProprieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('pays')
            ->add('ville')
            ->add('category', EntityType::class, [
                "class" => Category::class,
                "choice_label" => "name",
                "placeholder" => "Choisissez une catégorie"
            ])
            ->add('photos', FileType::class, [
                "mapped" => false, "required" => false,
                'attr' => [
                    'class' => 'dropify',
                    'id' => 'input-file-now-custom-1',
                    'for' => 'input-file-now-costom-1'
                ]
            ])
            ->add('lits')
            ->add('chambres')
            ->add('sdb',)
            ->add('prixJournalier')
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Propriete::class,
        ]);
    }
}
