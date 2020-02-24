<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 23.02.20
 * Time: 17:24
 */

namespace App\Form;


use App\Entity\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('collection', TextType::class, [
                'label' => 'Collection name',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Public' => 'public',
                    'Private'=> 'private',
                ],
                'label' => 'Collection status',
            ])
            ->add('Save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Collection::class,
        ]);
    }
}