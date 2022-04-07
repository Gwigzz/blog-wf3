<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('username')
            ->add('roles', ChoiceType::class, [
                'placeholder' => 'Choisir une catégorie',
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    /* 'ADMIN'     => 'ROLE_ADMIN', */
                    'BLOGGER'   => 'ROLE_BLOGGER',
                    'USER'      => 'ROLE_USER',
                ],
            ]);

        // Get the roles (convert to json and addapte to situation) 
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // Transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($roleString) {
                    // Transform the string back to an array
                    return [$roleString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
