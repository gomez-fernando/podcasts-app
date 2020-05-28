<?php

namespace App\Form;

use App\Entity\Posts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use App\Entity\Product;
use Symfony\Component\Validator\Constraints\File;

class PostsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', TextType::class, array(
                'label' => 'Título'
            ))

            ->add(
                'foto',
                FileType::class,
                [
                    'label' => 'Selecciona una imagen',
                    'required' => true,
                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,

                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '2048k',
                            'mimeTypes' => [
                                'image/gif',
                                'image/jpeg',
                                'image/png',
                                'image/svg+xml',
                                'image/webp'
                            ],
                            'mimeTypesMessage' => 'Por favor suba una imagen válida de no más de 2048k',
                        ])
                    ],
                ]
            )
            ->add(
                'audio',
                FileType::class,
                [
                    'label' => 'Selecciona un audio',
                    'mapped' => false,
                    'required' => true,
                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '30048k',
                            'mimeTypes' => [
                                'audio/ogg',
                                'audio/x-wav',
                                'audio/webm',
                                'audio/aac',
                                'audio/mpeg',
                                'audio/mpega'

                            ],
                            'mimeTypesMessage' => 'Por favor suba un archivo de audio de no más de 30048k',
                        ])
                    ],
                ]
            )
            ->add('descripcion', TextareaType::class, array(
                'label' => 'Descripción'
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Guardar'
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
