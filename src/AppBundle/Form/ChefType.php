<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ChefType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matricule', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Matricule']
            ])
            ->add('nom', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Nom']
            ])
            ->add('prenoms', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Prenoms']
            ])
            ->add('datenais', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Date de naissance', 'data-provide'=>'datepicker', 'data-date-autoclose'=>'true', 'data-date-format'=>'yyyy/mm/dd']
            ])
            ->add('lieunais', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Lieu de naissance']
            ])
            ->add('email', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Adresse email'], 'required'=> false
            ])
            ->add('tel', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Numero de telephone'], 'required'=> false
            ])
            ->add('formation', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Formation professionnelle'], 'required'=> false
            ])
            ->add('profession', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Profession'], 'required'=> false
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    '-- Selectionnez le sexe --' => '',
                    'Homme' => 'M',
                    'Femme' => 'F'
                ],
                'attr' => ['class'=> 'form-control'],
            ])
            /*->add('domicile', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Lieu de residence'], 'required'=> false
            ])*/
            //->add('classe')
            ->add('imageFile', VichImageType::class,[
                'required' => false,
                'allow_delete' => true,
                'label' => "Photo"
            ])
            //->add('imageSize')->add('updatedAt')->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('region', null,[
                'attr'=>['class'=>'form-control show-tick ms select2'],
                'class'=>'AppBundle:Region',
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
            'data_class' => 'AppBundle\Entity\Chef'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_chef';
    }


}
