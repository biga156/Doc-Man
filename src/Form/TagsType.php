<?php

namespace App\Form;

use App\Entity\Tags;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class TagsType extends AbstractType
{
  /*  public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new CallbackTransformer(
            function ($original) {
                return $original; //? implode(', ', $original) : '';
            },
            function ($submitted) {
                if (!$submitted) {
                    return [];
                }

                $submitted = array_map(function($tag) {
                    return trim($tag);
                }, explode(',', $submitted));

                return $submitted;
            }
        ));
    }*/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            //->add('documents')
        ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tags::class,
        ]);
    }
}
