<?php

namespace App\Form;

use App\Entity\Intervenant;
use App\Entity\Matiere;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class IntervenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('age')
            ->add('matieres', EntityType::class, [
                'class' => Matiere::class,
                'query_builder'=> function(EntityRepository $er) use ($options){
                    $sub =  $er->createQueryBuilder('e')
                        ->where('e.intervenant is null');
                    $qb =  $er->createQueryBuilder('es')
                        ->where('es.id in ('.  $sub->getDQL(). ')') 
                        ->orWhere('es in (:matiere)')
                        ->setParameter('matiere',$options['matieres']);
                    return $qb;
                },
                'choice_label' => function($matiere) {
                    return $matiere->getNom();
                },
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervenant::class,
            'matieres' => []
        ]);
    }
}
