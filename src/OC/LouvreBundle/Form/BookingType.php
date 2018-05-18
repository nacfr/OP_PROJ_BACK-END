<?php

namespace OC\LouvreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, array(
                'label' => 'Choisissez une date',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr' => ['class' => 'datepicker'],
            ))
            ->add('jPost', ChoiceType::class, array(
                'choices' => array(
                    'journée' => 'day',
                    'demi-jounrée' => 'halfday'),
                'expanded' => true
            ))
            ->add('Nb_billet', ChoiceType::class, array(
                'choices' => array(
                    '1 billet' => '1',
                    '2 billets' => '2',
                    '3 billets' => '3'
                ),
                'preferred_choices' => array('1 billet'),
            ));
    }


    /**
     * {@inheritdoc}
     */
    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix() {
        return 'booking';
    }
}