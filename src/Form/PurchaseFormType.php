<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseFormType extends AbstractType
{

    /**
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    public function getConfiguration($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration(" Nom", "Veillez saisir votre nom "))
            ->add('address', TextType::class, $this->getConfiguration("Adresse", "Veillez saisir votre adresse "))
            ->add('codepostal', TextType::class, $this->getConfiguration("Code postal", "Veillez saisir le code postal"))
            ->add('city', TextType::class, $this->getConfiguration("Ville", "Veillez saisir votre ville"));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}