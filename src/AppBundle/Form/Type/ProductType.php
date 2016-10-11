<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description');

        $builder->add('name', TextType::class);
        $builder->add('price', TextType::class);
        $builder->add('description', TextareaType::class);
        // $builder->add('save', SubmitType::class, array('label' => 'Zapisz') );
        
        $builder->add('category', 'entity', array(
                'class' => 'AppBundle\Entity\Category',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true
              ))
            ->add('save', 'submit');
        
        
        

    
    }

    public function getName()
    {
        return 'product';
    }
}