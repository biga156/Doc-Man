<?php

namespace App\Form;

//use App\Form\ExtentedChoiceType;
//use App\Form\Extensions\ExtentedChoicesSubscriber;

use App\Entity\Tags;
use App\Entity\Users;
use App\Entity\Documents;
use Symfony\Component\Form\AbstractType;
//use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Security\Core\Security;

class DocumentsLimitedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            
            ->add('label', TextType::class, [
                'required' => true,
                'label' => 'Label (*)'
            ])
            ->add('note' , TextType::class, [
                'required' => false,
                'label' => 'Note (facultativ)'
            ])
            ->add('protection')
            ->add('validity', DateType::class, [
                'disabled' => false,
                'label' => 'Validity (facultativ)',
                'widget' => 'choice',
                'data' => new \DateTime('now-1 days'),
                'disabled' => 'disabled',
            ])
            ->add('alarm', DateTimeType::class, [
                'disabled' => false,
                'label' => 'Alarm (facultativ)',
                'widget' => 'choice',
                'data' => new \DateTime('now-1 days'),
                'disabled' => 'disabled',
            ])
            /*->add('audioNote', FileType::class,[

            ] )*/
            //TODO: this is a file!!
            ->add('audioNote')
            //*Multiple selection tags
            //?dynamic search bar?
            ->add('tags', EntityType::class, array(
                'class' => Tags::class,
                'choice_label' => 'label', 
                'multiple'=>true,
                'expanded' => false,
                'label' => 'Tags',
                'choice_value'=> 'id',
                
            ));
           

          
            
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
        ]);
    }
}
