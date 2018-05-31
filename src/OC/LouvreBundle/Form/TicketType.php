<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 19/05/2018
	 * Time: 19:13
	 */
	
	namespace OC\LouvreBundle\Form;
	

	use OC\LouvreBundle\Entity\Tticket;
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
					'label' => 'Nom'))
				->add('firstname', TextType::class, array(
					'label' => 'Prénom'))
                ->add('dateofbirth', DateType::class, array(
                    'label' => 'Date de naissance',
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => [
                        'data-date-language' => 'fr',
                        'data-date-format' => 'dd-mm-yyyy',
                        'data-date-orientation' => 'bottom',
                        'data-provide' => 'datepicker',
                        'class' => 'datepicker-dateofbirth',
                    ]
                ))
				->add('country', CountryType::class)
			    ->add('reduceprice', CheckboxType::class, array(
                    'label' => 'Tarif réduit',
                    'required' => false,
                ))
                ->add('handicap', CheckboxType::class, array(
                    'label' => 'Personne à mobilité réduite',
                    'required' => false,
                ))
                ;
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults(
				[
					'data_class' => Tticket::class,
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