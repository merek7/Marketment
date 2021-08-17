<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Prospect;
use App\Repository\EntrepriseRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ProspectionType extends AbstractType
{
    private $security;
    private $user;
    public function __construct(Security $security, UserRepository $user)
    {
        $this->security = $security;
        $this->user = $user;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('description', TextareaType::class)
            ->add(
                'date',
                DateType::class,
                [
                    'widget' => 'single_text'
                ]
            )
            ->add(
                'entreprises',
                EntityType::class,
                [
                    'class' => Entreprise::class,
                    'label' => 'Entreprises',
                    'query_builder' => function (EntrepriseRepository $repository) {
                        return $repository->valider($this->user->UsernameToID($this->security->getUser()->getUserIdentifier()));
                    }
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Enregister',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
        ]);
    }
}
