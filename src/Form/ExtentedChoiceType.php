<?php
//! Not in use
//* Not needed, this is an extension for tags JSON version (Form\DocumentsType)

namespace App\Form;

    use Symfony\Component\Form\AbstractType;

    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\Options;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

    class ExtentedChoiceType extends AbstractType
    {
       /**
        * {@inheritdoc}
        */
        public function configureOptions(OptionsResolver $resolver)
        {  
            $resolver->setDefaults(array('choices'=>array()));
        }

        public function getParent()
        {
            return ChoiceType::class;
        }
    }