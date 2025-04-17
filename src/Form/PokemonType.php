<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Pokemon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PokemonType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom du Pokémon',
                ],
            ])
            ->add('num', IntegerType::class, [
                'label' => 'Numéro',
                'attr' => [
                    'placeholder' => 'Numéro du Pokémon',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du Pokémon',
                ],
            ])
            ->add('zone')
            ->add('img', UrlType::class, [
                'label' => 'Image',
                'attr' => [
                    'placeholder' => 'URL de l\'image du Pokémon',
                ],
            ])
            ->add('type1', EntityType::class, [
                'label' => 'Type 1',
                'class' => Type::class,
                'choice_label' => 'nom',
            ])
            ->add('type2', EntityType::class, [
                'label' => 'Type 2',
                'class' => Type::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }
}
