<?php

namespace INSSET\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextareaType::class, array('label' => false, 'attr' => array('placeholder' => 'Écrivez votre commentaire ici...')));
        $builder->add('article', EntityType::class, array('class' => 'INSSETBlogBundle:Article', 'choice_label' => 'id', 'label' => false, 'expanded' => false, 'multiple' => false, 'data' => $options['defaultArticle'], 'attr' => array('class' => 'hidden')));
        $builder->add('save',SubmitType::class, array('label' => 'Envoyer', 'attr' => array('value' => 'Enregistrer')));
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
