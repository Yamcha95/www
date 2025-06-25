<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordGeneratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('length', IntegerType::class, [
                'label' => 'Longueur',
                'data' => 12, // valeur par défaut recommandée
            ])
            ->add('include_uppercase', CheckboxType::class, [
                'label' => 'Majuscules',
                'required' => false,
                'data' => true,
            ])
            ->add('include_lowercase', CheckboxType::class, [
                'label' => 'Minuscules',
                'required' => false,
                'data' => true,
            ])
            ->add('include_numbers', CheckboxType::class, [
                'label' => 'Chiffres',
                'required' => false,
                'data' => true,
            ])
            ->add('include_symbols', CheckboxType::class, [
                'label' => 'Symboles',
                'required' => false,
                'data' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
