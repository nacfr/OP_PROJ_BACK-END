<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 07/06/2018
	 * Time: 23:11
	 */
	
	namespace OC\LouvreBundle\Service;
	
	use OC\LouvreBundle\Entity\Booking;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	
	
	class Stripe extends Controller
	{
		public static $_STRIPEERROR;

        /**
         * Validation of the payment form by the stripe API
         *
         * @param Booking $booking
         * @param BookingProvider $bookingprovider
         * @return bool|string
         */
        public function getChecking(Booking $booking, BookingProvider $bookingprovider)
		{
			try {
				\Stripe\Stripe::setApiKey("sk_test_k9lZ0W70Zjtt9loUGdVbhlTr");
				
				$totalprice = $bookingprovider->getPendingOrder($booking)['total'];
				$source = $_POST['stripeSource'];
				$clientname = strip_tags($_POST['name']);
				$clientmail = strip_tags($_POST['email']);
				
				$customer = \Stripe\Customer::create(array(
					"description" => $clientname,
					"email" => $clientmail,
					"source" => $source
				));
				
				
				$charge = \Stripe\Charge::create(array(
					"amount" => ($totalprice) * 100,
					"currency" => "eur",
					"customer" => $customer['id'],
					"source" => $source,
					"description" => "Paiement Stripe - OpenClassrooms Exemple"
				));
				
				return true;
				
			} catch (\Stripe\Error\Card $e) {
				$body = $e->getJsonBody();
				$err = $body['error'];
				$message = $this->getErrorMessage($err['code']);
				return $message;
			}
		}
		
		/**
         * Returns the error code to the controller
         *
		 * @param $code_error
		 */
		public function getErrorMessage($code_error)
		{
			switch ($code_error) {
				case "balance_insufficient":
					return "Le transfert ou le paiement n'a pas pu être effectué parce que le compte associé n'a pas un solde disponible suffisant";
					break;
				
				case "card_declined":
					return "La carte a été refusée";
					break;
				
				case "email_invalid":
					return "L'adresse électronique n'est pas valide";
					break;
				
				case "expired_card":
					return "La carte a expiré. Vérifiez la date d'expiration ou utilisez une carte différente.";
					break;
				
				case "incorrect_cvc":
					return "Le code de sécurité de la carte est incorrect.";
					break;
				
				case "incorrect_number":
					return "Le numéro de carte est incorrect. Vérifiez le numéro de la carte ou utilisez une carte différente.";
					break;
				
				case "incorrect_zip":
					return "Le code postal de la carte est incorrect. Vérifiez le code postal de la carte ou utilisez une carte différente.";
					break;
				
				case "instant_payouts_unsupported":
					return "La carte de débit fournie ne prend pas en charge les paiements instantanés.";
					break;
				
				case "invalid_card_type":
					return "La carte fournie en tant n'est pas une carte de débit.";
					break;
				
				case "invalid_charge_amount":
					return "Le montant spécifié est invalide.";
					break;
				
				case "invalid_cvc":
					return "Le code de sécurité de la carte est invalide.";
					break;
				
				case "invalid_expiry_month":
					return "Le mois d'expiration de la carte est incorrect.";
					break;
				
				case "invalid_expiry_year":
					return "L'année d'expiration de la carte est incorrecte.";
					break;
				
				case "invalid_number":
					return "Le numéro de la carte est invalide.";
					break;
				
				case "processing_error":
					return "Une erreur s'est produite lors du traitement de la carte. Vérifiez que les détails de la carte sont corrects ou utilisez une carte différente.";
					break;
			}
		}
	}