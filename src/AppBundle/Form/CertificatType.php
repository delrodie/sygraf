<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertificatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('code1', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Plage du numero des certificats']
            ])
            ->add('code2', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Plage du numero des certificats']
            ])*/
            ->add('motif', TextareaType::class,[
                'attr'=>['class'=>'form-control'], 'required' => false
            ])
            ->add('statut', CheckboxType::class,[
                'attr'=>['class'=>'checkbox-tick'], 'required' => false
            ])
            //->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('formation', null,[
                'attr'=> ['class'=>'form-control show-tick ms select2'],
                'class'=> 'AppBundle:TypeFormation',
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
            'data_class' => 'AppBundle\Entity\Certificat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_certificat';
    }


}
