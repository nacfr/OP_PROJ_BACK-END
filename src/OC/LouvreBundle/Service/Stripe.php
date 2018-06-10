<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 07/06/2018
	 * Time: 23:11
	 */
	
	namespace OC\LouvreBundle\Service;
	
	use OC\LouvreBundle\Entity\Tbooking;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Response;
	
	class Stripe extends Controller
	{
		public function getchecking(Tbooking $booking, BookingCalcul $bookingcalcul)
		{
			
			\Stripe\Stripe::setApiKey("sk_test_k9lZ0W70Zjtt9loUGdVbhlTr");
			
			// Get the credit card details submitted by the form
			$token = $_POST['stripeToken'];
			$bookingprice = $bookingcalcul->GetPrice($booking);
			
			// Create a charge: this will charge the user's card
			try {
				$charge = \Stripe\Charge::create(array(
					"amount" => 1000, // Amount in cents
					"currency" => "eur",
					"source" => $token,
					"description" => "Paiement Stripe - OpenClassrooms Exemple"
				));
				$result = true;
				return $result;
			} catch(\Stripe\Error\Card $e) {
				$result = false;
				return $result;
				// The card has been declined
			}
		}
		
	}