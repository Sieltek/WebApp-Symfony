<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'required' => true,
                'label' => 'Nom d\'utilisateur :',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Exemple : Xx_D4rKS4Suk3_xX'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer nom d\'utilisateur',
                    ])
                ],
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes doivent correspondre.',
                'options' => [
                    'attr' => [
                        'class'=>'form-control',
                        'placeholder' => '******',
                        'style' => 'padding-right : 150px',
                    ]],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),

                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mots de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe :'],
                'second_options' => ['label' => 'Confirmer le mot de passe :'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
