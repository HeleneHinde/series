<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('firstAirDate', DateType::class, [
                'html5'=>true,
                'widget'=>'single_text'
             ])
            ->add('overview')
            ->add('poster')
            ->add('tmdbId')
            //EntityType
            ->add('serie', EntityType::class, [
                //quelle entité est liée
                'class'=>Serie::class,
                //quel attribut servira a afficher l'information
                'choice_label'=>'name',
                'query_builder'=> function(SerieRepository $serieRepository){
                    $qb= $serieRepository->createQueryBuilder('s');
                    $qb->addOrderBy('s.name', 'ASC');
                    return $qb;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
