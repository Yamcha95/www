<?php

namespace App\Form;

use App\Entity\Mdp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MdpForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre')
            ->add('Identifiant')
            ->add('Mdp')
            ->add('createdAt', null, [
            ])
            ->add('updatedAt', null, [
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mdp::class,
        ]);
    }
}
