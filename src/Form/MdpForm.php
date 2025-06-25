<?php

namespace App\Form;

use App\Entity\Mdp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MdpForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Identifiant', TextType::class, [
                'label' => 'Identifiant',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mdp', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Créé le',
                'widget' => 'single_text',
                'disabled' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('updatedAt', DateTimeType::class, [
                'label' => 'Modifié le',
                'widget' => 'single_text',
                'disabled' => true,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mdp::class,
        ]);
    }
}
