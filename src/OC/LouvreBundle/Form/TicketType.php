<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 19/05/2018
 * Time: 19:13
 */

namespace OC\LouvreBundle\Form;


use OC\LouvreBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom',
                'attr' => [
                    'empty_value' => 'Votre nom',
                    'autocomplete' => 'off'
                ]
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'Prénom',
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ))
            ->add('dateofbirth', BirthdayType::class, array(
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'data-date-language' => 'fr',
                    'data-date-format' => 'dd-mm-yyyy',
                    'data-date-orientation' => 'bottom',
                    'data-date-start-date' => "-150y",
                    'data-date-end-date' => '0d',
                    'data-provide' => 'datepicker',
                    'class' => 'datepicker-dateofbirth',
                    'autocomplete' => 'off'
                ],
            ))
            ->add('country', CountryType::class, array(
                'label' => 'Pays',
                'attr' => [
                    'autocomplete' => 'off'
                ],
                'preferred_choices' => [
                    'AU', 'CN', 'DE', 'FR', 'GB', 'IT', 'JP', 'RU', 'ES', 'US'
                ]
            ))
            ->add('reduceprice', CheckboxType::class, array(
                'label' => 'Tarif réduit',
                'required' => false,
            ))
            ->add('handicap', CheckboxType::class, array(
                'label' => 'Personne à mobilité réduite',
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Ticket::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'tickets';
    }


}