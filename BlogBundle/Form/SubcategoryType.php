<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use BlogBundle\CoreBundle\Form\ImageType;

/**
 * Class SubcategoryType
 */
class SubcategoryType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('metaTitle')
            ->add('metaDescription')
            ->add('metaTags')
//            ->add('image', new ImageType(), array(
//                'error_bubbling' => false,
//                'required' => false
//            ))
            ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Category'
        ));
    }

}
