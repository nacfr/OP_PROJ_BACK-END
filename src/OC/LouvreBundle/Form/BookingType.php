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
						'data-date-dates-disabled' => $this->getHolidays(),
						'data-date-start-date' => "0d",
						'data-date-end-date' => '+364d',
						'data-date-today-highlight' => 'true',
						'data-date-orientation' => 'bottom',
						'data-provide' => 'datepicker',
						'class' => 'datepicker-bookingdate',
						'autocomplete' => 'off',
						
					]
				))
				->add('tickettype', ChoiceType::class, array(
					'label' => 'Type de ticket',
					'choices' => array(
						'journée' => 'day',
						'demi-journée' => 'halfday'),
					'expanded' => true,
					'attr' => ['class' => 'booking_tickettype']
				))
				->add('ticketnumber', ChoiceType::class, array(
					'label' => 'Nombre de billet',
					'choices' => array(
						'1 billet' => '1',
						'2 billets' => '2',
						'3 billets' => '3',
						'4 billets' => '4',
						'5 billets' => '5',
						'6 billets' => '6',
						'7 billets' => '7',
						'8 billets' => '8',
						'9 billets' => '9',
						'10 billets' => '10',
						'Plus de 10 billets' => '11'
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
		
		/**
		 * @return string
		 */
		private function genToken()
		{
			$bytes = openssl_random_pseudo_bytes(12);
			$hex = bin2hex($bytes);
			return $hex;
		}
		
		/**
		 * Returns a table of holidays for the current year
		 * @param null $year
		 * @return string
		 */
		private function getHolidays($year = null)
		{
			if ($year === null) {
				$year = intval(date('Y'));
			}
			
			$easterDate = easter_date($year);
			$easterDay = date('j', $easterDate);
			$easterMonth = date('n', $easterDate);
			$easterYear = date('Y', $easterDate);
			
			$holidays =
				// Dates fixes
				date('d-m-Y', mktime(0, 0, 0, 1, 1, $year)).', '.  // 1er janvier
				date('d-m-Y', mktime(0, 0, 0, 4, 2, $year)).', '.  // Paque
				date('d-m-Y', mktime(0, 0, 0, 5, 1, $year)).', '.  // Fête du travail
				date('d-m-Y', mktime(0, 0, 0, 5, 8, $year)).', '.  // Victoire des alliés
				date('d-m-Y', mktime(0, 0, 0, 5, 10, $year)).', '.  // Jeudi Assomption
				date('d-m-Y', mktime(0, 0, 0, 5, 21, $year)).', '.  // Pencôte
				date('d-m-Y', mktime(0, 0, 0, 7, 14, $year)).', '.  // Fête nationale
				date('d-m-Y', mktime(0, 0, 0, 8, 15, $year)).', '. // Assomption
				date('d-m-Y',mktime(0, 0, 0, 11, 1, $year)).', '.  // Toussaint
				date('d-m-Y',mktime(0, 0, 0, 11, 11, $year)).', '.  // Armistice
				date('d-m-Y',mktime(0, 0, 0, 12, 25, $year)).', '.  // Noel
				date('d-m-Y',mktime(0, 0, 0, $easterMonth, $easterDay + 1, $easterYear)).', '.
				date('d-m-Y',mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear)).', '.
				date('d-m-Y',mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear));
			
			
			return $holidays;
			
		}
	}