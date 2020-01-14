<?php

namespace App\Form;
use App\Entity\Intervenant;

use App\Entity\Matiere;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('intervenant', EntityType::class, [
                'class' => Intervenant::class,
                'label' => 'Choisir Intervenant',
                'query_builder'=> function(EntityRepository $er) use ($options){
                    $sub = $er->createQueryBuilder('ed')
                    ->leftJoin('ed.matieres', 'p')
                    ->groupby('ed.id')
                    ->having('count(p.id) < 2');

                    $qb =  $er->createQueryBuilder('e')
                    ->where('e.id in ('.  $sub->getDQL(). ')') 
                    ->orWhere('e in (:intervenant)')
                    ->setParameter('intervenant',$options['intervenant']);
                    
            return $qb;
                   /*  $sub =  $er->createQueryBuilder('e')
                        ->where('e.matieres is null');
                   $qb =  $er->createQueryBuilder('es')
                        ->where('es.id in ('.  $sub->getDQL(). ')') 
                        ->orWhere('es in (:matiere)')
                        ->setParameter('matiere',$options['matieres']);*/
                },
                'choice_label' => function($intervenant) {
                    return $intervenant->getPrenom() . " " . $intervenant->getNom();
                },
                'placeholder' => 'choisir',
                'required'=>false

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
            'intervenant' => []

        ]);
    }
}
