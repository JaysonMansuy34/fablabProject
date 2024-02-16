<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Email',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    // Ajoutez d'autres rôles ici
                ],
                'expanded' => true,
                'multiple' => true,
                'label_attr' => ['class' => 'checkbox-custom'],
                'choice_attr' => function($choice, $key, $value) {
                    // Ajouter une classe différente à chaque choix
                    return ['class' => 'choice-' . strtolower($choice)];
                },
            ])
            ->add('is_verified', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                    'role' => 'switch', // Ajoutez le rôle ARIA pour les technologies d'assistance
                ],
                'label' => 'Is Verified',
                'required' => false,
                'disabled' => true, // Rendre le champ non modifiable
            ])                      
            ->add('resetToken', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true, // Rendre le champ en lecture seule
                ],
                'label' => 'Token Password',
                'required' => false, // Rendre ce champ facultatif
                'disabled' => true, // Rendre le champ non modifiable
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Password',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
