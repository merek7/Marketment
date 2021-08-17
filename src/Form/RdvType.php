<?php

namespace App\Form;

use App\Entity\Prospect;
use App\Entity\RDV;
use App\Repository\ProspectRepository;

use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class RdvType extends AbstractType
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
            ->add(
                'date',
                DateTimeType::class,
                [
                    'widget' => 'choice',
                    'placeholder' => [
                        'year' => 'Annee', 'month' => 'Mois', 'day' => 'Jour',
                        'hour' => 'Heure', 'minute' => 'Minute', 'second' => 'Second',
                    ]
                ]
            )
            ->add('Lieux')
            ->add(
                'prospects',
                EntityType::class,
                [
                    'class' => Prospect::class,
                    'label' => 'Propections',
                    'query_builder' => function (ProspectRepository $prospect) {
                        return $prospect->ProspectByUser($this->user->UsernameToID($this->security->getUser()->getUserIdentifier()));
                    }

                ]
            )
            ->add(
                'Etat',
                ChoiceType::class,
                [
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' =>
                    [
                        'En attente' => 'En attente',
                        'Effectuer' => 'Effectuer',
                        'Annuler' => 'Annuler',
                        'Reporté' => 'Reporté'
                    ]
                ]
            )
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RDV::class,
        ]);
    }
}
