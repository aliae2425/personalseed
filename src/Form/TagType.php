<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class,[
                'label' => 'Nom du tag',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Description', TextareaType::class,[
                'label' => 'Nom du tag',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Etat', ChoiceType::class, [
                'label' => "Tete d'affiche (apparait sur la page de garde)",
                'attr' => ['class' => 'form-control'],
                'choices' => ['oui' => true, 'non' => false]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
