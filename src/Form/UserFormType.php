<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class UserFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPass', PasswordType::class, [
                'required' => true,
                'label' => 'Ancien mot de passe :',
                'attr' => [
                    'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez rentrer votre ancien mot de passe.',
                    ])
                ],
            ])

            ->add('newPass', RepeatedType::class, [
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
                'first_options'  => ['label' => 'Nouveau mot de passe :'],
                'second_options' => ['label' => 'Confirmer le mot de passe :'],
            ]);
    }

}