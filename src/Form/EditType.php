<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class EditType extends AbstractType
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Заголовок',
                'constraints' => [
                    new Type(['type' => 'string']),
                    new Length(['max' => 200]),
                ],
            ])
            ->add('preview', TextareaType::class, [
                'label' => 'Анонс',
                'constraints' => [
                    new Type(['type' => 'string']),
                    new Length(['max' => 1000]),
                ],
                'attr' => [
                    'rows' => 6,
                ],
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Текст новости',
                'constraints' => [
                    new Type(['type' => 'string']),
                    new Length(['max' => 4000]),
                ],
                'attr' => [
                    'rows' => 14,
                ],
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Изображение',
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxWidth'   => $this->params->get('image_max_width'),
                        'maxHeight'  => $this->params->get('image_max_height'),
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                        ],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
