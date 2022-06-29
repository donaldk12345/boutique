<?php

namespace App\Form;

use App\Entity\Filtre;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('val', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Votre recherche...',
                'class' => 'form-control-sm'
            ]
        ])
        ->add('categories', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => Category::class,
            'multiple' => true,
            'expanded' => true
        ])
        ->add('prixMin',NumberType::class,[
            'label' => false,
            'required' => false,
            'attr'=>[
                'placeholder' => 'prix minimum',
                'class' => 'form-control-sm'
            ]
        ])
        ->add('prixMax',NumberType::class,[
            'label' => false,
            'required' => false,
            'attr'=>[
                'placeholder' => 'prix maximum',
                'class' => 'form-control-sm'
            ]
        ])
        ->add('promo',CheckboxType::class,[
            'label' => 'En promotion',
            'required' => false
          
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filtre::class,
            'method' => 'GET',
            'crsf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
