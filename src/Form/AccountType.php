<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => "Adresse email"])
            ->add('plainPassword', RepeatedType::class, [
                'required' => ($options['method'] != "PATCH"),
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passes doivent correspondre.",
                'first_options' => [
                    'label' => "Mot de passe",
                ],
                'second_options' => [
                    'label' => "Répéter le mot de passe",
                ]
            ])
            ->add('profile', ProfileType::class, ['label' => false])
            ->add('submit', SubmitType::class, ['label' => "S'inscrire"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
