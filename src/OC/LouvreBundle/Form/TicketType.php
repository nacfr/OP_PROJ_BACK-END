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
				->add('dateofbirth', BirthdayType::class, array(
					'label' => 'Date de naissance',
					'format' => 'dd/MM/yyyy',
				))
				->add('country', CountryType::class);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults(
				[
					'data_class' => Ticket::class,
				]
			);
		}
		
		
	}