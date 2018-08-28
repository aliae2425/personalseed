<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;




class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, [
                'label' => 'Nom du projet',
                'attr' => ['class' => 'form-control']
                ])
            ->add('Description', TextareaType::class, [
                'label' => 'Description du projet',
                'attr' => ['class' => 'form-control']
                ])
            ->add('Tags', EntityType::class,[
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple'=>'true',
                'label'=> 'Tag du projet',
                'attr' => ['class' => 'form-control']
                ])
            /*->add('files', FileType::class, [
                'data_class' => null,
                'multiple' => 'true',
                'label' => 'Photos du projet',
                'attr' => ['class' => 'form-control']

            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
