<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 08.11.19
 * Time: 14:27
 */

namespace App\Form;


use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Stamp;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StampType extends AbstractType
{
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {

        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->user->getId();
       $builder
           ->add('country', EntityType::class, [
               'class' => Country::class,
               'choice_label' => function(Country $country){
               return sprintf('%s', $country->getCountry());
               },
               'placeholder' => 'Choose origin country'
           ])
           ->add('category', EntityType::class, [
               'class' => Category::class,
               'query_builder' => function(CategoryRepository $er) use ($user) {
                   return $er->findAllByUser($user);
               },
               'choice_label'=>'category',
               'choice_value'=>'id',
               'placeholder' => 'No category',
               'required' => false,
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
           ->add('year', IntegerType::class, [
               'label' => 'Year'
               ])
           ->add('file', FileType::class,[
               'mapped' => false,
               'label' => 'Please upload your image',
               'required' => false,
               'constraints' => [
                   new File([
                       'mimeTypes' => [
                           'image/jpeg',
                           'image/png'
                       ],
                       'maxSize' => '10M'
                   ])
               ],
           ])
           ->add('Save', SubmitType::class, [
               'attr'=> array('class'=>'btn btn-outline-primary')
           ]);
       ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stamp::class
        ]);
    }
}