<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;


class PostType extends AbstractType
{
    protected $translator;
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         
        if(isset($options['translator'])){
            $this->translator = $options['translator'];
        }
        
        $tagConf = array(
                'class'    => 'BlogBundle\Entity\Tag',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er){
                                        return $er->createQueryBuilder('u')
                                            ->where('u.active = 1')
                                            ->orderBy('u.name', 'ASC');
                                    },
                'multiple'  => true,
                'expanded' => false,
                'required' => false,
            );
        $builder
            ->add('translations', TranslationsType::class, array(
                    'fields' => array(                               
                        'title' => array( 'label' => $this->translator->trans('title')),
                        'description' => array( 'label' => $this->translator->trans('description')),
                        'slug' => array(                         
                            'label' => $this->translator->trans('slug'),                     
                            'display' => false
                        )
                    )
                ))
            ->add('categories', EntityType::class, array(
                'class' => 'BlogBundle:Category',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                                        return $er->createQueryBuilder('a')
                                                ->select('a')
                                                ;

                },
                'required' => false,
                'multiple'  => true,
                'expanded' => false
            ))
            ->add('tags', EntityType::class, $tagConf)
            ->add('published', DateTimeType::class, array(
                    'label' => 'Fecha de publicación',
                    'format' => 'dd/MM/yyyy',
                    'widget' => 'single_text',
                    'required' => true
                )
            )
            ->add('highlighted', null, array(
                'required' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Post',
            'translator' => null
        ));
    }

}
