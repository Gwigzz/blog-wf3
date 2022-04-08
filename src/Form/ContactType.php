<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => '',
                'constraints' => [
                    new NotBlank(['message' => 'Le champ ne doit pas être vide']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Minimum {{ limit }}',
                        'max' => 10,
                        'maxMessage' => 'Maxmium {{ limit }}',
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => '',
                'constraints' => [
                    new NotBlank(['message' => 'Champ email ne doit pas être vide']),
                ]
            ])
            ->add('telephone', TelType::class, [
                'label' => 'indiquez votre numéro de téléphone',
                'constraints' => [
                    // new NotBlank(['message' => 'Le champ telephone ne doit pas être vode']),
                    new Regex([
                        'pattern' => "/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/",
                        'message' => 'Numéro de téléphone invalide'
                    ])
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Votre message',
                    'rows' => 8
                ],
                'label' => 'votre message',
                'help' => 'Entre 10 et 2000 caractères',
                'constraints' => [
                    new NotBlank(['message' => 'Le message ne doit pas être vide']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Minimum {{ limit }}',
                        'max' => 2000,
                        'maxMessage' => 'Maximum {{ limit }}'
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // 'data_class' => Contact::class,
            'required' => false,
        ]);
    }
}
