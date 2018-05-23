<?php
	
	namespace OC\LouvreBundle\Form;
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\CollectionType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\FormEvent;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use OC\LouvreBundle\Entity\Booking;
	use Symfony\Component\Form\FormEvents;
	
	class BookingType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('bookingdate', DateType::class, array(
					'label' => 'Choisissez une date',
					'widget' => 'single_text',
					'input' => 'datetime',
					'format' => 'dd/MM/yyyy',
					'attr' => ['class' => 'datepicker'],
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
					'entry_options' => array('label' => false)

				))
				->add('save', SubmitType::class, array('label' => 'Valider'));
			
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults(
				[
					'data_class' => Booking::class,
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