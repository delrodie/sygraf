<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticiperType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->region = $options['region'];
        $this->level = $options['level'];
        $this->formation = $options['formation'];
        $region = $this->region; $level = $this->level; $formation = $this->formation;
        $builder
            //->add('annee')
            //->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('fonction', TextType::class,[
                'attr' =>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'La fonction du statgiaire']
            ])
            ->add('entite', TextType::class,[
                'attr' =>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>"L'entité dans laquelle le chef assure la fonction"]
            ])
            ->add('chef', null,[
                'attr'=>['class'=>'form-control show-tick ms select2'],
                'class'=>'AppBundle:Chef',
                'query_builder'=>function(EntityRepository $repository) use($region, $level){
                    return $repository->listeStagiares($region, $level);
                },
                'choice_label'=> 'slug'
            ])
            ->add('formation', EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=> 'AppBundle:Formation',
                'query_builder'=>function(EntityRepository $repository) use ($formation){
                    return $repository->findUnique($formation);
                },
                'choice_label'=> 'lieu'
            ])
            ->add('certificat', null,[
                'attr'=>['class'=>'form-control show-tick ms select2'],
                'class'=>'AppBundle:Certificat',
                'query_builder'=>function(EntityRepository $repository) use($region, $level){
                    return $repository->liste($region, $level);
                },
                'choice_label'=> 'code'
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Participer',
            'region' => null,
            'level' => null,
            'formation' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_participer';
    }


}
