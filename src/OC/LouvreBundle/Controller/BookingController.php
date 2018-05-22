<?php
	/**
	 * Created by PhpStorm.
	 * User: Pruvost
	 * Date: 16/05/2018
	 * Time: 13:47
	 */
	
	namespace OC\LouvreBundle\Controller;
	
	
	use OC\LouvreBundle\Entity\Booking;
	use OC\LouvreBundle\Entity\Ticket;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\HttpFoundation\Response;
	use OC\LouvreBundle\Form\BookingType;
	
	class BookingController extends Controller
	{
		public function indexAction(Request $request)
		{
			
			$booking = new Booking();
			
			//Création formulaire
			/*
			$form = $this->createFormBuilder()
				->add('bookingdate', DateType::class, array(
					'label' => 'Choisissez une date',
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
				))
				->add('save', SubmitType::class, array('label' => 'Create Task'))
				->getForm();
			*/
			
			$form = $this->createForm(BookingType::class, $booking);
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$booking = $form->getData();

				return $this->redirectToRoute('oc_sucess');
			}
			
			
			
			return $this->render('@OCLouvre/Booking/accueil.html.twig', array('form' => $form->createView()));
			
			
		}
		
		
		public function widgetAction()
		{
			$listPurchase = array(
				array('qt' => '0', 'title' => ' x Gratuit', 'price' => '0€'),
				array('qt' => '0', 'title' => ' x Enfant', 'price' => '0€'),
				array('qt' => '0', 'title' => ' x Normal', 'price' => '0€'),
				array('qt' => '0', 'title' => ' x Sénior', 'price' => '0€'),
				array('qt' => '0', 'title' => ' x Réduit', 'price' => '0€')
			);
			
			
			return $this->render('@OCLouvre/Booking/widgetPurchase.html.twig', array(
				'listPurchase' => $listPurchase
			));
			
		}
		
		public function sucessAction()
		{
			return $this->render('@OCLouvre/Booking/sucess.html');
		}
	}