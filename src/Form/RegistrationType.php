<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{

            /**
     * @param string $label
     * @param string $placeholder
     * @return array
     */
   public function getConfiguration($label, $placeholder){
    return [
        'label'=>$label,
        'attr' => [
            'placeholder'=>$placeholder
        ]
        ];
}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email',EmailType::class,$this->getConfiguration(" Email","Veillez saisir votre adresse email "))
        ->add('username',TextType::class,$this->getConfiguration(" Prenom","Veillez saisir votre Prenom "))
        ->add('password',PasswordType::class,$this->getConfiguration("Mot de passe","Veillez saisir votre mot de passe "))
        ->add('confirm_password',PasswordType::class,$this->getConfiguration("Confirmation ","Veillez confirmé votre mot de passe "))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
