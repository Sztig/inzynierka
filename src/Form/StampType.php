<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 08.11.19
 * Time: 14:27
 */

namespace App\Form;


use App\Entity\Country;
use App\Entity\Stamp;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StampType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('country', EntityType::class, [
               'class' => Country::class,
               'choice_label' => function(Country $country){
               return sprintf('%s', $country->getCountry());
               },
               'placeholder' => 'Choose origin country'
           ])
           ->add('name', TextType::class, [
               'label' => 'Stamp name'
           ])
           ->add('description', TextType::class, [
               'label' => 'Description'
           ])
           ->add('color', TextType::class, [
               'label' => 'Color'
           ])
           ->add('serialNumber', TextType::class, [
               'label' => 'Serial Number'
           ])
           ->add('file', FileType::class,[
               'mapped' => false,
               'label' => 'Please upload your image',
               'required' => false
           ])
           ->add('save', SubmitType::class);
       ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stamp::class
        ]);
    }
}