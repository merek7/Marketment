<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('prenom')
            ->add(
                'datenaiss',
                DateType::class,
                [
                    'widget' => 'single_text',
                ]
            )
            ->add('telephone')
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de saisir une adresse email'
                        ])
                    ],
                    'required' => true,
                    'attr' => ['class' => 'form-control']
                ]
            )->add(
                'roles',
                ChoiceType::class,
                [
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' =>
                    [
                        'Marketeur' => 'ROLE_USER',
                        'Administration' => 'ROLE_ADMIN',
                        'Admin' => 'ROLE_SUPER_ADMIN',
                    ],
                ]
            )->add('Valider', SubmitType::class);
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    return [$rolesString];
                }
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
