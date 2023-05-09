<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\PlageHoraire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlageHoraireType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nom_plage',ChoiceType::class, ['choices' => [
                'Matin' => 'matin' ,
                'Après-Midi' => 'apres-midi' ,

            ]])
            ->add('debut', TimeType::class)
            ->add('fin',TimeType::class)
            ->add('num_jour',ChoiceType::class, ['choices' => [
                'lundi' => '0' ,
                'mardi' => '1' ,
                'mercredi' => '2' ,
                'jeudi' => '3' ,
                'vendredi' => '4' ,
                'samedi' => '5' ,
                'dimanche' => '6' ,
            ]])
            ->add('etats', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'nom_etat',
                'label' => 'État : ',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ]);
    }
//    public function buildForm(FormBuilderInterface $builder, array $options): void
//    {
//        $joursSemaine = [
//            'lundi' => 0,
//            'mardi' => 1,
//            'mercredi' => 2,
//            'jeudi' => 3,
//            'vendredi' => 4,
//            'samedi' => 5,
//            'dimanche' => 6,
//        ];
//
//        foreach ($joursSemaine as $jour => $value) {
//            $builder->add('num_jour'.$jour, ChoiceType::class, [
//                'label' => ucfirst($jour),
//                'mapped' => false,
//                'choices' => [
//                    'Matin' => 'matin',
//                    'Après-Midi' => 'apres-midi',
//                ],
//                'required' => true,
//                'label' => 'Plage horaire :',
//            ]);
//
//            $builder->add('debut_'.$jour, TimeType::class, [
//                'required' => true,
//                'label' => 'Début :',
//            ]);
//
//            $builder->add('fin_'.$jour, TimeType::class, [
//                'required' => true,
//                'label' => 'Fin :',
//            ]);

//            $builder->add('etats_'.$jour, EntityType::class, [
//                'class' => Etat::class,
//                'choice_label' => 'nom_etat',
//                'label' => 'État : ',
//                'multiple' => true,
//                'expanded' => true,
//                'required' => false,
//            ]);
//        }
//    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'=>PlageHoraire::class,
        ]);
    }
}
