<?php

namespace App\Form;

use App\Entity\HomepageImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class HomepageImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image1', FileType::class, [
                'label' => 'Image 1',
                'mapped' => false,  // Ne correspond pas directement à une colonne de la BDD
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M', // Taille max 2MB
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Merci d’uploader une image valide (JPG, PNG, GIF)',
                    ])
                ],
            ])
            ->add('image2', FileType::class, [
                'label' => 'Image 2',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Merci d’uploader une image valide (JPG, PNG, GIF)',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HomepageImage::class,
        ]);
    }
}
