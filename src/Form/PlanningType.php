<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('inom', TextType::class)
            ->add('plagesHoraires', CollectionType::class, [
                'entry_type' => PlageHoraireType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__plagehoraire_name__',
                'entry_options' => [
                    'label' => false,
                    'required' => true,
                    'attr' => ['class' => 'plagehoraire'],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->leftJoin('p.etats', 'e')
                            ->addSelect('e');
                    },
                ],
            ]);
    }
//    public function buildForm(FormBuilderInterface $builder, array $options): void
//    {
//        $builder
//            ->add('inom', TextType::class)
//            ->add('plagesHoraires', CollectionType::class, [
//                //PlageHoraireType est le formulaire utilisé pour chaque élément de la collection
//                'entry_type' => PlageHoraireType::class,
//                //autorise l'ajout de nouveaux éléments à la collection
//                'allow_add' => true,
//                //permet de ne pas appeler la méthode setPlagesHoraires lors de la mise à jour de la relation many-to-many.
//                'by_reference' => false,
//                // permet de générer un prototype d'élément de la collection qui sera utilisé pour l'ajout des nouveaux éléments.
//                'prototype' => true,
//            ]);
//    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
