<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Fournisseur;
use App\Entity\SousRubrique;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom du produit',
            'attr' => [
                'placeholder' => 'Produit',
            ],
            'constraints' => [
                new Regex([
                    'pattern' => '/^[A-Za-zéèàçâêûîôäëüïö\_\-\s]+$/',
                    'message' => 'Caratère(s) non valide(s)'
                ]),
            ]
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'attr' => [
                'placeholder' => 'Description',
            ],
            'constraints' => [
                new Regex([
                    'pattern' => '/^[A-Za-zéèàçâêûîôäëüïö\_\-\s]+$/',
                    'message' => 'Caratère(s) non valide(s)'
                ]),
            ]
        ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'attr' => [
                    'placeholder' => 'Prix',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d+(,\d{1,2})?$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            ->add('picture', FileType::class,[
                'label' => 'Image du produit',
                'help' => 'Selectionnez une image',
                'attr' => [
                    'placeholder' => 'image.jpg',
                ],
            ])
            ->add('stock', IntegerType::class,[
                'label' => 'Stock du produit',
                'attr' => [
                    'placeholder' => 'Stock',
                ],
            ])
            ->add('active', ChoiceType::class,[
                'choices' => [
                    'Activation du produit' => [
                        'Oui' => 'active_yes',
                        'Non' => 'active_no',
                    ],
                ],
            ])
            ->add('sous_rubrique', EntityType::class, [
                'class' => SousRubrique::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sous_rubrique')
                        ->orderBy('sous_rubrique.rubrique', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('fournisseur', EntityType::class, [
                'class' => Fournisseur::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('fournisseur')
                    ;
                },
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
