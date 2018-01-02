<?php

namespace INSSET\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, array(
                    'label' => false
            ))
            ->add('article', EntityType::class, array(
                'class' => 'INSSETBlogBundle:Article',
                'label' => false,
                'choice_label' => 'id',
                'expanded' => false,
                'multiple' => false,
                'data' => $options['defaultArticle'],
                'attr' => array(
                    'class' => 'hidden'
                )
            ))
            ->add('save',SubmitType::class, array(
                    'label' => 'Enregistrer',
                    'attr' => array(
                        'value' => 'Enregistrer'
                    )
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'INSSET\BlogBundle\Entity\Comment'
        ));

        $resolver->setRequired(array('defaultArticle'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'insset_blogbundle_comment';
    }


}
