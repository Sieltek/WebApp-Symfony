<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez mettre un titre',
                    ])
                ],
            ])

            ->add('description', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez mettre une description',
                    ])
                ],
            ])
            ->add('contenu', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez mettre un contenu',
                    ])
                ],
            ]);;
    }
}
