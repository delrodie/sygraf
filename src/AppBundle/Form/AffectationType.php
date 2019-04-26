<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->formation = $options['formation'];

        $formation = $this->formation;

        $builder
            //->add('depart')->add('arrivee')
            ->add('statut')
            //->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('formation', EntityType::class,[
                'attr'=>['class' => 'form-control'],
                'class' => 'AppBundle:TypeFormation',
                'query_builder' => function(EntityRepository $repository) use($formation){
                        return $repository->findBySlug($formation);
                },
                'choice_label'=> 'libelle'
            ])
            ->add('region', null,[
                'attr'=>['class'=> 'form-control show-tick ms select2'],
                'class' => 'AppBundle:Region',
                'query_builder' => function(EntityRepository $repository){
                        return $repository->liste();
                },
                'choice_label' => 'libelle'
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Affectation',
            'formation' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_affectation';
    }


}
