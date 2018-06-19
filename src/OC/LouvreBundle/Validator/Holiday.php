<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 19/06/2018
	 * Time: 22:48
	 */
	
	namespace OC\LouvreBundle\Validator;
	use Symfony\Component\Validator\Constraint;
	
	
	/**
	 * @Annotation
	 */
	class Holiday extends Constraint
	{
		public $message = "Impossible de réserver d'effectuer une réservation pour cette date";
		
	}