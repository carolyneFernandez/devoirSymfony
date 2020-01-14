<?php

namespace App\Form;
use App\Entity\Projet;
use App\Entity\Matiere;

use App\Entity\Etudiant;
use Doctrine\ORM\EntityRepository;
use App\Repository\EtudiantRepository;
use App\Repository\ProjetRepository;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('nombreEtudiant', null, [
                'label' => "Nombre maximum d'étudiants"
            ])
            ->add('note')
            ->add('etudiants', EntityType::class, [
                'class' => Etudiant::class,
                'query_builder'=> function(EntityRepository $er) use ($options){
                    // create a subquery
                   $sub = $er->createQueryBuilder('ed')
                         ->leftJoin('ed.projets', 'p')
                         ->groupby('ed.id')
                         ->having('count(p.id) < 4');
                    
                    $qb =  $er->createQueryBuilder('e')
                            ->where('e.id in ('.  $sub->getDQL(). ')') 
                            ->orWhere('e in (:etudiant)')
                            ->setParameter('etudiant',$options['etudiants']);
                            
                    return $qb;
                },
                'choice_label' => function($etudiant) {
                    return $etudiant->getNom() . " " .$etudiant->getPrenom()."\n";
                },
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => function($matiere) {
                    return $matiere->getNom();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
            'etudiants' => [],
        ]);
    }
}
