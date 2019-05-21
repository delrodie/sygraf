<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('promotion', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>"Promotion"], 'required'=>false
            ])
            ->add('lieu', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>"Lieu"]
            ])
            ->add('debut', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>"Debut periode", 'data-date-format'=>"yyyy/mm/dd"]
            ])
            ->add('fin', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>"Fin periode"]
            ])
            //->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('type', null,[
                'attr'=>['class'=>'form-control show-tick ms select2'],
                'class'=>'AppBundle:TypeFormation',
                'query_builder'=>function(EntityRepository $repository){
                    return $repository->liste();
                },
                'choice_label'=>'libelle'
            ])
            ->add('region', null,[
                'attr'=>['class'=>'form-control show-tick ms select2'],
                'class' => 'AppBundle:Region',
                'query_builder'=>function(EntityRepository $repository){
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
            'data_class' => 'AppBundle\Entity\Formation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_formation';
    }


}
