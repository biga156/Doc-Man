<?php

namespace App\Form;

use App\Entity\Files;
use App\Entity\Documents;
use App\Repository\DocumentsRepository;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($_GET['doc'])) { //* for each users
            $builder
                ->add('file', VichFileType::class, [
                    //*without VichUploader:
                    //->add('file', FileType::class, [
                    'label' => 'File',
                    'allow_delete' => true,
                    'delete_label' => 'Remove file',
                    //'download_uri' => $router->generateUrl('download_file', $file->getId()),
                    'download_uri' => "/storage/files/documents",
                    //'download_label' => 'download_file',
                    'download_label' => new PropertyPath('name'),

                    // unmapped means that this field is not associated to any entity property
                    //'mapped' => false,

                    // make it optional so you don't have to re-upload the file
                    // every time you edit the Product details
                    'required' => false,
                  
                ])
                ->add('description', TextType::class)
                ->add('type', ChoiceType::class, array(
                    'choices' => array(
                        'Audio note file' => 'audio',
                        'Document file' => 'document',
                    ),
                    'expanded' => true, 
                ))


                //Note: simple version, without queryBuilder:
                /*->add('documents', EntityType::class, [
                // query choices from this entity
                'class' => Documents::class,
                'choice_label' => 'label',
                'label' => 'Documents',
                'disabled'=>false,
             ])*/
                //Note: complet version with GET
                ->add('documents', EntityType::class, [
                    'label' => 'Add to document:',
                    'class' => Documents::class,
                    'query_builder' => function (DocumentsRepository $er) {
                        return $er
                            ->createQueryBuilder('t')
                            ->andWhere('t.id= :val')
                            ->setParameter('val', $_GET['doc']);
                    },
                    'choice_label' => 'label',
                    'choice_value' => 'id',
                    'disabled' => false,
                ]);
        } else {    //* for admin with complet list of documents in the select
            $builder
                ->add('file', VichFileType::class, [
                    'label' => 'File',
                    'allow_delete' => true,
                    'delete_label' => 'Remove file',
                    //'download_uri' => $router->generateUrl('download_file', $file->getId()),
                    // 'download_label' => 'download_file',
                    'download_label' => new PropertyPath('name'),
                    'required' => false,
                ])
                //->add('type')
                ->add('type', ChoiceType::class, array(
                    'choices' => array(
                        'Audio note file' => 'audio',
                        'Document file' => 'document',
                    ),
                    'expanded' => true, 
                ))

                ->add('documents', EntityType::class, [
                    'label' => 'Add to document',
                    'class' => Documents::class,
                    'query_builder' => function (DocumentsRepository $er) {
                        return $er
                            ->createQueryBuilder('t')
                            ->orderBy('t.label', 'ASC');
                    },
                    'choice_label' => 'label',
                    'choice_value' => 'id',
                    'disabled' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Files::class,
        ]);
    }
}
