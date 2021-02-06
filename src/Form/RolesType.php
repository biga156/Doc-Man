<?php
/* NOTE: admin's interface for managing roles */

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class RolesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles', ChoiceType::class, [
            'required' => true,
            'label' => 'Role',
            'multiple' => false,
            'expanded' => false,
            'choices' => [
                'Non-verified user' => 'ROLE_USER_TEMP',
                'User' => 'ROLE_USER',
                'Administrator' => 'ROLE_ADMIN',
            ],
        ]);

        // Data transformer array->string->array
        $builder->get('roles')->addModelTransformer(
            new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
