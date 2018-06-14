<?php
	
	namespace OC\LouvreBundle\Form;
	
	use OC\LouvreBundle\Entity\Booking;
    use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\HiddenType;
	use Symfony\Component\Form\Extension\Core\Type\CollectionType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Validator\Constraints\NotBlank;
	
	class BookingType extends AbstractType
	{
		
		private function genToken()
        {
            $bytes = openssl_random_pseudo_bytes(12);
            $hex   = bin2hex($bytes);
            return $hex;
        }

	    public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('bookingdate', DateType::class, array(
                    'label' => 'Choisissez une date',
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => [
                        'data-date-language' => 'fr',
                        'data-date-format' => 'dd-mm-yyyy',
                        'data-date-days-of-week-disabled' => '02',
                        'data-date-start-date' => "0d",
                        'data-date-end-date' => 'Infinity',
                        'data-date-today-highlight' => 'true',
                        'data-date-orientation' => 'bottom',
                        'data-provide' => 'datepicker',
                        'class' => 'datepicker-bookingdate',
                        'autocomplete' => 'off'
                    ]
				))
				->add('tickettype', ChoiceType::class, array(
					'label' => 'Type de ticket',
					'choices' => array(
						'journée' => 'day',
						'demi-jounrée' => 'halfday'),
					'expanded' => true
				))
				->add('ticketnumber', ChoiceType::class, array(
					'label' => 'Nombre de billet',
					'choices' => array(
						'1 billet' => '1',
						'2 billets' => '2',
						'3 billets' => '3'
					),
					'preferred_choices' => array('1 billet'),
					'attr' => ['class' => 'add-ticketnumber-form-widget']
				))
				->add('tickets', CollectionType::class, array(
					'entry_type' => TicketType::class,
					'allow_add' => true,
					'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => array(
                        'attr' => array('class' => 'ticket-box'),
                    ),
				))
                ->add('btoken', HiddenType::class, array(
                        'data' => $this->genToken()
                    ))
				->add('save', SubmitType::class, array('label' => 'Valider'));
			
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults(
				[
					'data_class' => Booking::class,
                    'validation_groups' => array('registration')
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
			return 'booking';
		}
	}