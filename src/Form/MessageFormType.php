<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'required' => true,
                'label' => 'Écrire un message',
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
