<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TitularisationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->chef = $options['chef'];
        $chef = $this->chef;
        $builder
            ->add('dateTitu', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Date de titularisation', 'data-provide'=>'datepicker', 'data-date-autoclose'=>'true', 'data-date-format'=>'yyyy/mm/dd']
            ])
            ->add('lieu', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Lieu de la titularisation']
            ])
            ->add('buchette', ChoiceType::class,[
                'choices' => [
                    '-- Selectionnez les bûchettes --' => '',
                    '2 bûchettes' => 2,
                    '3 bûchettes' => 3,
                    '4 bûchettes' => 4,
                ],
                'attr' => ['class'=> 'form-control'],
            ])
            ->add('fonction', TextType::class,[
                'attr' =>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'La fonction du chef a titulariser']
            ])
            ->add('entite', TextType::class,[
                'attr' =>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>"L'entité dans laquelle le chef assure la fonction"]
            ])
            //->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('chef', EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=> 'AppBundle:Chef',
                'query_builder'=>function(EntityRepository $repository) use ($chef){
                    return $repository->findBadgiste($chef);
                },
                'choice_label'=> 'slug'
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Titularisation',
            'chef' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_titularisation';
    }


}
